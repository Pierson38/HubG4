<?php

namespace App\Entity;

use App\Repository\CarpoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarpoolRepository::class)]
class Carpool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("carpoolEvent")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("carpoolEvent")]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $fromLocation = null;

    #[ORM\Column(length: 255)]
    #[Groups("carpoolEvent")]
    private ?string $toLocation = null;

    #[ORM\Column]
    private ?int $places = null;

    #[ORM\ManyToOne(inversedBy: 'carpools')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'carpool', targetEntity: CarpoolMembers::class, orphanRemoval: true)]
    private Collection $carpoolMembers;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fromLat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fromLong = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $toLat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $toLong = null;

    private ?int $membersCount = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();            
        $this->carpoolMembers = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFromLocation(): ?string
    {
        return $this->fromLocation;
    }

    public function setFromLocation(string $fromLocation): static
    {
        $this->fromLocation = $fromLocation;

        return $this;
    }

    public function getToLocation(): ?string
    {
        return $this->toLocation;
    }

    public function setToLocation(string $toLocation): static
    {
        $this->toLocation = $toLocation;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): static
    {
        $this->places = $places;

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
     * @return Collection<int, CarpoolMembers>
     */
    public function getCarpoolMembers(): Collection
    {
        return $this->carpoolMembers;
    }

    public function addCarpoolMember(CarpoolMembers $carpoolMember): static
    {
        if (!$this->carpoolMembers->contains($carpoolMember)) {
            $this->carpoolMembers->add($carpoolMember);
            $carpoolMember->setCarpool($this);
        }

        return $this;
    }

    public function removeCarpoolMember(CarpoolMembers $carpoolMember): static
    {
        if ($this->carpoolMembers->removeElement($carpoolMember)) {
            // set the owning side to null (unless already changed)
            if ($carpoolMember->getCarpool() === $this) {
                $carpoolMember->setCarpool(null);
            }
        }

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFromLat(): ?string
    {
        return $this->fromLat;
    }

    public function setFromLat(?string $fromLat): static
    {
        $this->fromLat = $fromLat;

        return $this;
    }

    public function getFromLong(): ?string
    {
        return $this->fromLong;
    }

    public function setFromLong(?string $fromLong): static
    {
        $this->fromLong = $fromLong;

        return $this;
    }

    public function getToLat(): ?string
    {
        return $this->toLat;
    }

    public function setToLat(?string $toLat): static
    {
        $this->toLat = $toLat;

        return $this;
    }

    public function getToLong(): ?string
    {
        return $this->toLong;
    }

    public function setToLong(?string $toLong): static
    {
        $this->toLong = $toLong;

        return $this;
    }

    public function getMembersCount(): ?int
    {
        return $this->membersCount;
    }

    public function setMembersCount(?int $membersCount): static
    {
        $this->membersCount = $membersCount;

        return $this;
    }
}
