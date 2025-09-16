<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CustomProductCategory;
use App\Form\Admin\CustomProductCategoryType;
use App\Repository\CustomProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/categories')]
#[IsGranted('ROLE_ADMIN')]
class CustomProductCategoryController extends AbstractController
{
    public function __construct(
        private CustomProductCategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'admin_category_index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findBy([], ['sortOrder' => 'ASC', 'name' => 'ASC']);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new CustomProductCategory();
        $form = $this->createForm(CustomProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Categoría creada exitosamente.');

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada.');
        }

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada.');
        }

        $form = $this->createForm(CustomProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTime());
            
            $this->entityManager->flush();

            $this->addFlash('success', 'Categoría actualizada exitosamente.');

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada.');
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Categoría eliminada exitosamente.');
        }

        return $this->redirectToRoute('admin_category_index');
    }

    #[Route('/{id}/toggle-active', name: 'admin_category_toggle_active', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function toggleActive(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Categoría no encontrada.');
        }

        $category->setIsActive(!$category->isActive());
        $this->entityManager->flush();

        $status = $category->isActive() ? 'activada' : 'desactivada';
        $this->addFlash('success', "Categoría {$status}.");

        return $this->redirectToRoute('admin_category_index');
    }
}
