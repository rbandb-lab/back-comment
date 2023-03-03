<?php

declare(strict_types=1);

namespace Tests\Functional\Repository;

use Comment\Model\Comment;
use Comment\Repository\CommentRepository;
use Comment\ValueObject\CommentId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Infra\Symfony6\ORM\Doctrine\Assembler\CommentAssembler;
use Ramsey\Uuid\UuidInterface;

class InMemoryCommentRepository implements CommentRepository
{
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function find(CommentId $commentId)
    {
        foreach ($this->comments->getIterator() as $comment) {
            if ($comment->getCommentId() === $commentId) {
                return $comment;
            }
        }
        return null;
    }

    public function findAll()
    {
        return $this->comments;
    }

    public function get(int $key)
    {
        return $this->comments->get($key);
    }

    public function save(Comment $comment): void
    {
        foreach ($this->comments->getIterator() as $storedComment) {
            if ($storedComment->getCommentId() === $comment->getCommentId()) {
                $this->comments->removeElement($storedComment);
            }
        }
        $this->comments->add($comment);
    }

    public function findByPostId(string $postId)
    {
        $results = new ArrayCollection();
        foreach ($this->comments->getIterator() as $comment) {
            if ($comment->getPostId() === $postId) {
                $results->add($comment);
            }
        }
        return $results;
    }

    public function findLatest(int $number)
    {
        $iterator = $this->comments->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getCreatedAt() < $b->getCreatedAt()) ? -1 : 1;
        });
        return new ArrayCollection(iterator_to_array($iterator));
    }
}
