<?php

declare(strict_types=1);

namespace Comment\Model;

use Comment\Exception\CannotDeleteCommentWithChildrenException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Post
{
    private string $id;
    private Collection $comments;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->comments = new ArrayCollection();
    }

    public function addComment(Comment $drafComment): void
    {
        $this->comments->add($drafComment);
    }

    public function removeComment(Comment $drafComment): void
    {
        if ($drafComment->hasChildren() === false) {
            $this->comments->removeElement($drafComment);

            return;
        }

        throw new CannotDeleteCommentWithChildrenException($drafComment->getCommentId());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
