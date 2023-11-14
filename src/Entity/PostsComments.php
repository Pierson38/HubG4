<?php

namespace App\Entity;

use App\Repository\PostsCommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsCommentsRepository::class)]
class PostsComments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'postsComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'postComment', targetEntity: PostsReport::class)]
    private Collection $postsReports;

    #[ORM\ManyToOne(inversedBy: 'postsComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Posts $post = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->postsReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
     * @return Collection<int, PostsReport>
     */
    public function getPostsReports(): Collection
    {
        return $this->postsReports;
    }

    public function addPostsReport(PostsReport $postsReport): static
    {
        if (!$this->postsReports->contains($postsReport)) {
            $this->postsReports->add($postsReport);
            $postsReport->setPostComment($this);
        }

        return $this;
    }

    public function removePostsReport(PostsReport $postsReport): static
    {
        if ($this->postsReports->removeElement($postsReport)) {
            // set the owning side to null (unless already changed)
            if ($postsReport->getPostComment() === $this) {
                $postsReport->setPostComment(null);
            }
        }

        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): static
    {
        $this->post = $post;

        return $this;
    }
}
