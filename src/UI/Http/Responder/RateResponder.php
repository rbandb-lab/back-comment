<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Comment\Model\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UI\Http\Presentation\RatePresenter;

final class RateResponder extends ApiResponder
{
    private RatePresenter $presenter;
    private PropertyAccessorInterface $accessor;

    public function __construct(RatePresenter $presenter, PropertyAccessorInterface $accessor)
    {
        parent::__construct($accessor);
        $this->presenter = $presenter;
    }

    public function respond(Comment $ratedComment, mixed $headers): JsonResponse
    {
        if ($this->acceptsJson($headers)) {
            return new JsonResponse($this->presenter->present($ratedComment), Response::HTTP_OK);
        }

        throw new \LogicException("Server does not supports accept types other than json");
    }
}
