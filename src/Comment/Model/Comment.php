<?php

declare(strict_types=1);

namespace Comment\Model;

use Comment\Exception\CannotRateCommentTwiceException;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Comment\ValueObject\CommentRating;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Comment
{
    private string $id;
    private Author $author;
    private string $articleId;
    private Collection $subComments;
    private int $createdAt;
    private CommentContent $commentContent;
    private Collection $ratings;
    private ?string $parentId = null;

    public function __construct(string $id, string $articleId, Author $author, string $commentContent)
    {
        $this->id = $id;
        $this->articleId = $articleId;
        $this->author = $author;
        $this->subComments = new ArrayCollection();
        $this->createdAt = time();
        $this->commentContent = new CommentContent($commentContent);
        $this->ratings = new ArrayCollection();
    }

    public function getCommentContent(): CommentContent
    {
        return $this->commentContent;
    }

    public function reply(Comment $replyComment): void
    {
        $replyComment->setParentId($this->getId());
        $this->subComments->add($replyComment);
    }

    public function hasChildren(): bool
    {
        return $this->subComments->count() > 0;
    }

    public function addRating(CommentRating $rating): void
    {
        foreach ($this->ratings->getIterator() as $existingRating) {
            /** @var CommentRating $existingRating */
            if ($existingRating->getRatingAuthor()->getId() === $rating->getRatingAuthor()->getId()) {
                throw new CannotRateCommentTwiceException('Author has already submitted rating');
            }
        }
        $this->ratings->add($rating);
    }

    public function getRating(): ?float
    {
        $ratingResult = 0;
        $count = 0;
        /** @var CommentRating $rating */
        foreach ($this->ratings->getIterator() as $rating) {
            $ratingResult += $rating->getRate();
            ++$count;
        }

        return $ratingResult / $count;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getArticleId(): string
    {
        return $this->articleId;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getSubComments(): Collection
    {
        return $this->subComments;
    }

    public function addSubComment(Comment $comment): void
    {
        $comment->setParentId($this->id);
        $this->subComments->add($comment);
    }
}
