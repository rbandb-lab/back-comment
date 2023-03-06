<?php

declare(strict_types=1);

namespace Application\QueryHandler;

use Application\Query\ByPostQuery;
use Comment\Repository\CommentRepository;
use SharedKernel\Application\Query\QueryHandler;

final class ByPostQueryHandler implements QueryHandler
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(ByPostQuery $query)
    {
        return $this->commentRepository->findByPostId($query->getPostId());
    }
}
