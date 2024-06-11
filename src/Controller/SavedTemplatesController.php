<?php

namespace App\Controller;

use App\Repository\TemplateRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SavedTemplatesController extends AbstractController
{
    #[Route('/saved/templates', name: 'app_saved_templates')]
    public function index(TemplateRepository $templateRepository): Response
    {

        $user = $this->getUser();

        $company_id = $user->getCompany()->getId();

        $templateRepository->findBy(['user' => $company_id]);

        return $this->render('saved_templates/index.html.twig', [
            'controller_name' => 'Templates Salvos',
            'templates' => $templateRepository
        ]);
    }

    public function store(Request $request, TemplateRepository $templateRepository): void
    {
        $this->store($request, $templateRepository);
    }

    public function destroy($id, TemplateRepository $templateRepository): void
    {
        $this->destroy($id, $templateRepository);
    }
}
