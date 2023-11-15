<?php

namespace App\Entity;

use App\Repository\PostsReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsReportRepository::class)]
class PostsReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\ManyToOne(inversedBy: 'postsReports')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $reportedBy = null;

    #[ORM\ManyToOne(inversedBy: 'postsReports')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Posts $post = null;

    #[ORM\ManyToOne(inversedBy: 'postsReports')]
    private ?PostsComments $postComment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getReportedBy(): ?User
    {
        return $this->reportedBy;
    }

    public function setReportedBy(?User $reportedBy): static
    {
        $this->reportedBy = $reportedBy;

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

    public function getPostComment(): ?PostsComments
    {
        return $this->postComment;
    }

    public function setPostComment(?PostsComments $postComment): static
    {
        $this->postComment = $postComment;

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
