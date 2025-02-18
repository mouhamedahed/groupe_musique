<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/salles')]
class SalleController extends AbstractController
{

    public function __construct(
                                private readonly EntityManagerInterface $entityManager,
                                private readonly SerializerInterface $serializer,
    private readonly SalleRepository $salleRepository)
    {
    }
    #[Route('/', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $salles = $this->salleRepository->findAll();
        $json = $this->serializer->serialize($salles, 'json', ['groups' => 'salle:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Salle $salle): JsonResponse
    {
        return $this->json($salle);
    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function addSalle(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['capacite']) ||!isset( $data['adresse'])) {
            return new JsonResponse(['error' => 'Données invalides'], 400);
        }

        $salle = new Salle();
        $salle->setName($data['name']);
        $salle->setCapacite($data['capacite']);
        $salle->setAdresse($data['adresse']);

        $em->persist($salle);
        $em->flush();

        return new JsonResponse(['message' => 'Salle ajoutée avec succès !'], 201);
    }

    #[Route('/edit/{id}', methods: ['PUT'])]
    public function update(Request $request, Salle $salle ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $salle->setName($data['nom'] ?? $salle->getName());
        $salle->setAdresse($data['adresse'] ?? $salle->getAdresse());
        $salle->setCapacite($data['capacite'] ?? $salle->getCapacite());

        $this->entityManager->flush();

        return $this->json($salle);
    }

    #[Route('/delete/{id}', methods: ['DELETE'])]
    public function delete(Salle $salle, ): JsonResponse
    {
        $this->entityManager->remove($salle);
        $this->entityManager->flush();

        return $this->json(['message' => 'Salle supprimée']);
    }
}
