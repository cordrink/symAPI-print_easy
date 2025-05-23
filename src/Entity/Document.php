<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            denormalizationContext: ['groups' => ['document_write']]
        )
    ],
    normalizationContext: ['groups' => ['documents_read']],
    paginationEnabled: true
)]
#[ApiResource(
    uriTemplate: '/profil/{id}/document',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'profil',
            fromClass: Profil::class
        )
    ]
)]

class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('documents_read')]
    private ?string $fileName = null;

    #[Assert\File(
        maxSize: '5M',
        extensions: ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        extensionsMessage: "Veuillez selectionner un fichier de type : jpg, jpeg, png, pdf, doc, docx"
    )]
    #[Vich\UploadableField(mapping: 'documents', fileNameProperty: 'fileName')]
    #[Groups('document_write')]
    private ?File $file;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(["attente", "imprime"], message: "Le statut doit être 'attente' ou 'imprime'. " )]
    #[Groups('documents_read')]
    private ?string $status = 'attente';

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $printedAt = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'document')]
    private Collection $notifications;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Profil $profil = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Order $_order = null;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setUploadedAtValue(): void
    {
        $this->uploadedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file): static
    {
        $this->file = $file;

        if (null !== $this->file) {
            $this->uploadedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getPrintedAt(): ?\DateTimeImmutable
    {
        return $this->printedAt;
    }

    public function setPrintedAt(?\DateTimeImmutable $printedAt): static
    {
        $this->printedAt = $printedAt;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setDocument($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getDocument() === $this) {
                $notification->setDocument(null);
            }
        }

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): static
    {
        $this->_order = $_order;

        return $this;
    }
}
