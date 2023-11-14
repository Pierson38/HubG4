<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversations $conversation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: MessagesImages::class, orphanRemoval: true)]
    private Collection $messagesImages;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?bool $isRead = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->messagesImages = new ArrayCollection();
        $this->isRead = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?Conversations
    {
        return $this->conversation;
    }

    public function setConversation(?Conversations $conversation): static
    {
        $this->conversation = $conversation;

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

    /**
     * @return Collection<int, MessagesImages>
     */
    public function getMessagesImages(): Collection
    {
        return $this->messagesImages;
    }

    public function addMessagesImage(MessagesImages $messagesImage): static
    {
        if (!$this->messagesImages->contains($messagesImage)) {
            $this->messagesImages->add($messagesImage);
            $messagesImage->setMessage($this);
        }

        return $this;
    }

    public function removeMessagesImage(MessagesImages $messagesImage): static
    {
        if ($this->messagesImages->removeElement($messagesImage)) {
            // set the owning side to null (unless already changed)
            if ($messagesImage->getMessage() === $this) {
                $messagesImage->setMessage(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
