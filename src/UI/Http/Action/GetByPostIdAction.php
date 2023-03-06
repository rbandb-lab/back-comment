<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Query\ByPostQuery;
use Comment\Repository\PostRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\GetCommentsResponder;
use OpenApi\Annotations as OA;

#[Route(path: "/api/posts/{id}/comments", name: "list_comments_by_post_id", methods: ["GET"])]
#[OA\Response(
    response: 200,
    description: 'Returns the comments of a post',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Comments::class, groups: ['full']))
    )
)]
#[OA\Parameter(
    name: 'order',
    in: 'query',
    description: 'id of an existing post',
    schema: new OA\Schema(type: 'string')
)]
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
