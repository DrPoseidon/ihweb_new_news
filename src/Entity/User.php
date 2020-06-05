<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="News_user")
     */
    private $User_to_News;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="Comments_user")
     */
    private $User_to_Comments;

    public function __construct()
    {
        $this->User_to_News = new ArrayCollection();
        $this->User_to_Comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|News[]
     */
    public function getUserToNews(): Collection
    {
        return $this->User_to_News;
    }

    public function addUserToNews(News $userToNews): self
    {
        if (!$this->User_to_News->contains($userToNews)) {
            $this->User_to_News[] = $userToNews;
            $userToNews->setNewsUser($this);
        }

        return $this;
    }

    public function removeUserToNews(News $userToNews): self
    {
        if ($this->User_to_News->contains($userToNews)) {
            $this->User_to_News->removeElement($userToNews);
            // set the owning side to null (unless already changed)
            if ($userToNews->getNewsUser() === $this) {
                $userToNews->setNewsUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getUserToComments(): Collection
    {
        return $this->User_to_Comments;
    }

    public function addUserToComment(Comments $userToComment): self
    {
        if (!$this->User_to_Comments->contains($userToComment)) {
            $this->User_to_Comments[] = $userToComment;
            $userToComment->setCommentsUser($this);
        }

        return $this;
    }

    public function removeUserToComment(Comments $userToComment): self
    {
        if ($this->User_to_Comments->contains($userToComment)) {
            $this->User_to_Comments->removeElement($userToComment);
            // set the owning side to null (unless already changed)
            if ($userToComment->getCommentsUser() === $this) {
                $userToComment->setCommentsUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getEmail();
    }
}
