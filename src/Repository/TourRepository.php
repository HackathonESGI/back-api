<?php

namespace App\Repository;

use App\Entity\Tour;
use App\Entity\User\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class);
    }

    /**
     * @param Tour $tour
     * @return array<Patient>
     */
    public function getTourPatients(Tour $tour): array
    {
        $patients = [];
        foreach ($tour->getMeetings() as $meeting) {
            $patients[] = $meeting->getPatient();
        }

        return $patients;
    }
}
