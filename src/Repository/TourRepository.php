<?php

namespace App\Repository;

use App\Entity\Tour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class TourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class);
    }

    public function getTourPatients(Tour $tour): ArrayCollection
    {
        $patients = new ArrayCollection();
        foreach ($tour->getMeetings() as $meeting) {
            $patients->add($meeting->getPatient());
        }

        return $patients;
    }
}
