<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="roles")
     */
    private $userWithRole;

    public function __construct()
    {
        $this->userWithRole = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserWithRole(): Collection
    {
        return $this->userWithRole;
    }

    public function addUserWithRole(User $userWithRole): self
    {
        if (!$this->userWithRole->contains($userWithRole)) {
            $this->userWithRole[] = $userWithRole;
        }

        return $this;
    }

    public function removeUserWithRole(User $userWithRole): self
    {
        $this->userWithRole->removeElement($userWithRole);

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
