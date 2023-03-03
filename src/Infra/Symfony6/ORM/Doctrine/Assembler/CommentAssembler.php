<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Assembler;

use Comment\Service\CommentIdGenerator;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentId;
use Infra\Symfony6\ORM\Doctrine\Entity\Comment as DoctrineComment;
use Comment\Model\Comment;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CommentAssembler
{
    public function fromModel(Comment $comment): DoctrineComment
    {
        $commentId = $comment->getCommentId();
        if(is_string($commentId)){
            $id = Uuid::isValid($commentId) ? Uuid::fromString($commentId) : $commentId;
        }
        if($commentId instanceof CommentId){
            $id = $commentId->getUuid();
        }

        $doctrineComment = new DoctrineComment(
            id: $id,
            postId: $comment->getPostId(),
            commentContent: $comment->getCommentContent()->getContent(),
            parent: null
        );

        $doctrineComment->setCreatedAt($comment->getCreatedAt());
        $doctrineComment = $this->addChildren($doctrineComment, $comment);
        return $doctrineComment;
    }

    private function addChildren(DoctrineComment $doctrineComment, Comment $comment): DoctrineComment
    {
        foreach ($comment->getSubComments()->getIterator() as $subComment) {
            $doctrineSubComment = $this->fromModel($subComment);
            $doctrineSubComment->setParent($doctrineComment);
            $doctrineComment->addChild($doctrineSubComment);
        }
        return $doctrineComment;
    }


    public function fromOrm(DoctrineComment $ormComment): Comment
    {
        $author = $ormComment->getUser();
        $comment = new Comment(
            commentId: new CommentId($ormComment->getId()),
            postId: $ormComment->getPostId(),
            author: new Author(
                id: (string) $author->getId(),
                username: $author->getUsername()
            ),
            commentContent: $ormComment->getCommentContent()
        );

        foreach ($ormComment->getRatings()->getIterator() as $ormRating){
            $comment->addRating(RatingAssembler::fromOrm($ormRating));
        }

        foreach ($comment->getSubComments()->getIterator() as $ormSubComment) {
            $subComment = $this->fromOrm($ormSubComment);
            $subComment->setParentId($comment->getCommentId());
            $comment->addSubComment($subComment);
        }

        return $comment;
    }
}
