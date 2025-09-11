<?php

declare(strict_types=1);

namespace App\Model\ImportLog;

use Doctrine\ORM\EntityManagerInterface;

class ImportLogFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \App\Model\ImportLog\ImportLogRepository
     */
    protected $importLogRepository;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Model\ImportLog\ImportLogRepository $importLogRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ImportLogRepository $importLogRepository)
    {
        $this->entityManager = $entityManager;
        $this->importLogRepository = $importLogRepository;
    }

    /**
     * @param \App\Model\ImportLog\ImportLogData $importLogData
     * @return \App\Model\ImportLog\ImportLog
     */
    public function create(ImportLogData $importLogData): ImportLog
    {
        $importLog = new ImportLog($importLogData);
        $this->entityManager->persist($importLog);
        $this->entityManager->flush();

        return $importLog;
    }

    /**
     * @param int $importLogId
     * @param \App\Model\ImportLog\ImportLogData $importLogData
     * @return \App\Model\ImportLog\ImportLog
     */
    public function edit(int $importLogId, ImportLogData $importLogData): ImportLog
    {
        $importLog = $this->getById($importLogId);
        $importLog->edit($importLogData);
        $this->entityManager->flush();

        return $importLog;
    }

    /**
     * @param int $importLogId
     * @return \App\Model\ImportLog\ImportLog
     */
    public function getById(int $importLogId): ImportLog
    {
        return $this->importLogRepository->getById($importLogId);
    }

    /**
     * @param int $importLogId
     */
    public function deleteById(int $importLogId): void
    {
        $importLog = $this->importLogRepository->getById($importLogId);
        $this->entityManager->remove($importLog);
        $this->entityManager->flush();
    }

    /**
     * @return \App\Model\ImportLog\ImportLog[]
     */
    public function getAll(): array
    {
        return $this->importLogRepository->getAll();
    }
}
