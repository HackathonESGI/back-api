<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\User\Patient;
use App\Enum\MeetingStatusEnum;
use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
#[ApiResource]
#[ORM\UniqueConstraint(
    name: 'unique_patient_meeting',
    columns: ['patient_id', 'tour_id', 'starting_date', 'ending_date']
)]
class Meeting implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'meetings')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'meetings')]
    private ?Tour $tour = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $starting_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $ending_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, enumType: MeetingStatusEnum::class)]
    private ?MeetingStatusEnum $status = null;

    #[ORM\OneToMany(mappedBy: 'meeting', targetEntity: MeetingLog::class)]
    private Collection $meetingLogs;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    private ?MeetingCategory $category = null;

    public function __construct()
    {
        $this->meetingLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->starting_date;
    }

    public function setStartingDate(\DateTimeInterface $starting_date): self
    {
        $this->starting_date = $starting_date;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->ending_date;
    }

    public function setEndingDate(\DateTimeInterface $ending_date): self
    {
        $this->ending_date = $ending_date;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getStatus(): ?MeetingStatusEnum
    {
        return $this->status;
    }

    public function setStatus(MeetingStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, MeetingLog>
     */
    public function getMeetingLogs(): Collection
    {
        return $this->meetingLogs;
    }

    public function addMeetingLog(MeetingLog $meetingLog): self
    {
        if (!$this->meetingLogs->contains($meetingLog)) {
            $this->meetingLogs->add($meetingLog);
            $meetingLog->setMeeting($this);
        }

        return $this;
    }

    public function removeMeetingLog(MeetingLog $meetingLog): self
    {
        if ($this->meetingLogs->removeElement($meetingLog)) {
            // set the owning side to null (unless already changed)
            if ($meetingLog->getMeeting() === $this) {
                $meetingLog->setMeeting(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?MeetingCategory
    {
        return $this->category;
    }

    public function setCategory(?MeetingCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
