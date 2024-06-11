<?php

namespace App\Controller;

use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ModelRepository $modelRepository): Response
    {

        $user = $this->getUser();

        $company_id = $user->getCompany()->getId();

        $models = $modelRepository->findBy(['company' => $company_id]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HelloWorld',
            'models' => $models
        ]);
    }
}
