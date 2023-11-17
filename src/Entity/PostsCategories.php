<?php

namespace App\Entity;

use App\Repository\PostsCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsCategoriesRepository::class)]
class PostsCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'postsChildren')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?self $categoryParent = null;

    #[ORM\OneToMany(mappedBy: 'categoryParent', targetEntity: self::class)]
    private Collection $postsChildren;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Posts::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->postsChildren = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategoryParent(): ?self
    {
        return $this->categoryParent;
    }

    public function setCategoryParent(?self $categoryParent): static
    {
        $this->categoryParent = $categoryParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPostsChildren(): Collection
    {
        return $this->postsChildren;
    }

    public function addPostsChild(self $postsChild): static
    {
        if (!$this->postsChildren->contains($postsChild)) {
            $this->postsChildren->add($postsChild);
            $postsChild->setCategoryParent($this);
        }

        return $this;
    }

    public function removePostsChild(self $postsChild): static
    {
        if ($this->postsChildren->removeElement($postsChild)) {
            // set the owning side to null (unless already changed)
            if ($postsChild->getCategoryParent() === $this) {
                $postsChild->setCategoryParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
