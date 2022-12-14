<?php

namespace App\Controller;

use App\Form\TypeForm;
use App\Repository\MaisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtabController extends AbstractController
{
    #[Route('/', name: 'app_etab')]
    public function index(MaisonRepository $maisonRepository, Request $request): Response
    {
        $allEtab = $maisonRepository->findAll();

        $form = $this->createForm(TypeForm::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid()){

            $allEtab = $maisonRepository->search($search->get('choix')->getData());
        }
        return $this->render('etab/index.html.twig', [
            'etabs' => $allEtab,
            'form' => $form->createView()
        ]);
    }
}
