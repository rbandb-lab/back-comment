<?php

declare(strict_types=1);

namespace Infra\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Application\Command\CommentCommand;
use Comment\Model\Dto\CommentDto;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use SharedKernel\Application\Command\CommandBusInterface;

final class CommentProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $command = new CommentCommand(
            commentId: $data->id,
            commentDto: new CommentDto(
                postId: $data->postId,
                author: new Author($data->id, $data->username),
                commentContent: new CommentContent($data->commentContent),
                parentId: $data->parentId
            )
        );

        $this->commandBus->dispatch($command);
    }
}
