<?php

declare(strict_types=1);

namespace App\Model\ImportLog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ImportLogRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $importLogId
     * @return \App\Model\ImportLog\ImportLog
     */
    public function getById(int $importLogId): ImportLog
    {
        $importLog = $this->getImportLogRepository()->find($importLogId);

        if ($importLog === null) {
            throw new \Exception('Import log with id ' . $importLogId . ' not found');
        }

        return $importLog;
    }

    /**
     * @return \App\Model\ImportLog\ImportLog[]
     */
    public function getAll(): array
    {
        return $this->getImportLogRepository()->findAll();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getImportLogRepository(): EntityRepository
    {
        return $this->em->getRepository(ImportLog::class);
    }
}
