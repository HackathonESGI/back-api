<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Meeting;
use App\Repository\User\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ApiResource]
class Patient extends User
{
    #[ORM\Column]
    private ?float $lat = null;

    #[ORM\Column]
    private ?float $long = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $pathologies = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $ameli_id = null;

    /**
     * @var Collection<int, Provider>
     */
    #[ORM\ManyToMany(targetEntity: Provider::class, inversedBy: 'patients')]
    private Collection $Providers;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Meeting::class, fetch: 'EAGER')]
    private Collection $meetings;

    public function __construct()
    {
        $this->Providers = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles(); // TODO: Change the autogenerated stub
        $roles[] = 'ROLE_PATIENT';

        return array_unique($roles);
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }

    public function setLong(float $long): self
    {
        $this->long = $long;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getPathologies(): array
    {
        return $this->pathologies;
    }

    /**
     * @param array<string> $pathologies
     */
    public function setPathologies(array $pathologies): self
    {
        $this->pathologies = $pathologies;

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

    public function getAmeliId(): ?string
    {
        return $this->ameli_id;
    }

    public function setAmeliId(?string $ameli_id): self
    {
        $this->ameli_id = $ameli_id;

        return $this;
    }

    /**
     * @return Collection<int, Provider>
     */
    public function getProviders(): Collection
    {
        return $this->Providers;
    }

    public function addProvider(Provider $provider): self
    {
        if (!$this->Providers->contains($provider)) {
            $this->Providers->add($provider);
        }

        return $this;
    }

    public function removeProvider(Provider $provider): self
    {
        $this->Providers->removeElement($provider);

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
            $meeting->setPatient($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getPatient() === $this) {
                $meeting->setPatient(null);
            }
        }

        return $this;
    }
}
