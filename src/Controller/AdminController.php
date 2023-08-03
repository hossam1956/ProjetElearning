<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/formations", name="app_admin.formation")
     */
    #[Route('/formations', name: 'app_admin.formations')]
    public function formations(): Response
    {
        return $this->render('admin/formations.html.twig');
    }

    /**
     * @Route("/admin/users", name="app_admin.users")
     */
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }

    /**
     * @Route("/formation/detail", name="app_admin.formation.detail")
     */
    public function formationDetail(): Response
    {
        return $this->render('admin/formation_detail.html.twig');
    }
}
