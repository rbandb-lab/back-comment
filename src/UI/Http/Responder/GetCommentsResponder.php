<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Comment\Model\Comment as Comment;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UI\Http\Presentation\GetCommentsPresenter as GetCommentsPresenter;

class GetCommentsResponder extends ApiResponder
{
    private GetCommentsPresenter $presenter;

    public function __construct(GetCommentsPresenter $presenter, PropertyAccessorInterface $accessor)
    {
        parent::__construct($accessor);
        $this->presenter = $presenter;
    }

    public function respond(Collection $comments, mixed $headers): Response
    {
        if ($this->acceptsJson($headers)) {
            return new JsonResponse($this->presenter->present($comments), Response::HTTP_OK);
        }

        throw new \LogicException("Server does not supports accept types other than json");
    }
}
