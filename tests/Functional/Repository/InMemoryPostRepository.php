<?php

declare(strict_types=1);

namespace Tests\Functional\Repository;

use Comment\Model\Post;
use Comment\Repository\PostRepository;
use Comment\ValueObject\PostContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InMemoryPostRepository implements PostRepository
{
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $post1 = new Post(id:"post-1");
        $post2 = new Post(id:"post-2");
        $this->posts->add($post1);
        $this->posts->add($post2);
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function find(string $id)
    {
        foreach ($this->posts->getIterator() as $post) {
            if ($post->getId() === $id) {
                return $post;
            }
        }
        return null;
    }
}
