<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Entity;

use Comment\Entity\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;


#[ORM\Table(name: "author")]
class User implements UserInterface
{
    #[ORM\Id()]
    #[ORM\Column(name: 'id', type: UuidType::NAME)]
    private UuidInterface $id;

    #[ORM\Column()]
    private string $username;

    public function __construct(UuidInterface $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
