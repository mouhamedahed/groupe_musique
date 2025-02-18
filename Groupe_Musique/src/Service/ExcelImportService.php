<?php

namespace App\Service;

use App\Entity\Band;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExcelImportService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importBandsFromExcel(UploadedFile $file): void
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $band = new Band();
            $band->setName($row[0]);
            $band->setOrigin($row[1]);
            $band->setCity($row[2]);
            $band->setYearStart((int)$row[3]);
            $band->setYearEnd($row[4] ? (int)$row[4] : null);
            $band->setFounders($row[5]);
            $band->setMembresCount($row[6]);
            $band->setGenre($row[7]);
            $band->setDescription($row[8]);

            $this->entityManager->persist($band);
        }

        $this->entityManager->flush();
    }
}
