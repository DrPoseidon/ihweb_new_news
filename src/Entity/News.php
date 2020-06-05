<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="bigint")
     */
    private $Number_of_Views;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="User_to_News")
     */
    private $News_user;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="Comments_news")
     */
    private $News_to_Comments;

    public function __construct()
    {
        $this->News_to_Comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getNumberOfViews(): ?string
    {
        return $this->Number_of_Views;
    }

    public function setNumberOfViews(string $Number_of_Views): self
    {
        $this->Number_of_Views = $Number_of_Views;

        return $this;
    }

    public function getNewsUser(): ?User
    {
        return $this->News_user;
    }

    public function setNewsUser(?User $News_user): self
    {
        $this->News_user = $News_user;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getNewsToComments(): Collection
    {
        return $this->News_to_Comments;
    }

    public function addNewsToComment(Comments $newsToComment): self
    {
        if (!$this->News_to_Comments->contains($newsToComment)) {
            $this->News_to_Comments[] = $newsToComment;
            $newsToComment->setCommentsNews($this);
        }

        return $this;
    }

    public function removeNewsToComment(Comments $newsToComment): self
    {
        if ($this->News_to_Comments->contains($newsToComment)) {
            $this->News_to_Comments->removeElement($newsToComment);
            // set the owning side to null (unless already changed)
            if ($newsToComment->getCommentsNews() === $this) {
                $newsToComment->setCommentsNews(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
