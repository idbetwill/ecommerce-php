<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/simple-products')]
final class SimpleProductController extends AbstractController
{
    #[Route('/', name: 'admin_simple_product_index')]
    public function index(): Response
    {
        // Datos de ejemplo para demostrar la funcionalidad
        $products = [
            ['id' => 1, 'name' => 'Producto 1', 'price' => 100.00, 'sku' => 'SKU001'],
            ['id' => 2, 'name' => 'Producto 2', 'price' => 200.00, 'sku' => 'SKU002'],
            ['id' => 3, 'name' => 'Producto 3', 'price' => 300.00, 'sku' => 'SKU003'],
        ];

        return $this->render('admin/simple_product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/new', name: 'admin_simple_product_new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            // Aquí procesarías el formulario
            $this->addFlash('success', 'Producto creado exitosamente');
            return $this->redirectToRoute('admin_simple_product_index');
        }

        return $this->render('admin/simple_product/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'admin_simple_product_edit')]
    public function edit(int $id, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            // Aquí procesarías el formulario
            $this->addFlash('success', 'Producto actualizado exitosamente');
            return $this->redirectToRoute('admin_simple_product_index');
        }

        return $this->render('admin/simple_product/edit.html.twig', [
            'id' => $id
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_simple_product_delete')]
    public function delete(int $id): Response
    {
        // Aquí procesarías la eliminación
        $this->addFlash('success', 'Producto eliminado exitosamente');
        return $this->redirectToRoute('admin_simple_product_index');
    }
}
