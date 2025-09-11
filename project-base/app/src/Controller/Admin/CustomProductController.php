<?php

namespace App\Controller\Admin;

use App\Model\CustomProduct;
use App\Repository\CustomProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/admin/custom-products')]
final class CustomProductController extends AbstractController
{
    public function __construct(
        private readonly CustomProductRepository $customProductRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'admin_custom_product_index', methods: ['GET'])]
    public function index(): Response
    {
        $customProducts = $this->customProductRepository->findAll();

        return $this->render('admin/custom_product/index.html.twig', [
            'custom_products' => $customProducts,
        ]);
    }

    #[Route('/new', name: 'admin_custom_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $customProduct = new CustomProduct();
        $form = $this->createFormBuilder($customProduct)
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Precio',
                'required' => true,
                'currency' => 'USD',
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activo',
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customProduct->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($customProduct);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto personalizado creado exitosamente.');
            return $this->redirectToRoute('admin_custom_product_index');
        }

        return $this->render('admin/custom_product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_custom_product_show', methods: ['GET'])]
    public function show(CustomProduct $customProduct): Response
    {
        return $this->render('admin/custom_product/show.html.twig', [
            'custom_product' => $customProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_custom_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomProduct $customProduct): Response
    {
        $form = $this->createFormBuilder($customProduct)
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Precio',
                'required' => true,
                'currency' => 'USD',
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activo',
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Actualizar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customProduct->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto personalizado actualizado exitosamente.');
            return $this->redirectToRoute('admin_custom_product_index');
        }

        return $this->render('admin/custom_product/edit.html.twig', [
            'form' => $form->createView(),
            'custom_product' => $customProduct,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_custom_product_delete', methods: ['POST'])]
    public function delete(Request $request, CustomProduct $customProduct): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customProduct->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($customProduct);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto personalizado eliminado exitosamente.');
        }

        return $this->redirectToRoute('admin_custom_product_index');
    }
}
