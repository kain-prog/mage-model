<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SavedTemplatesController extends AbstractController
{
    #[Route('/saved/templates', name: 'app_saved_templates')]
    public function index(): Response
    {
        return $this->render('saved_templates/index.html.twig', [
            'controller_name' => 'Templates Salvos',
        ]);
    }
}
