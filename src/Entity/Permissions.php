<?php

namespace App\Entity;

use App\Repository\PermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionsRepository::class)]
class Permissions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'permissions')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'permissions')]
    private ?Promo $promo = null;

    #[ORM\ManyToOne(inversedBy: 'permissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Folder $folder = null;

    #[ORM\Column]
    private ?bool $isReadable = null;

    #[ORM\Column]
    private ?bool $isEditable = null;

    #[ORM\Column]
    private ?bool $isDeletable = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isReadable = false;
        $this->isEditable = false;
        $this->isDeletable = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): static
    {
        $this->promo = $promo;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): static
    {
        $this->folder = $folder;

        return $this;
    }

    public function isIsReadable(): ?bool
    {
        return $this->isReadable;
    }

    public function setIsReadable(bool $isReadable): static
    {
        $this->isReadable = $isReadable;

        return $this;
    }

    public function isIsEditable(): ?bool
    {
        return $this->isEditable;
    }

    public function setIsEditable(bool $isEditable): static
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    public function isIsDeletable(): ?bool
    {
        return $this->isDeletable;
    }

    public function setIsDeletable(bool $isDeletable): static
    {
        $this->isDeletable = $isDeletable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
