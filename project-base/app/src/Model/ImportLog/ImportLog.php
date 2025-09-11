<?php

declare(strict_types=1);

namespace App\Model\ImportLog;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="import_logs")
 * @ORM\Entity
 */
class ImportLog
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $startTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $status;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $errorMessage;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $recordsChanged;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $importType;

    /**
     * @param \App\Model\ImportLog\ImportLogData $importLogData
     */
    public function __construct(ImportLogData $importLogData)
    {
        $this->setData($importLogData);
    }

    /**
     * @param \App\Model\ImportLog\ImportLogData $importLogData
     */
    public function edit(ImportLogData $importLogData): void
    {
        $this->setData($importLogData);
    }

    /**
     * @param \App\Model\ImportLog\ImportLogData $importLogData
     */
    protected function setData(ImportLogData $importLogData): void
    {
        $this->startTime = $importLogData->startTime;
        $this->endTime = $importLogData->endTime;
        $this->status = $importLogData->status;
        $this->errorMessage = $importLogData->errorMessage;
        $this->recordsChanged = $importLogData->recordsChanged;
        $this->importType = $importLogData->importType;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return int|null
     */
    public function getRecordsChanged(): ?int
    {
        return $this->recordsChanged;
    }

    /**
     * @return string
     */
    public function getImportType(): string
    {
        return $this->importType;
    }
}
