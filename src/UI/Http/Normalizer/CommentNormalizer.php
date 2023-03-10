<?php

declare(strict_types=1);

namespace UI\Http\Normalizer;

use Comment\Model\Comment;

final class CommentNormalizer
{
    public static function normalizeComment(Comment $comment): array
    {
        $createdAt = new \DateTime();
        $createdAt->setTimestamp($comment->getCreatedAt());

        $data = [
            'id' => (string) $comment->getCommentId(),
            'parentId' => (string) $comment->getParentId(),
            'author' => $comment->getAuthor()->getUsername(),
            'post' => $comment->getPostId(),
            'content' => $comment->getCommentContent()->getContent(),
            'createdAt' => $createdAt->format('c'),
        ];

        if ($comment->getRating() !== null) {
            $data['rating'] = $comment->getRating();
        }

        $data['subComments'] = $comment->getSubComments()->count() > 0 ? array_map(
            function (Comment $subComment) {
                return CommentNormalizer::normalizeComment($subComment);
            },
            $comment->getSubComments()->toArray()
        ) : [];

        return $data;
    }
}
