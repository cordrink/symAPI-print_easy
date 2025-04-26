<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats')]
    private Collection $userLeft;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats')]
    private Collection $userRight;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'chat')]
    private Collection $messages;

    public function __construct()
    {
        $this->userLeft = new ArrayCollection();
        $this->userRight = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserLeft(): Collection
    {
        return $this->userLeft;
    }

    public function addUser1(User $userLeft): static
    {
        if (!$this->userLeft->contains($userLeft)) {
            $this->userLeft->add($userLeft);
        }

        return $this;
    }

    public function removeUserLeft(User $userLeft): static
    {
        $this->userLeft->removeElement($userLeft);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserRight(): Collection
    {
        return $this->userRight;
    }

    public function addUserRight(User $userRight): static
    {
        if (!$this->userRight->contains($userRight)) {
            $this->userRight->add($userRight);
        }

        return $this;
    }

    public function removeUserRight(User $userRight): static
    {
        $this->userRight->removeElement($userRight);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }
}
