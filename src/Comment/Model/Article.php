<?php

declare(strict_types=1);

namespace Comment\Model;

use Comment\Exception\CannotDeleteCommentWithChidlrendException;
use Comment\ValueObject\ArticleContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

class Article
{
    private string $id;
    private ArticleContent $articleContent;

    private Collection $comments;


    public function __construct(string $id, ArticleContent $articleContent)
    {
        $this->id = $id;
        $this->articleContent = $articleContent;
        $this->comments = new ArrayCollection();
    }

    public function addComment(Comment $drafComment): void
    {
        $this->comments->add($drafComment);
    }

    public function removeComment(Comment $drafComment): void
    {
        if($drafComment->hasChildren() === false){
            $this->comments->removeElement($drafComment);
            return;
        }

        throw new CannotDeleteCommentWithChidlrendException($drafComment->getId());
    }

    public function getArticleContent(): ArticleContent
    {
        return $this->articleContent;
    }

    /**
     * @param ArticleContent $articleContent
     */
    public function setArticleContent(ArticleContent $articleContent): void
    {
        $this->articleContent = $articleContent;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
