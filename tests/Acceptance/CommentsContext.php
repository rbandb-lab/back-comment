<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Comment\Model\Article;
use Comment\Model\Comment;
use Comment\ValueObject\ArticleContent;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentRating;
use Symfony\Component\String\UnicodeString;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class CommentsContext extends AuthContext implements Context
{
    private ?Article $article = null;
    private ?\Exception $exception = null;
    private int $count = 0;

    /**
     * @Given the author :arg1 identified by id :arg2 posted an article with id :arg3
     */
    public function theAuthorIdentifiedByIdPostedAnArticleWithId($arg1, $arg2, $arg3)
    {
        $author = $this->author->getId() === $arg2 ? $this->author : null;
        $article = new Article(id: $arg3, articleContent: new ArticleContent(''));

        $this->article = $article;
    }

    /**
     * @Given the article has the following text content:
     */
    public function theArticleHasTheFollowingTextContent(PyStringNode $string)
    {
        $this->article->setArticleContent(new ArticleContent(implode(PHP_EOL, $string->getStrings())));
    }

    /**
     * @Given the author :arg1 sends a comment to the article :arg2 with payload:
     */
    public function theAuthorSendsACommentToTheArticleWithPayload2($arg1, $arg2, PyStringNode $string)
    {
        if ($this->article->getId() === $arg2) {
            $commentAuthor = $arg1;
            $payload = implode(PHP_EOL, $string->getStrings());
            try {
                $comment = new Comment(
                    id: 'comment-0',
                    articleId: $arg2,
                    author: new Author('2'.$arg1, $arg1),
                    commentContent: $payload
                );
                $this->article->addComment($comment);
                ++$this->count;
            } catch (\Exception $exception) {
                $this->exception = $exception;
            }
        }
    }

    /**
     * @Given the article comment has no child comments
     */
    public function theArticleCommentHasNoChildComments()
    {
        /** @var Comment $comment */
        foreach ($this->article->getComments()->getIterator() as $comment) {
            assertTrue($comment->hasChildren() === false);
        }
    }

    /**
     * @Then a new Comment is created
     */
    public function aNewCommentIsCreated()
    {
        assertCount($this->count, $this->article->getComments());
    }

    /**
     * @Then an :arg1 exception is thrown
     */
    public function anExceptionIsThrownWithMessageText($arg1)
    {
        assertInstanceOf($arg1, $this->exception);
    }

    /**
     * @Then the message text should contain the following keywords:
     */
    public function theMessageTextShouldContainTheFollowingKeywords(PyStringNode $string)
    {
        $keywords = explode('|', $string->getRaw());
        $exceptionMessage = new UnicodeString($this->exception->getMessage());
        foreach ($keywords as $keyword) {
            $toFind = new UnicodeString($keyword);
            if ($toFind->length() > 0) {
                assertTrue($exceptionMessage->containsAny((string) $toFind));
            }
        }
    }

    /**
     * @Then the Comment is added to the article :arg1
     */
    public function theCommentIsAddedToTheArticle($arg1)
    {
        assertEquals($this->article->getId(), $arg1);
    }

    /**
     * @Given the author :arg1 with id :arg2 sends a comment to the article :arg3 with payload
     */
    public function theAuthorWithIdSendsACommentToTheArticleWithPayload($arg1, $arg2, $arg3, PyStringNode $string)
    {
        if ($this->article->getId() === $arg3) {
            $author = new Author($arg2, $arg1);
            $payload = trim(implode('', $string->getStrings()));
            $comment = new Comment(
                id: 'comment-'.$this->count,
                articleId: $this->article->getId(),
                author: $author,
                commentContent: $payload
            );
            $this->article->addComment($comment);
            ++$this->count;

            return;
        }
        throw new \InvalidArgumentException('Article not found');
    }

    /**
     * @Then the article :arg1 should have :arg2 comments
     */
    public function theArticleShouldHaveComments($arg1, $arg2)
    {
        assertTrue($this->article->getId() === $arg1);
        assertTrue($this->article->getComments()->count() === (int) $arg2);
    }

    /**
     * @Then the author of comment number :arg2 is :arg1
     */
    public function theAuthorOfCommentNumberIs($arg1, $arg2)
    {
        $comments = $this->article->getComments();
        assertGreaterThan(0, $comments->count());
        $comment = $comments->get((int) $arg2);
        assertInstanceOf(Comment::class, $comment);
        assertTrue($comment->getAuthor()->getUsername() === $arg1);
    }

    /**
     * @Then the comment number :arg2 should have :arg1 content
     */
    public function theCommentNumberShouldHaveContent($arg1, $arg2)
    {
        $comments = $this->article->getComments();
        assertGreaterThan(0, $comments->count());
        $comment = $comments->get((int) $arg2);
        assertTrue($comment->getCommentContent()->getContent() === $arg1);
    }

    /**
     * @Then the comment number :arg1 has no child comments
     */
    public function theCommentNumberHasNoChildComments($arg1)
    {
        $comments = $this->article->getComments();
        assertGreaterThan(0, $comments->count());
        $comment = $comments->get((int) $arg1);
        assertCount(0, $comment->getSubComments());
    }

    /**
     * @Given the author :arg1 with id :arg2 sends a reply to the comment number :arg3 with payload
     */
    public function theAuthorWithIdSendsAReplyToTheCommentNumberWithPayload($arg1, $arg2, $arg3, PyStringNode $string)
    {
        $comments = $this->article->getComments();
        $comment = $comments->get((int) $arg1);

        $author = new Author($arg2, $arg1);
        $payload = trim(implode('', $string->getStrings()));
        $reply = new Comment(
            id: 'comment-'.$this->count,
            articleId: $this->article->getId(),
            author: $author, commentContent: $payload
        );
        $comment->addSubComment($comment);
    }

    /**
     * @Then the comment number :arg2 has :arg1 child comments
     */
    public function theCommentNumberHasChildComments($arg1, $arg2)
    {
        $comments = $this->article->getComments();
        $comment = $comments->get((int) $arg2);
        assertCount((int) $arg1, $comment->getSubComments());
    }

    /**
     * @Given the author :arg1 with id :arg2 rates the comment number :arg3 with a :arg4 rating
     */
    public function theAuthorHendryWithIdRatesTheCommentNumberWithARating($arg1, $arg2, $arg3, $arg4)
    {
        $comments = $this->article->getComments();
        $comment = $comments->get((int) $arg3);
        $author = new Author($arg2, $arg1);
        $rating = new CommentRating($author, (float) $arg4);
        $comment->addRating($rating);
    }

    /**
     * @Then the comment number :arg1 should a rating of :arg2
     */
    public function theCommentNumberShouldARatingOf($arg1, $arg2)
    {
        $comments = $this->article->getComments();
        $comment = $comments->get((int) $arg1);
        assertEquals((float) $arg2, $comment->getRating());
    }
}
