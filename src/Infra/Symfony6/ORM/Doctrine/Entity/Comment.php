<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Entity;

use ApiPlatform\Metadata\ApiResource;
use Comment\Entity\CommentInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;


#[ApiResource]
class Comment implements CommentInterface
{
    #[ORM\Id()]
    #[ORM\Column(name: 'id', type: UuidType::NAME)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Comment::class)]
    private Collection $children;

    #[ORM\Column()]
    private string $postId;

    #[ORM\Column()]
    private string $commentContent;

    #[ORM\Column(type: 'integer')]
    private int $createdAt;

    #[ORM\JoinTable(name: 'comments_ratings')]
    #[ORM\JoinColumn(name: 'comment_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'rating_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: Rating::class, cascade: ['all'])]
    private Collection $ratings;

    /** Many Categories have One Category. */
    #[ORM\ManyToOne(targetEntity: Comment::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private Comment|null $parent = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    public function __construct(UuidInterface $id, string $postId, string $commentContent, ?Comment $parent = null)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->commentContent = $commentContent;
        $this->parent = $parent;
        $this->children = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function addChild(Comment $comment): void
    {
        if (!$this->children->contains($comment)) {
            $this->children->add($comment);
        }
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }


    public function addRating(Rating $rating): void
    {
        $this->ratings->add($rating);
    }

    public function removeRating(Rating $rating): void
    {
        $this->ratings->removeElement($rating);
    }

    public function getCommentContent(): string
    {
        return $this->commentContent;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }


    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function setPostId(string $postId): void
    {
        $this->postId = $postId;
    }

    public function getParent(): ?Comment
    {
        return $this->parent;
    }


    public function setParent(?Comment $parent): void
    {
        $this->parent = $parent;
    }
}
