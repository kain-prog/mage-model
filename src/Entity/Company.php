<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\Table(name: 'company')]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: "company")]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Template::class, mappedBy: "company")]
    private Collection $templates;

    #[ORM\OneToMany(targetEntity: Model::class, mappedBy: "company")]
    private Collection $models;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->templates = new ArrayCollection();
        $this->models = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    public function setUsers(ArrayCollection $users): void
    {
        $this->users = $users;
    }

    public function getTemplates(): ArrayCollection
    {
        return $this->templates;
    }

    public function setTemplates(ArrayCollection $templates): void
    {
        $this->templates = $templates;
    }

    public function getModels(): ArrayCollection
    {
        return $this->models;
    }

    public function setModels(ArrayCollection $models): void
    {
        $this->models = $models;
    }
}
