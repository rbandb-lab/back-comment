<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Repository;

use Comment\Model\Comment;
use Comment\Repository\CommentRepository as CommentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Infra\Symfony6\ORM\Doctrine\Assembler\CommentAssembler;
use Infra\Symfony6\ORM\Doctrine\Entity\Comment as DoctrineComment;

final class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    private CommentAssembler $assembler;

    public function __construct(ManagerRegistry $registry, CommentAssembler $assembler)
    {
        parent::__construct($registry, DoctrineComment::class);
        $this->assembler = $assembler;
    }

    public function save(Comment $comment): void
    {
        $entity = $this->assembler->fromModel($comment);
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    public function findByPostId(string $postId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c.postId = :postId')
            ->setParameter('postId', $postId);

        return $this->hydrateModel($qb->getQuery()->getResult());
    }

    public function findLatest(int $number)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($number)
        ;

        return $this->hydrateModel($qb->getQuery()->getResult());
    }

    private function hydrateModel(array $result): Collection
    {
        $result = new ArrayCollection($result);
        $comments = [];
        foreach ($result->getIterator() as $ormComment) {
            $comments[] = $this->assembler->fromOrm($ormComment);
        }

        return new ArrayCollection($comments);
    }
}
