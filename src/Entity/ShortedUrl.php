<?php
declare(strict_types=1);

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

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $destiny = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $originUrl = null;

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

    public function originUrl(): ?string
    {
        return $this->originUrl;
    }

    public function setOriginUrl(string $localUrl): self
    {
        $this->originUrl = $localUrl;

        return $this;
    }
}
