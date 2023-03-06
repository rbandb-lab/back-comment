<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Query\ByPostQuery;
use Infra\Symfony6\Messenger\EnvelopeTrait;
use SharedKernel\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\GetCommentsResponder;

#[Route(path: "/api/posts/{id}/comments", name: "list_comments_by_post_id", methods: ["GET"])]
final class GetByPostIdAction
{
    use EnvelopeTrait;
    private QueryBusInterface $queryBus;
    private GetCommentsResponder $getCommentsResponder;
    public function __construct(
        QueryBusInterface $queryBus,
        GetCommentsResponder $getCommentsResponder,
    ) {
        $this->queryBus = $queryBus;
        $this->getCommentsResponder = $getCommentsResponder;
    }
    public function __invoke(Request $request, string $id): Response
    {
        $results = $this->queryBus->dispatch(new ByPostQuery($id));

        return $this->getCommentsResponder->respond($results, $request->headers->all());
    }
}
