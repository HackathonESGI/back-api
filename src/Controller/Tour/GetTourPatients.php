<?php

declare(strict_types=1);

namespace App\Controller\Tour;

use App\Entity\Tour;
use App\Repository\TourRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetTourPatients
{
    public function __construct(
        private readonly TourRepository $tourRepository,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(Tour $tour): Response
    {
        $patients = $this->tourRepository->getTourPatients($tour);
        return new JsonResponse($this->serializer->serialize($patients, 'json'), Response::HTTP_OK, [], true);
    }
}
