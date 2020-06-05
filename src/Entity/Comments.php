<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $Text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="User_to_Comments")
     */
    private $Comments_user;

    /**
     * @ORM\ManyToOne(targetEntity=News::class, inversedBy="News_to_Comments")
     */
    private $Comments_news;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getCommentsUser(): ?User
    {
        return $this->Comments_user;
    }

    public function setCommentsUser(?User $Comments_user): self
    {
        $this->Comments_user = $Comments_user;

        return $this;
    }

    public function getCommentsNews(): ?News
    {
        return $this->Comments_news;
    }

    public function setCommentsNews(?News $Comments_news): self
    {
        $this->Comments_news = $Comments_news;

        return $this;
    }

    public function __toString()
    {
        return $this->getText();
    }
}
