<?php

declare(strict_types=1);

namespace Tests\Functional\Repository;

use Comment\Repository\AuthorRepository;
use Comment\ValueObject\Author;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryAuthorRepository implements AuthorRepository
{
    private Collection $authors;

    public function __construct()
    {
        $john = new Author("1-john", "John Doe");
        $janet = new Author("2-janet", "Janet Doe");
        $jeremy = new Author("3-jeremy", "Jeremy Doe");
        $henry = new Author("4-henry", "Henry Doe");
        $this->authors = new ArrayCollection();
        $this->authors->add($john);
        $this->authors->add($janet);
        $this->authors->add($jeremy);
        $this->authors->add($henry);
    }

    public function getAuthors(): \Iterator
    {
        return $this->authors->getIterator();
    }

    public function find(string $id)
    {
        foreach ($this->authors->getIterator() as $author){
            if($author->id === $id){
                return $author;
            }
        }
        return null;
    }
}
