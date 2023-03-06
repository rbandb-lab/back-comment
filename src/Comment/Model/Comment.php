<?php

declare(strict_types=1);

namespace Comment\Model;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Application\Command\CommentCommand;
use Comment\Entity\CommentInterface;
use Comment\Exception\CannotRateCommentTwiceException;
use Comment\Exception\CannotRateItsOwnCommentException;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Comment\ValueObject\CommentId;
use Comment\ValueObject\CommentRating;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Infra\ApiPlatform\State\Processor\CommentProcessor;
use Infra\ApiPlatform\State\provider\CommentProvider;
use ApiPlatform\OpenApi\Model;


final class Comment implements CommentInterface
{
    private CommentId|string $commentId;
    private Author $author;
    private string $postId;
    private Collection $subComments;
    private int $createdAt;
    private CommentContent $commentContent;
    private Collection $ratings;
    private CommentId|string|null $parentId = null;

    public function __construct(CommentId|string $commentId, string $postId, Author $author, string $commentContent)
    {
        $this->commentId = $commentId;
        $this->postId = $postId;
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
        $replyComment->setParentId($this->getCommentId());
        $this->subComments->add($replyComment);
    }

    public function hasChildren(): bool
    {
        return $this->subComments->count() > 0;
    }

    public function addRating(CommentRating $rating): void
    {
        if ($this->author->id === $rating->getRatingAuthor()->id) {
            throw new CannotRateItsOwnCommentException("Author tried to rate its own comment");
        }

        foreach ($this->ratings->getIterator() as $existingRating) {
            /** @var CommentRating $existingRating */
            if ($existingRating->getRatingAuthor()->id === $rating->getRatingAuthor()->id) {
                throw new CannotRateCommentTwiceException("Author has already submitted rating");
            }
        }
        $this->ratings->add($rating);
    }

    public function getRating(): ?float
    {
        $sum = 0;
        $count = $this->ratings->count();
        if ($count > 0) {
            $ratings = $this->ratings->toArray();
            $arrayOfRatings = array_map(function (CommentRating $rating) {
                return $rating->getRate();
            }, $ratings);
            $sum = array_sum($arrayOfRatings);
        }

        return $count > 0 ? $sum / $count : 0;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function getCommentId(): CommentId|string
    {
        return $this->commentId;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getParentId(): CommentId|string|null
    {
        return $this->parentId;
    }

    public function setParentId(CommentId|string|null $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getSubComments(): Collection
    {
        return $this->subComments;
    }

    public function addSubComment(Comment $comment): void
    {
        $comment->setParentId($this->getCommentId());
        $this->subComments->add($comment);
    }
}
