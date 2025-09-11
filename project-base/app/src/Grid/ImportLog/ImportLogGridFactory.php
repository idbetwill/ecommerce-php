<?php

declare(strict_types=1);

namespace App\Grid\ImportLog;

use App\Model\ImportLog\ImportLog;
use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\Grid\DataSourceInterface;
use Shopsys\FrameworkBundle\Component\Grid\Grid;
use Shopsys\FrameworkBundle\Component\Grid\GridFactory;
use Shopsys\FrameworkBundle\Component\Grid\GridFactoryInterface;
use Shopsys\FrameworkBundle\Component\Grid\QueryBuilderDataSource;

class ImportLogGridFactory implements GridFactoryInterface
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Grid\GridFactory
     */
    protected $gridFactory;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Grid\GridFactory $gridFactory
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(GridFactory $gridFactory, EntityManagerInterface $entityManager)
    {
        $this->gridFactory = $gridFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|null $roleConstant
     * @return \Shopsys\FrameworkBundle\Component\Grid\Grid
     */
    public function create(?string $roleConstant = null): Grid
    {
        $grid = $this->gridFactory->create('importLogGrid', $this->createAndGetDataSource());

        $grid->addColumn('id', 'il.id', t('ID'));
        $grid->addColumn('startTime', 'il.startTime', t('Start Time'), true);
        $grid->addColumn('endTime', 'il.endTime', t('End Time'), true);
        $grid->addColumn('status', 'il.status', t('Status'), true);
        $grid->addColumn('errorMessage', 'il.errorMessage', t('Error Message'));
        $grid->addColumn('recordsChanged', 'il.recordsChanged', t('Records Changed'), true);
        $grid->addColumn('importType', 'il.importType', t('Import Type'), true);

        $grid->setDefaultOrder('startTime', DataSourceInterface::ORDER_DESC);
        $grid->enablePaging();

        $grid->addDeleteActionColumn('admin_importlog_delete', ['id' => 'il.id'])
            ->setConfirmMessage(t('Do you really want to remove this import log?'));

        $grid->setTheme('Admin/Content/ImportLog/listGrid.html.twig');

        return $grid;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Grid\DataSourceInterface
     */
    protected function createAndGetDataSource(): DataSourceInterface
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('il')
            ->from(ImportLog::class, 'il');

        return new QueryBuilderDataSource($queryBuilder, 'il.id');
    }
}
