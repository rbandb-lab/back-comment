<?php

declare(strict_types=1);

namespace Tests\Functional\Comment;

use Application\Command\RateCommand;
use Application\CommandHandler\RateCommandHandler;
use Comment\Model\Post;
use Comment\Model\Comment;
use Comment\Model\Dto\RatingDto;
use Comment\Repository\PostRepository;
use Comment\Repository\AuthorRepository;
use Comment\Repository\CommentRepository;
use Comment\Service\CommentIdGenerator;
use Comment\ValueObject\Author;
use PHPUnit\Framework\TestCase;
use Tests\Functional\Repository\InMemoryPostRepository;
use Tests\Functional\Repository\InMemoryAuthorRepository;
use Tests\Functional\Repository\InMemoryCommentRepository;
use UI\Http\Presentation\CommentPresenter;

use function PHPUnit\Framework\assertEquals;

class RateComment extends TestCase
{
    private PostRepository $postRepository;
    private AuthorRepository $authorRepository;
    private CommentRepository $commentRepository;
    private RateCommandHandler $handler;
    private CommentPresenter $presenter;
    private CommentIdGenerator $commentIdGenerator;
    private Post $post;

    private ?Comment $comment = null;

    protected function setUp(): void
    {
        $this->postRepository = new InMemoryPostRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->commentRepository  = new InMemoryCommentRepository();
        $this->presenter = new CommentPresenter();
        $this->handler  = new RateCommandHandler($this->commentRepository);
        $this->commentIdGenerator = new \Infra\Symfony6\Service\CommentIdGenerator();
        $author1 = $this->authorRepository->find('1-john');
        $author2 = $this->authorRepository->find('2-janet');
        $author3 = $this->authorRepository->find('3-jeremy');
        $author4 = $this->authorRepository->find('4-henry');

        $this->post = $this->postRepository->find('post-1');
        $comment1 = new Comment(
            commentId: $this->commentIdGenerator->createId(),
            postId: $this->post->getId(),
            author: $author1,
            commentContent: "comment1"
        );
        $this->commentRepository->save($comment1);

        $comment2 = new Comment(
            commentId: $this->commentIdGenerator->createId(),
            postId: $this->post->getId(),
            author: $author2,
            commentContent: "comment2"
        );
        $this->commentRepository->save($comment2);
    }
    public function testAuthorCannotRateItsComment()
    {
        $comments = $this->commentRepository->findAll();
        $comment1 = $comments->first();
        $author = $this->authorRepository->find("1-john");

        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment1->getCommentId(),
                postId: $this->post->getId(),
                author: $author,
                commentRating: 5
            )
        );

        $this->expectException('Comment\Exception\CannotRateItsOwnCommentException');
        $this->expectExceptionMessage('Author tried to rate its own comment');
        ($this->handler)($rateCommand);
    }

    public function testRatingStepIsZeroPointFive()
    {
        $comments = $this->commentRepository->findAll();
        $comment1 = $comments->first();
        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment1->getCommentId(),
                postId: $this->post->getId(),
                author: $this->authorRepository->find("4-henry"),
                commentRating: 0.5
            )
        );
        $comment = ($this->handler)($rateCommand);
        assertEquals(0.5, $comment->getRating());
    }

    public function testRatingStepsByZeroPointFive()
    {
        $comments = $this->commentRepository->findAll();
        $comment1 = $comments->first();
        $author = $this->authorRepository->find("2-janet");
        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment1->getCommentId(),
                postId: $this->post->getId(),
                author: $author,
                commentRating: 0.1
            )
        );
        $this->expectException('Comment\Exception\InvalidCommentRatingException');
        $this->expectExceptionMessage('decimal part must be 0 or 0.5');
        ($this->handler)($rateCommand);
    }
    public function testCannotRateSameCommentTwice()
    {
        $comments = $this->commentRepository->findAll();
        $comment1 = $comments->first();
        $author = $this->authorRepository->find("2-janet");
        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment1->getCommentId(),
                postId: $this->post->getId(),
                author: $author,
                commentRating: 0
            )
        );
        $comment = ($this->handler)($rateCommand);

        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment1->getCommentId(),
                postId: $this->post->getId(),
                author: $author,
                commentRating: 0
            )
        );
        $this->expectException('Comment\Exception\CannotRateCommentTwiceException');
        ($this->handler)($rateCommand);
    }

    private function addRating(Post $post, Author $author, Comment $comment)
    {
        $rateCommand = new RateCommand(
            id: \Ramsey\Uuid\Uuid::uuid4()->toString(),
            ratingDto: new RatingDto(
                commentId: $comment->getCommentId(),
                postId: $post->getId(),
                author: $author,
                commentRating: 4.5
            )
        );
        return ($this->handler)($rateCommand);
    }
}
