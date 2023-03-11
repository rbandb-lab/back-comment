<?php

declare(strict_types=1);

namespace Comment\Repository;

use Comment\Model\Comment;
use Comment\ValueObject\CommentId;

interface CommentRepository
{
    public function find(CommentId $id);

    public function findAll();

    public function save(Comment $comment): void;

    public function findByPostId(string $postId);

    public function findLatest(int $number);
}
