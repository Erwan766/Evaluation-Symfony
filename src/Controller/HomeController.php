<?php

namespace App\Controller;

use doctrine;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // le champ: '' est vide pour que ça soit la page par défaut
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Produit::class);
        $listeProduit = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'listeProduit' => $listeProduit,
        ]);
    }
}
