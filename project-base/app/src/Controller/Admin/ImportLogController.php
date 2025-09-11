<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Grid\ImportLog\ImportLogGridInlineEdit;
use App\Model\ImportLog\ImportLogFacade;
use Shopsys\FrameworkBundle\Component\Router\Security\Annotation\CsrfProtection;
use Shopsys\FrameworkBundle\Controller\Admin\AdminBaseController;
use Symfony\Component\Routing\Annotation\Route;

class ImportLogController extends AdminBaseController
{
    /**
     * @var \App\Grid\ImportLog\ImportLogGridInlineEdit
     */
    protected $importLogGridInlineEdit;

    /**
     * @var \App\Model\ImportLog\ImportLogFacade
     */
    protected $importLogFacade;

    /**
     * @param \App\Grid\ImportLog\ImportLogGridInlineEdit $importLogGridInlineEdit
     * @param \App\Model\ImportLog\ImportLogFacade $importLogFacade
     */
    public function __construct(ImportLogGridInlineEdit $importLogGridInlineEdit, ImportLogFacade $importLogFacade)
    {
        $this->importLogGridInlineEdit = $importLogGridInlineEdit;
        $this->importLogFacade = $importLogFacade;
    }

    /**
     * @Route("/importlog/list/")
     */
    public function listAction()
    {
        $grid = $this->importLogGridInlineEdit->getGrid();

        return $this->render('Admin/Content/ImportLog/list.html.twig', [
            'gridView' => $grid->createView(),
        ]);
    }

    /**
     * @Route("/importlog/delete/{id}", requirements={"id" = "\d+"})
     * @CsrfProtection
     */
    public function deleteAction($id)
    {
        $this->importLogFacade->deleteById($id);

        $this->getFlashMessageSender()
            ->addInfoFlash(t('Import log with id %id% deleted', ['%id%' => $id]));

        return $this->redirectToRoute('admin_importlog_list');
    }
}
