<?php

declare(strict_types=1);

namespace App\Model\ImportLog;

class ImportLogData
{
    /**
     * @var \DateTime|null
     */
    public $startTime;

    /**
     * @var \DateTime|null
     */
    public $endTime;

    /**
     * @var string|null
     */
    public $status;

    /**
     * @var string|null
     */
    public $errorMessage;

    /**
     * @var int|null
     */
    public $recordsChanged;

    /**
     * @var string|null
     */
    public $importType;
}
