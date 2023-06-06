<?php

declare(strict_types=1);

namespace App\Controller\Tour;

use App\Entity\Tour;
use App\Repository\TourRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetTourPatients
{
    public function __construct(private TourRepository $tourRepository)
    {
    }

    public function __invoke(Tour $tour): Response
    {
        return new JsonResponse($this->tourRepository->getTourPatients($tour));
    }
}