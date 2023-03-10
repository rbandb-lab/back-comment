<?php

declare(strict_types=1);

namespace Tests\Functional\Repository;

use Comment\Model\Article;
use Comment\Repository\ArticleRepository;
use Comment\ValueObject\ArticleContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryArticleRepository implements ArticleRepository
{
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $article1 = new Article(id: 'article-1', articleContent: new ArticleContent('Chuck Norris can slam a revolving door. Chuck Norris can have his cake and eat it, too. Chuck Norris is currently suing NBC, claiming Law and Order are trademarked names for his left and right legs Chuck Norris can slice meat so thin is only has one side, When Chuck Norris sends in his taxes, he sends blank forms and includes only a picture of himself, crouched and ready to attack. Chuck Norris has not had to pay taxes, ever Chuck Norris is the only man to ever defeat a brick wall in a game of tennis. Chuck Norris looks gift horses in the mouth. Chuck Norris doesn’t wear a watch. HE decides what time it is, The Great Wall of China was originally created to keep Chuck Norris out. It failed miserably.'));
        $article2 = new Article(id: 'article-2', articleContent: new ArticleContent('There is no chin behind Chuck Norris’ beard. There is only another fist. If you spell Chuck Norris in Scrabble, you win. Forever If you ask Chuck Norris what time it is, he always says, “Two seconds ’til.” After you ask, “Two seconds ’til what?” he roundhouse kicks you in the face, Chuck Norris can speak a language inside of another language, A Handicapped parking sign does not signify that this spot is for handicapped people. It is actually in fact a warning, that the spot belongs to Chuck Norris and that you will be handicapped if you park there.'));
        $this->articles->add($article1);
        $this->articles->add($article2);
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function find(string $id)
    {
        foreach ($this->articles->getIterator() as $article) {
            if ($article->id === $id) {
                return $article;
            }
        }

        return null;
    }
}
