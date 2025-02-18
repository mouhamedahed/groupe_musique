<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Concert;
use App\Entity\Salle;
use App\Repository\BandRepository;
use App\Repository\ConcertRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/concerts')]
class ConcertController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface               $entityManager,
                                private readonly ConcertRepository   $concertRepository,
                                private readonly SerializerInterface $serializer,
                                private readonly SalleRepository     $salleRepository,
                                private readonly BandRepository      $bandRepository,

    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 📌 Récupérer tous les concerts
     */
    #[Route('', methods: ['GET'])]
    public function getAllConcerts(): JsonResponse
    {
        $concerts = $this->concertRepository->findAll();
        $json = $this->serializer->serialize($concerts, 'json', ['groups' => 'concert:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * 📌 Récupérer un concert par ID
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getConcert(int $id, ConcertRepository $concertRepository): JsonResponse
    {
        $concert = $concertRepository->find($id);
        if (!$concert) {
            return $this->json(['message' => 'Concert non trouvé'], 404);
        }
        return $this->json($concert, 200, [], ['groups' => 'concert:read']);
    }

    /**
     * 📌 Ajouter un nouveau concert
     */
    #[Route('/add', methods: ['POST'])]
    public function addConcert(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['date'], $data['bands'], $data['salle'], $data['name'])) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        // Récupération de la salle
        $salle = $this->salleRepository->find($data['salle']['id']);
        if (!$salle) {
            return $this->json(['message' => 'Salle non trouvée'], 404);
        }

        // Récupération des groupes sous forme d'objets
        $bandIds = array_column($data['bands'], 'id');
        $bands = $this->bandRepository->findBy(['id' => $bandIds]);



        if (count($bands) !== count($bandIds)) {
            return $this->json(['message' => 'Un ou plusieurs groupes sont introuvables'], 404);
        }

        // Création du concert
        $concert = new Concert();
        $concert->setName($data['name']);
        $concert->setDate(new \DateTime($data['date']));
        $concert->setSalle($salle);

        // Ajout des groupes au concert
        foreach ($bands as $band) {
            $concert->addBand($band);
        }

        $this->entityManager->persist($concert);
        $this->entityManager->flush();

        return $this->json(['message' => 'Concert ajouté avec succès'], 201);
    }

    /**
     * 📌 Modifier un concert
     */
    #[Route('/edit/{id}', methods: ['PUT'])]
    public function updateConcert(int $id, Request $request): JsonResponse
    {
        $concert = $this->concertRepository->find($id);

        if (!$concert) {
            return $this->json(['message' => 'Concert non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data || !is_array($data)) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        // Mise à jour de la date
        if (!empty($data['date'])) {
            try {
                $concert->setDate(new \DateTime($data['date']));
            } catch (\Exception $e) {
                return $this->json(['message' => 'Format de date invalide'], 400);
            }
        }

        // Mise à jour des groupes (bands)
        if (!empty($data['bands']) && is_array($data['bands'])) {
            $bandIds = array_column($data['bands'], 'id');
            $bands = $this->bandRepository->findBy(['id' => $bandIds]);

            if (count($bands) !== count($bandIds)) {
                return $this->json(['message' => 'Un ou plusieurs groupes sont introuvables'], 400);
            }

            $concert->getBands()->clear();
            foreach ($bands as $band) {
                $concert->addBand($band);
            }
        }

        // Mise à jour de la salle
        if (!empty($data['salle']['id'])) {
            $salle = $this->salleRepository->find($data['salle']['id']);
            if (!$salle) {
                return $this->json(['message' => 'Salle introuvable'], 400);
            }
            $concert->setSalle($salle);
        }
        $this->entityManager->flush();
        try {
            $this->entityManager->flush();
            return $this->json(['message' => 'Concert mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Erreur lors de la mise à jour', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 📌 Supprimer un concert
     */
    #[Route('/delete/{id}', methods: ['DELETE'])]
    public function deleteConcert(int $id): JsonResponse
    {
        $concert = $this->entityManager->getRepository(Concert::class)->find($id);
        if (!$concert) {
            return $this->json(['message' => 'Concert non trouvé'], 404);
        }

        $this->entityManager->remove($concert);
        $this->entityManager->flush();

        return $this->json(['message' => 'Concert supprimé avec succès']);
    }
}
