<?php

declare(strict_types=1);

namespace Tests\Functional\Comment;

use Comment\Exception\InvalidCommentContentException;
use Comment\Model\Post;
use Comment\Model\Comment;
use Comment\Model\Dto\CommentDto;
use Application\Command\CommentCommand;
use Application\CommandHandler\CommentCommandHandler;
use Comment\Repository\PostRepository;
use Comment\Repository\AuthorRepository;
use Comment\Repository\CommentRepository;
use Comment\Service\CommentIdGenerator;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Functional\Repository\InMemoryPostRepository;
use Tests\Functional\Repository\InMemoryAuthorRepository;
use Tests\Functional\Repository\InMemoryCommentRepository;
use UI\Http\Presentation\CommentPresenter;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class CreateCommentTest extends KernelTestCase
{
    private PostRepository $postRepository;
    private AuthorRepository $authorRepository;
    private CommentRepository $commentRepository;

    private CommentCommandHandler $handler;

    private CommentPresenter $presenter;
    private CommentIdGenerator $idGenerator;

    private ?Comment $comment = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = $this->getContainer();
        $this->postRepository = new InMemoryPostRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->commentRepository  = new InMemoryCommentRepository();
        $this->presenter = new CommentPresenter();
        $this->handler  = new CommentCommandHandler($this->commentRepository, $this->presenter);
        $this->idGenerator = $container->get("Infra\Symfony6\Service\CommentIdGenerator");
    }

    public function testCommentContentTooLong()
    {
        $post = $this->postRepository->getPosts()->first();
        $author = $this->authorRepository->find("1-john");
        $this->expectException(InvalidCommentContentException::class);
        $commentContent = new CommentContent('Egestas maecenas pharetra convallis posuere morbi leo. Massa enim nec dui nunc mattis enim ut tellus elementum. Sapien pellentesque habitant morbi tristique senectus et netus. Et malesuada fames ac turpis egestas sed tempus. Netus et malesuada fames ac turpis egestas sed. Sagittis purus sit amet volutpat consequat. Iaculis nunc sed augue lacus viverra vitae congue eu. Curabitur vitae nunc sed velit dignissim sodales ut. Nam at lectus urna duis convallis. Cras ornare arcu dui vivamus. Auctor eu augue ut lectus arcu bibendum at varius vel. Ultrices tincidunt arcu non sodales neque sodales ut. Egestas integer eget aliquet nibh praesent. Arcu cursus vitae congue mauris rhoncus aenean vel elit scelerisque. Justo nec ultrices dui sapien eget mi proin sed libero. Donec enim diam vulputate ut pharetra. Sed risus pretium quam vulputate dignissim. Nunc non blandit massa enim nec dui. In hac habitasse platea dictumst');
    }

    public function testItCreatesComment()
    {
        $post = $this->postRepository->getPosts()->first();
        $author = $this->authorRepository->find("1-john");
        $commentContent = new CommentContent('Egestas maecenas pharetra convallis posuere morbi leo.');
        $comment = $this->createBaseComment($post, $author, $commentContent);
        self::assertInstanceOf(Comment::class, $comment);
        self::assertInstanceOf(Comment::class, $this->commentRepository->find($comment->getCommentId()));
    }


    public function testItCreatesCommentsOnSameLevel()
    {
        $post = $this->postRepository->getPosts()->first();

        $author = $this->authorRepository->find("1-john");
        $commentContent = new CommentContent('Egestas maecenas pharetra convallis posuere morbi leo.');
        $comments[] = $this->createBaseComment($post, $author, $commentContent);

        $author = $this->authorRepository->find("2-janet");
        $commentContent = new CommentContent('Ultrices vitae auctor eu augue ut lectus.');
        $comments[] = $this->createBaseComment($post, $author, $commentContent);

        $author = $this->authorRepository->find("3-jeremy");
        $commentContent = new CommentContent(' fermentum et. Cras pulvinar mattis nunc sed. Arcu.');
        $comments[] = $this->createBaseComment($post, $author, $commentContent);

        $commentsList = $this->commentRepository->findAll();
        assertCount(3, $commentsList);

        $count = 0;
        foreach ($commentsList->getIterator() as $member) {
            /** @var Comment $member */
            $comment = $comments[$count];
            assertEquals($member->getCommentId(), $comment->getCommentId());
            assertEquals($member->getAuthor(), $comment->getAuthor());
            assertEquals($member->getCreatedAt(), $comment->getCreatedAt());
            assertEquals($member->getPostId(), $comment->getPostId());
            assertEquals($member->getRating(), $comment->getRating());
            assertEquals($member->getCommentContent(), $comment->getCommentContent());
            $count++;
        }
    }

    public function testItCreatesCommentWithSubComments()
    {
        $post = $this->postRepository->getPosts()->first();
        $author = $this->authorRepository->find("1-john");
        $commentContent = new CommentContent('Egestas maecenas pharetra convallis posuere morbi leo.');
        $comment = $this->createBaseComment($post, $author, $commentContent);
        self::assertInstanceOf(Comment::class, $comment);
    }

    private function createBaseComment(Post $post, Author $author, CommentContent $commentContent)
    {
        $command = new CommentCommand(
            commentId: $id = $this->idGenerator->createId(Uuid::uuid4()),
            commentDto: new CommentDto(
                postId: $post->getId(),
                author: $author,
                commentContent: $commentContent,
                parentId: null
            )
        );
        return ($this->handler)($command);
    }
}
