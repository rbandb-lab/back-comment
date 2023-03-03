<?php

declare(strict_types=1);

namespace Tests\Functional\Comment;

use Application\Command\CommentCommand;
use Application\Command\ReplyCommand;
use Application\CommandHandler\CommentCommandHandler;
use Application\CommandHandler\ReplyCommandHandler;
use Comment\Model\Post;
use Comment\Model\Comment;
use Comment\Model\Dto\CommentDto;
use Comment\Model\Dto\ReplyDto;
use Comment\Repository\PostRepository;
use Comment\Repository\AuthorRepository;
use Comment\Repository\CommentRepository;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\Functional\Repository\InMemoryPostRepository;
use Tests\Functional\Repository\InMemoryAuthorRepository;
use Tests\Functional\Repository\InMemoryCommentRepository;
use UI\Http\Presentation\CommentPresenter;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class GetListOfComments extends TestCase
{
    private PostRepository $postRepository;
    private AuthorRepository $authorRepository;
    private CommentRepository $commentRepository;
    private ReplyCommandHandler $handler;
    private CommentPresenter $presenter;
    private Post $post;

    private ?Comment $comment = null;

    protected function setUp(): void
    {
        $this->postRepository = new InMemoryPostRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->commentRepository  = new InMemoryCommentRepository();
        $this->presenter = new CommentPresenter();
        $this->handler  = new ReplyCommandHandler($this->commentRepository);

        $author1 = $this->authorRepository->find('1-john');
        $author2 = $this->authorRepository->find('2-janet');
        $author3 = $this->authorRepository->find('3-jeremy');
        $author4 = $this->authorRepository->find('4-henry');

        $this->post = $this->postRepository->find('post-1');
        $comment1 = new Comment(
            commentId: Uuid::uuid4(),
            postId: $this->post->getId(),
            author: $author1,
            commentContent: "comment1"
        );
        $this->commentRepository->save($comment1);

        $comment2 = new Comment(
            commentId: Uuid::uuid4(),
            postId: $this->post->getId(),
            author: $author2,
            commentContent: "comment2"
        );
        $this->commentRepository->save($comment2);
    }

    public function testListComments()
    {
        $comments = $this->commentRepository->findByPostId('post-1');
        assertCount(2, $comments);
    }

    public function testItAddSubComments()
    {
        $comments = $this->commentRepository->findByPostId('post-1');
        $comment1 = $comments->first();
        $author = $this->authorRepository->find("4-henry");
        $author2 = $this->authorRepository->find("3-jeremy");
        $subComment1 = $this->createReplyComment($this->post, $author, new CommentContent('sub-comment-1'), $comment1->getId());
        $subSubComment1 = $this->createReplyComment($this->post, $author2, new CommentContent('sub-sub-comment-1'), $subComment1->getId());

        $results = $this->commentRepository->findAll();
        assertCount(4, $results); // 2 base comments + 2 children
    }

    private function createReplyComment(Post $post, Author $author, CommentContent $commentContent, Uuid|string $parentId)
    {
        $replyCommand = new ReplyCommand(
            id: Uuid::uuid4(),
            replyDto: new ReplyDto(
                postId: $post->getId(),
                author: $author,
                commentContent: $commentContent,
                parentId: $parentId
            )
        );
        return ($this->handler)($replyCommand);
    }
}
