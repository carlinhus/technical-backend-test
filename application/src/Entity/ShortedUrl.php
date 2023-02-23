<?php

namespace App\Entity;

use App\Repository\ShortedUrlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShortedUrlRepository::class)]
class ShortedUrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $destiny = null;

    #[ORM\Column(length: 255)]
    private ?string $localUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestiny(): ?string
    {
        return $this->destiny;
    }

    public function setDestiny(?string $destiny): self
    {
        $this->destiny = $destiny;

        return $this;
    }

    public function getLocalUrl(): ?string
    {
        return $this->localUrl;
    }

    public function setLocalUrl(string $localUrl): self
    {
        $this->localUrl = $localUrl;

        return $this;
    }
}
