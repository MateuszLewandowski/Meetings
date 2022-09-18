<?php

namespace App\Entity;

use App\Http\DTO\MeetingDTO;
use App\Http\DTO\UserDTO;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements FactorableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Meeting $meeting = null;

    #[ORM\ManyToMany(targetEntity: Meeting::class, mappedBy: 'participants')]
    private Collection $meetings;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(Meeting $meeting): self
    {
        // set the owning side of the relation if necessary
        if ($meeting->getOwner() !== $this) {
            $meeting->setOwner($this);
        }

        $this->meeting = $meeting;

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->addParticipant($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->removeElement($meeting)) {
            $meeting->removeParticipant($this);
        }

        return $this;
    }

    public function toDTO() {

        $meetingsDTOCollection = [];
        $meetings = $this->getMeetings();
        $len = count($meetings);
        if ($len > 0) {
            foreach ($meetings as $i => $meeting) {
                $meetingsDTOCollection[$i] = $meeting->toDTO();
            }
        }
        return new UserDTO(
            $this->getId(),
            $this->getName(),
            $this->getEmail(),
            $meetingsDTOCollection,
        );
    }
}
