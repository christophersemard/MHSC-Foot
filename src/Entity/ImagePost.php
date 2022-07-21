<?php

namespace App\Entity;

use App\Repository\ImagePostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagePostRepository::class)]
class ImagePost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'imagePosts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?post $post = null;

    #[ORM\Column(length: 255)]
    private ?string $ImageUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?post
    {
        return $this->post;
    }

    public function setPost(?post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->ImageUrl;
    }

    public function setImageUrl(string $ImageUrl): self
    {
        $this->ImageUrl = $ImageUrl;

        return $this;
    }
}
