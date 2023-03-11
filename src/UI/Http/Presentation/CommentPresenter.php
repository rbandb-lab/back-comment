<?php

declare(strict_types=1);

namespace UI\Http\Presentation;

use Comment\Model\Comment;
use SharedKernel\Presentation\PresenterInterface;
use UI\Http\Normalizer\CommentNormalizer;

final class CommentPresenter implements PresenterInterface
{
    public function present(Comment $comment): array
    {
        $createdAt = new \DateTime();
        $createdAt->setTimestamp($comment->getCreatedAt());
        $presenter = $this;

        return CommentNormalizer::normalizeComment($comment);
    }
}
