<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\ProjetDevRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProjetDevRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']]
        ),
        new Put(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['projetdev:read']],
    denormalizationContext: ['groups' => ['projetdev:write']]
)]
#[Vich\Uploadable]
class ProjetDev
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['projetdev:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private array $technologie = [];

    #[Vich\UploadableField(mapping: 'projetdev_image', fileNameProperty: 'imageName')]
    #[Groups(['projetdev:write'])]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?string $websiteLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?string $githubLink = null;

    #[ORM\ManyToOne(inversedBy: 'projetDevs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['projetdev:read', 'projetdev:write'])]
    private ?User $user = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTechnologie(): array
    {
        return $this->technologie;
    }

    public function setTechnologie(array $technologie): static
    {
        $this->technologie = $technologie;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): static
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(?string $websiteLink): static
    {
        $this->websiteLink = $websiteLink;

        return $this;
    }

    public function getGithubLink(): ?string
    {
        return $this->githubLink;
    }

    public function setGithubLink(?string $githubLink): static
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
