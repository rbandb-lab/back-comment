<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Query\ByPostQuery;
use Application\Query\LatestQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\GetCommentsResponder;

#[Route(path: "/api/latest/{number}", name: "latest_comments", methods: ["GET"])]
final class GetLatestAction
{
    use EnvelopeTrait;
    private MessageBusInterface $queryBus;
    private GetCommentsResponder $getCommentsResponder;
    public function __construct(
        MessageBusInterface $queryBus,
        GetCommentsResponder $getCommentsResponder,
    ) {
        $this->queryBus = $queryBus;
        $this->getCommentsResponder = $getCommentsResponder;
    }

    public function __invoke(Request $request, int $number): Response
    {
        $results = $this->handle(
            $this->queryBus->dispatch(new LatestQuery($number))
        );

        return $this->getCommentsResponder->respond($results, $request->headers->all());
    }
}
