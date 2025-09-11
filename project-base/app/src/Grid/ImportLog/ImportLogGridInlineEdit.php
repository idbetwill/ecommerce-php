<?php

declare(strict_types=1);

namespace App\Grid\ImportLog;

use App\Form\Admin\ImportLogFormType;
use App\Model\ImportLog\ImportLogDataFactory;
use App\Model\ImportLog\ImportLogFacade;
use Shopsys\FrameworkBundle\Component\Grid\InlineEdit\AbstractGridInlineEdit;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ImportLogGridInlineEdit extends AbstractGridInlineEdit
{
    /**
     * @var \App\Grid\ImportLog\ImportLogGridFactory
     */
    private $importLogGridFactory;

    /**
     * @var \App\Model\ImportLog\ImportLogFacade
     */
    private $importLogFacade;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \App\Model\ImportLog\ImportLogDataFactory
     */
    private $importLogDataFactory;

    public function __construct(
        ImportLogGridFactory $importLogGridFactory,
        ImportLogFacade $importLogFacade,
        FormFactoryInterface $formFactory,
        ImportLogDataFactory $importLogDataFactory
    ) {
        parent::__construct($importLogGridFactory);
        $this->importLogGridFactory = $importLogGridFactory;
        $this->importLogFacade = $importLogFacade;
        $this->formFactory = $formFactory;
        $this->importLogDataFactory = $importLogDataFactory;
    }

    /**
     * @param string|int|null $rowId
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm(string|int|null $rowId): FormInterface
    {
        if ($rowId === null) {
            $importLogData = $this->importLogDataFactory->create();
        } else {
            $importLog = $this->importLogFacade->getById((int)$rowId);
            $importLogData = $this->importLogDataFactory->createFromImportLog($importLog);
        }

        return $this->formFactory->create(ImportLogFormType::class, $importLogData);
    }

    /**
     * @param string|int $rowId
     * @param mixed $formData
     */
    protected function editEntity(string|int $rowId, mixed $formData): void
    {
        $this->importLogFacade->edit((int)$rowId, $formData);
    }

    /**
     * @param mixed $formData
     * @return int
     */
    protected function createEntityAndGetId(mixed $formData): int
    {
        $importLog = $this->importLogFacade->create($formData);

        return $importLog->getId();
    }

    /**
     * @return string
     */
    public function getRoleConstant(): string
    {
        return 'ROLE_ADMIN';
    }
}
