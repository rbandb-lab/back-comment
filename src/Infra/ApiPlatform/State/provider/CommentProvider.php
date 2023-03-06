<?php

declare(strict_types=1);

namespace Infra\ApiPlatform\State\provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Application\Query\ByPostQuery;
use Comment\Model\Comment;
use Ramsey\Uuid\Uuid;
use SharedKernel\Application\Query\QueryBusInterface;

class CommentProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var string $id */
        $id = $uriVariables['id'];

        /** @var Comment|null $model */
        return $this->queryBus->dispatch(new ByPostQuery('1'));
    }
}
