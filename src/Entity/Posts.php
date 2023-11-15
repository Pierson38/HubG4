<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostsImages::class, orphanRemoval: true)]
    private Collection $postsImages;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?PostsCategories $category = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostsReport::class)]
    private Collection $postsReports;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostsComments::class, orphanRemoval: true)]
    private Collection $postsComments;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->postsImages = new ArrayCollection();
        $this->postsReports = new ArrayCollection();
        $this->postsComments = new ArrayCollection();
    }

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
     * @return Collection<int, PostsImages>
     */
    public function getPostsImages(): Collection
    {
        return $this->postsImages;
    }

    public function addPostsImage(PostsImages $postsImage): static
    {
        if (!$this->postsImages->contains($postsImage)) {
            $this->postsImages->add($postsImage);
            $postsImage->setPost($this);
        }

        return $this;
    }

    public function removePostsImage(PostsImages $postsImage): static
    {
        if ($this->postsImages->removeElement($postsImage)) {
            // set the owning side to null (unless already changed)
            if ($postsImage->getPost() === $this) {
                $postsImage->setPost(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?PostsCategories
    {
        return $this->category;
    }

    public function setCategory(?PostsCategories $category): static
    {
        $this->category = $category;

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
            $postsReport->setPost($this);
        }

        return $this;
    }

    public function removePostsReport(PostsReport $postsReport): static
    {
        if ($this->postsReports->removeElement($postsReport)) {
            // set the owning side to null (unless already changed)
            if ($postsReport->getPost() === $this) {
                $postsReport->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostsComments>
     */
    public function getPostsComments(): Collection
    {
        return $this->postsComments;
    }

    public function addPostsComment(PostsComments $postsComment): static
    {
        if (!$this->postsComments->contains($postsComment)) {
            $this->postsComments->add($postsComment);
            $postsComment->setPost($this);
        }

        return $this;
    }

    public function removePostsComment(PostsComments $postsComment): static
    {
        if ($this->postsComments->removeElement($postsComment)) {
            // set the owning side to null (unless already changed)
            if ($postsComment->getPost() === $this) {
                $postsComment->setPost(null);
            }
        }

        return $this;
    }
}
