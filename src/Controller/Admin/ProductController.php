<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\CustomProductCategory;
use App\Form\Admin\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CustomProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/products')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CustomProductCategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'admin_product_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1);
        $limit = 20;
        $search = $request->query->get('search', '');
        $categoryId = $request->query->get('category', '');

        $queryBuilder = $this->productRepository->createQueryBuilder('p')
            ->leftJoin('p.customCategories', 'c')
            ->addSelect('c');

        if ($search) {
            $queryBuilder->andWhere('p.name LIKE :search OR p.sku LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($categoryId) {
            $queryBuilder->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $queryBuilder->orderBy('p.createdAt', 'DESC');

        $products = $queryBuilder->getQuery()->getResult();
        $totalProducts = count($products);
        $totalPages = ceil($totalProducts / $limit);
        $offset = ($page - 1) * $limit;
        $products = array_slice($products, $offset, $limit);

        $categories = $this->categoryRepository->findBy(['isActive' => true], ['name' => 'ASC']);

        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }

    #[Route('/new', name: 'admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $product = new \App\Entity\Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTime());
            $product->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto creado exitosamente.');

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado.');
        }

        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_product_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTime());
            
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto actualizado exitosamente.');

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado.');
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto eliminado exitosamente.');
        }

        return $this->redirectToRoute('admin_product_index');
    }

    #[Route('/{id}/toggle-featured', name: 'admin_product_toggle_featured', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function toggleFeatured(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado.');
        }

        $product->setIsFeatured(!$product->isFeatured());
        $this->entityManager->flush();

        $status = $product->isFeatured() ? 'destacado' : 'no destacado';
        $this->addFlash('success', "Producto marcado como {$status}.");

        return $this->redirectToRoute('admin_product_index');
    }
}
