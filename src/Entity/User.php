<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Promo $promo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    private ?Campus $campus = null;

    #[ORM\OneToMany(mappedBy: 'professor', targetEntity: Courses::class)]
    private Collection $coursesProfessor;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Courses::class)]
    private Collection $coursesAdmin;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: PostsComments::class, orphanRemoval: true)]
    private Collection $postsComments;

    #[ORM\OneToMany(mappedBy: 'reportedBy', targetEntity: PostsReport::class, orphanRemoval: true)]
    private Collection $postsReports;

    #[ORM\OneToMany(mappedBy: 'fromUser', targetEntity: Conversations::class, orphanRemoval: true)]
    private Collection $conversationsFrom;

    #[ORM\OneToMany(mappedBy: 'toUser', targetEntity: Conversations::class, orphanRemoval: true)]
    private Collection $conversationsTo;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Events::class, orphanRemoval: true)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notifications::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Folder::class)]
    private Collection $folders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Permissions::class)]
    private Collection $permissions;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Carpool::class, orphanRemoval: true)]
    private Collection $carpools;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CarpoolMembers::class, orphanRemoval: true)]
    private Collection $carpoolMembers;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Lbc::class, orphanRemoval: true)]
    private Collection $lbcs;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Files::class)]
    private Collection $files;

    public function __construct()
    {
        $this->coursesProfessor = new ArrayCollection();
        $this->coursesAdmin = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->postsComments = new ArrayCollection();
        $this->postsReports = new ArrayCollection();
        $this->conversationsFrom = new ArrayCollection();
        $this->conversationsTo = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->folders = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->carpools = new ArrayCollection();
        $this->carpoolMembers = new ArrayCollection();
        $this->lbcs = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCoursesProfessor(): Collection
    {
        return $this->coursesProfessor;
    }

    public function addCoursesProfessor(Courses $coursesProfessor): static
    {
        if (!$this->coursesProfessor->contains($coursesProfessor)) {
            $this->coursesProfessor->add($coursesProfessor);
            $coursesProfessor->setProfessor($this);
        }

        return $this;
    }

    public function removeCoursesProfessor(Courses $coursesProfessor): static
    {
        if ($this->coursesProfessor->removeElement($coursesProfessor)) {
            // set the owning side to null (unless already changed)
            if ($coursesProfessor->getProfessor() === $this) {
                $coursesProfessor->setProfessor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCoursesAdmin(): Collection
    {
        return $this->coursesAdmin;
    }

    public function addCoursesAdmin(Courses $coursesAdmin): static
    {
        if (!$this->coursesAdmin->contains($coursesAdmin)) {
            $this->coursesAdmin->add($coursesAdmin);
            $coursesAdmin->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCoursesAdmin(Courses $coursesAdmin): static
    {
        if ($this->coursesAdmin->removeElement($coursesAdmin)) {
            // set the owning side to null (unless already changed)
            if ($coursesAdmin->getCreatedBy() === $this) {
                $coursesAdmin->setCreatedBy(null);
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
            $postsComment->setCreatedBy($this);
        }

        return $this;
    }

    public function removePostsComment(PostsComments $postsComment): static
    {
        if ($this->postsComments->removeElement($postsComment)) {
            // set the owning side to null (unless already changed)
            if ($postsComment->getCreatedBy() === $this) {
                $postsComment->setCreatedBy(null);
            }
        }

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
            $postsReport->setReportedBy($this);
        }

        return $this;
    }

    public function removePostsReport(PostsReport $postsReport): static
    {
        if ($this->postsReports->removeElement($postsReport)) {
            // set the owning side to null (unless already changed)
            if ($postsReport->getReportedBy() === $this) {
                $postsReport->setReportedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getConversationsFrom(): Collection
    {
        return $this->conversationsFrom;
    }

    public function addConversationsFrom(Conversations $conversationsFrom): static
    {
        if (!$this->conversationsFrom->contains($conversationsFrom)) {
            $this->conversationsFrom->add($conversationsFrom);
            $conversationsFrom->setFromUser($this);
        }

        return $this;
    }

    public function removeConversationsFrom(Conversations $conversationsFrom): static
    {
        if ($this->conversationsFrom->removeElement($conversationsFrom)) {
            // set the owning side to null (unless already changed)
            if ($conversationsFrom->getFromUser() === $this) {
                $conversationsFrom->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getConversationsTo(): Collection
    {
        return $this->conversationsTo;
    }

    public function addConversationsTo(Conversations $conversationsTo): static
    {
        if (!$this->conversationsTo->contains($conversationsTo)) {
            $this->conversationsTo->add($conversationsTo);
            $conversationsTo->setToUser($this);
        }

        return $this;
    }

    public function removeConversationsTo(Conversations $conversationsTo): static
    {
        if ($this->conversationsTo->removeElement($conversationsTo)) {
            // set the owning side to null (unless already changed)
            if ($conversationsTo->getToUser() === $this) {
                $conversationsTo->setToUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCreatedBy($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCreatedBy() === $this) {
                $event->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Folder>
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): static
    {
        if (!$this->folders->contains($folder)) {
            $this->folders->add($folder);
            $folder->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): static
    {
        if ($this->folders->removeElement($folder)) {
            // set the owning side to null (unless already changed)
            if ($folder->getCreatedBy() === $this) {
                $folder->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Permissions>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permissions $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->setUser($this);
        }

        return $this;
    }

    public function removePermission(Permissions $permission): static
    {
        if ($this->permissions->removeElement($permission)) {
            // set the owning side to null (unless already changed)
            if ($permission->getUser() === $this) {
                $permission->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carpool>
     */
    public function getCarpools(): Collection
    {
        return $this->carpools;
    }

    public function addCarpool(Carpool $carpool): static
    {
        if (!$this->carpools->contains($carpool)) {
            $this->carpools->add($carpool);
            $carpool->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): static
    {
        if ($this->carpools->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getCreatedBy() === $this) {
                $carpool->setCreatedBy(null);
            }
        }

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
            $carpoolMember->setUser($this);
        }

        return $this;
    }

    public function removeCarpoolMember(CarpoolMembers $carpoolMember): static
    {
        if ($this->carpoolMembers->removeElement($carpoolMember)) {
            // set the owning side to null (unless already changed)
            if ($carpoolMember->getUser() === $this) {
                $carpoolMember->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lbc>
     */
    public function getLbcs(): Collection
    {
        return $this->lbcs;
    }

    public function addLbc(Lbc $lbc): static
    {
        if (!$this->lbcs->contains($lbc)) {
            $this->lbcs->add($lbc);
            $lbc->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLbc(Lbc $lbc): static
    {
        if ($this->lbcs->removeElement($lbc)) {
            // set the owning side to null (unless already changed)
            if ($lbc->getCreatedBy() === $this) {
                $lbc->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFile(Files $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getCreatedBy() === $this) {
                $file->setCreatedBy(null);
            }
        }

        return $this;
    }
}
