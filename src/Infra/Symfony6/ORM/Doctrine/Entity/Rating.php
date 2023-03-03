<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity]
class Rating
{
    #[ORM\Id()]
    #[ORM\Column(name: 'id', type: UuidType::NAME)]
    private UuidInterface $id;

    #[ORM\Column(type: 'integer')]
    private int $value;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    public function __construct(UuidInterface $id, int $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
