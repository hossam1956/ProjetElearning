<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/formations', name: 'app_admin.formations')]
    public function formations(): Response
    {
        return $this->render('admin/formations.html.twig');
    }

    #[Route('/users', name: 'app_admin.users')]
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }
}
