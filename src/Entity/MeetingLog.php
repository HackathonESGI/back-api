<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MeetingLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingLogRepository::class)]
#[ApiResource]
class MeetingLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'meetingLogs')]
    private ?Meeting $meeting = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): self
    {
        $this->meeting = $meeting;

        return $this;
    }
}
