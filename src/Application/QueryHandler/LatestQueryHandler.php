<?php

declare(strict_types=1);

namespace Application\QueryHandler;

use Application\Query\LatestQuery;
use Comment\Repository\CommentRepository;
use Doctrine\Common\Collections\Collection;
use SharedKernel\Application\Query\QueryHandler;

class LatestQueryHandler implements QueryHandler
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(LatestQuery $query): Collection
    {
        return $this->commentRepository->findLatest($query->getNumber());
    }
}
