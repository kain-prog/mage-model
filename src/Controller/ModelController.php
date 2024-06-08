<?php

namespace App\Controller;

use App\Repository\ModelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
    #[Route('/model', name: 'app_model', methods: ['GET'])]
    public function index(ModelRepository $modelRepository): Response
    {

        $user = $this->getUser();

        $company_id = $user->getCompany()->getId();

        $models = $modelRepository->findBy(['company' => $company_id]);


        return $this->render('model/index.html.twig', [
            'controller_name' => 'ModelController',
            'models' => $models
        ]);
    }

    #[Route('/model', name: 'app_model_store', methods: ['POST'])]
    public function store(ModelRepository $modelRepository, Request $request, SessionInterface $session): Response
    {
        $modelId = $request->request->get('id');
        $model = $modelRepository->find($modelId);

        $session->set('model', $model);

        return $this->redirectToRoute('app_template');
    }
}
