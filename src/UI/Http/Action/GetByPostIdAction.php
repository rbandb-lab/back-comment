<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Query\ByPostQuery;
use Comment\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\GetCommentsResponder;

#[Route(path: "/api/posts/{id}/comments", name: "list_comments", methods: ["GET"])]
final class GetByPostIdAction
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

    public function __invoke(Request $request, string $id): Response
    {
        $results = $this->handle(
            $this->queryBus->dispatch(new ByPostQuery($id))
        );

        return $this->getCommentsResponder->respond($results, $request->headers->all());
    }
}
