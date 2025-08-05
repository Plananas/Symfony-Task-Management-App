<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Carbon\CarbonImmutable;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $isDone = null;

    #[ORM\Column]
    private ?CarbonImmutable $created_at = null;

    #[ORM\Column]
    private ?CarbonImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?CarbonImmutable $deleted_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getCreatedAt(): ?CarbonImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(CarbonImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?CarbonImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(CarbonImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?CarbonImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?CarbonImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
