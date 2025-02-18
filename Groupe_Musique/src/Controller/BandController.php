<?php

namespace App\Controller;

use App\Entity\Band;
use App\Repository\BandRepository;
use App\Service\ExcelImportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/bands')]
class BandController extends AbstractController
{


    public function __construct(private readonly ExcelImportService     $importService,
                                private readonly EntityManagerInterface $entityManager,
                                private readonly BandRepository         $bandRepository,
    private readonly SerializerInterface $serializer)
    {
    }

    #[Route('/import', methods: ['POST'])]
    public function importBands(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'Fichier manquant'], Response::HTTP_BAD_REQUEST);
        }

        $this->importService->importBandsFromExcel($file);

        return new JsonResponse(['message' => 'Importation réussie'], Response::HTTP_OK);
    }


    /**
     *  Récupérer tous les groupes de musique
     */
    #[Route('/', methods: ['GET'])]
    public function getAllBands(): JsonResponse
    {
        $bands = $this->bandRepository->findAll();
        $json = $this->serializer->serialize($bands, 'json', ['groups' => 'band:read']);

        return new JsonResponse($json, 200, [], true);
    }
    /**
     *  Récupérer un groupe par ID
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getBand(int $id): JsonResponse
    {
        $band = $this->bandRepository->find($id);
        if (!$band) {
            return $this->json(['message' => 'Groupe non trouvé'], 404);
        }
        return $this->json($band, 200, [], ['groups' => 'band:read']);
    }

    /**
     *  Créer un groupe de musique
     */
    #[Route('/', methods: ['POST'])]
    public function createBand(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['name'], $data['origin'], $data['year_start'])) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        $band = new Band();
        $band->setName($data['name']);
        $band->setOrigin($data['origin']);
        $band->setYearStart($data['year_start']);

        if (isset($data['year_end'])) {
            $band->setYearEnd($data['year_end']);
        }

        $this->entityManager->persist($band);
        $this->entityManager->flush();

        return $this->json($band, 201, [], ['groups' => 'band:read']);
    }

    /**
     *  Mettre à jour un groupe de musique
     */
    #[Route('/{id}', methods: ['PUT'])]
    public function updateBand(int $id, Request $request): JsonResponse
    {
        $band = $this->bandRepository->find($id);
        if (!$band) {
            return $this->json(['message' => 'Groupe non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        if (isset($data['name'])) {
            $band->setName($data['name']);
        }
        if (isset($data['origin'])) {
            $band->setOrigin($data['origin']);
        }
        if (isset($data['year_start'])) {
            $band->setYearStart($data['year_start']);
        }
        if (isset($data['year_end'])) {
            $band->setYearEnd($data['year_end']);
        }

        $this->entityManager->flush();

        return $this->json($band, 200, [], ['groups' => 'band:read']);
    }

    /**
     *  Supprimer un groupe de musique
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteBand(int $id): JsonResponse
    {
        $band = $this->bandRepository->find($id);
        if (!$band) {
            return $this->json(['message' => 'Groupe non trouvé'], 404);
        }
        $this->entityManager->remove($band);
        $this->entityManager->flush();

        return $this->json(['message' => 'Groupe supprimé avec succès'], 204);
    }
}

