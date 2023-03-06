<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Query\LatestQuery;
use SharedKernel\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\GetCommentsResponder;

#[Route(path: "/api/latest/{number}", name: "latest_comments", methods: ["GET"])]
final class GetLatestAction
{
    private QueryBusInterface $queryBus;
    private GetCommentsResponder $getCommentsResponder;
    public function __construct(
        QueryBusInterface $queryBus,
        GetCommentsResponder $getCommentsResponder,
    ) {
        $this->queryBus = $queryBus;
        $this->getCommentsResponder = $getCommentsResponder;
    }

    public function __invoke(Request $request, int $number): Response
    {
        $results = $this->queryBus->dispatch(new LatestQuery($number));

        return $this->getCommentsResponder->respond($results, $request->headers->all());
    }
}
