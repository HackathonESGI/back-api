<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Tour\GetTourMeetings;
use App\Controller\Tour\GetTourPatients;
use App\Controller\Tour\GetTourTrip;
use App\Entity\User\Provider;
use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TourRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/tours/{id}/trip',
            controller: GetTourTrip::class,
        ),
        new GetCollection(
            uriTemplate: '/tours/{id}/meetings',
            controller: GetTourMeetings::class,
        ),
        new GetCollection(
            uriTemplate: '/tours/{id}/patients',
            controller: GetTourPatients::class,
            serialize: true
        ),
        new Post(),
        new Patch(),
        new Delete(),
        new Put()
    ],
)]
#[ORM\UniqueConstraint(
    name: 'unique_provider_tour',
    columns: ['provider_id', 'date']
)]
class Tour implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    private ?Provider $provider = null;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Meeting::class, fetch: 'EAGER')]
    private Collection $meetings;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

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
            $meeting->setTour($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getTour() === $this) {
                $meeting->setTour(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
