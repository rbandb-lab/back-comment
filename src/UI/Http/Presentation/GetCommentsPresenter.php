<?php

declare(strict_types=1);

namespace UI\Http\Presentation;

use Comment\Model\Comment;
use Doctrine\Common\Collections\Collection;
use SharedKernel\Presentation\PresenterInterface;
use UI\Http\Normalizer\CommentNormalizer;

final class GetCommentsPresenter implements PresenterInterface
{
    public function present(Collection $comments): array
    {
        $data = [];
        foreach ($comments->getIterator() as $comment) {
            $data[] = CommentNormalizer::normalizeComment($comment);
        }

        return $data;
    }
}
