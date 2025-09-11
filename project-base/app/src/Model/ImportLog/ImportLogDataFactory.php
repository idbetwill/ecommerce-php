<?php

declare(strict_types=1);

namespace App\Model\ImportLog;

class ImportLogDataFactory
{
    /**
     * @return \App\Model\ImportLog\ImportLogData
     */
    public function create(): ImportLogData
    {
        $importLogData = new ImportLogData();
        $importLogData->startTime = new \DateTime();
        $importLogData->status = 'pending';
        $importLogData->importType = 'manual';

        return $importLogData;
    }

    /**
     * @param \App\Model\ImportLog\ImportLog $importLog
     * @return \App\Model\ImportLog\ImportLogData
     */
    public function createFromImportLog(ImportLog $importLog): ImportLogData
    {
        $importLogData = new ImportLogData();
        $importLogData->startTime = $importLog->getStartTime();
        $importLogData->endTime = $importLog->getEndTime();
        $importLogData->status = $importLog->getStatus();
        $importLogData->errorMessage = $importLog->getErrorMessage();
        $importLogData->recordsChanged = $importLog->getRecordsChanged();
        $importLogData->importType = $importLog->getImportType();

        return $importLogData;
    }
}
