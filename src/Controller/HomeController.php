<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Produit::class);
        $dernierProduits = $repository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'dernierProduits' => $dernierProduits,
        ]);
    }

    #[Route('/liste', name: 'app_liste')]
    public function liste(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Produit::class);
        $produits = $repository->findAll();
        
        return $this->render('home/Liste.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/{id}', name: 'produitInfo')]
    public function infoProduit(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    { 

        $commentaire = $produit->getCommentaire();
        $addCommentaire = new Commentaire();

        $form= $this->createForm(CommentaireType::class, $addCommentaire);

        // Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le commentaire au produit et l'enregistrer en base de données
            $addCommentaire->setProduitId($produit);
            $entityManager->persist($addCommentaire);
            $entityManager->flush();
        }

        return $this->render('home/info.html.twig', [
            'produit' => $produit,
            'commentaire' => $commentaire,
            'form' => $form->createView()
        ]);
    }
}
