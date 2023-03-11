<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Comment\Model\Comment;
use SharedKernel\Responder\HttpResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UI\Http\Presentation\CommentPresenter;

final class CommentResponder extends ApiResponder implements HttpResponder
{
    private CommentPresenter $presenter;

    public function __construct(CommentPresenter $presenter, PropertyAccessorInterface $accessor)
    {
        parent::__construct($accessor);
        $this->presenter = $presenter;
    }

    public function respond(Comment $comment, mixed $headers): Response
    {
        if ($this->acceptsJson($headers)) {
            return new JsonResponse($this->presenter->present($comment), Response::HTTP_OK);
        }

        throw new \LogicException('Server does not supports accept types other than json');
    }
}
