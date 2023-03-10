<?php

declare(strict_types=1);

namespace Tests\Functional\Repository;

use Comment\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryCommentRepository implements CommentRepository
{
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function find(string $id)
    {
        foreach ($this->comments->getIterator() as $comment) {
            if ($comment->id === $id) {
                return $comment;
            }
        }

        return null;
    }

    public function get(int $key)
    {
        return $this->comments->get($key);
    }
}
