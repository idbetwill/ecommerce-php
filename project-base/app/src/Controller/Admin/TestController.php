<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/test')]
final class TestController extends AbstractController
{
    #[Route('/', name: 'admin_test_index')]
    public function index(): Response
    {
        return new Response('<h1>Test Admin Page</h1><p>This is a test page to verify the admin structure works.</p>');
    }
}
