<?php

declare(strict_types=1);

namespace Comment\Repository;

use Comment\Model\Comment;
use Comment\ValueObject\CommentId;
use Infra\Symfony6\ORM\Doctrine\Assembler\CommentAssembler;
use Ramsey\Uuid\UuidInterface;

interface CommentRepository
{
    public function find(CommentId $id);

    public function findAll();

    public function save(Comment $comment): void;

    public function findByPostId(string $postId);

    public function findLatest(int $number);
}
