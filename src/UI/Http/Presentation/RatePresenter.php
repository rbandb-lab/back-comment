<?php

declare(strict_types=1);

namespace UI\Http\Presentation;

use Comment\Model\Comment;
use UI\Http\Normalizer\CommentNormalizer;

final class RatePresenter
{
    public function present(Comment $comment): array
    {
        $createdAt = new \DateTime();
        $createdAt->setTimestamp($comment->getCreatedAt());
        $presenter = $this;
        return CommentNormalizer::normalizeComment($comment);
    }
}
