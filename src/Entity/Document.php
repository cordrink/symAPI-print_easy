<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $fileName = null;

    #[Assert\File(
        maxSize: '5M',
        extensions: ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        extensionsMessage: "Veuillez selectionner un fichier de type : jpg, jpeg, png, pdf, doc, docx"
    )]
    private $file;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(["attente", "imprime"], message: "Le statut doit être 'attente' ou 'imprime'. " )]
    private ?string $status = 'attente';

    #[ORM\Column]
    #[Assert\DateTime]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $printedAt = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

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

    public function getUser(): ?Customer
    {
        return $this->customer;
    }

    public function setUser(?Customer $user): static
    {
        $this->customer = $user;

        return $this;
    }
}
