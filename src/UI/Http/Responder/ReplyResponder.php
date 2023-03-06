<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Comment\Model\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UI\Http\Presentation\ReplyPresenter;

final class ReplyResponder extends ApiResponder
{
    private ReplyPresenter $presenter;

    public function __construct(ReplyPresenter $presenter, PropertyAccessorInterface $accessor)
    {
        parent::__construct($accessor);
        $this->presenter = $presenter;
    }

    public function respond(Comment $reply, mixed $headers): JsonResponse
    {
        if ($this->acceptsJson($headers)) {
            return new JsonResponse($this->presenter->present($reply), Response::HTTP_OK);
        }

        throw new \LogicException("Server does not supports accept types other than json");
    }
}
