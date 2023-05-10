<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
// récupération de la class commentaire type pour inclure dans la page produitInfo
use App\Form\CommentaireType;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // controller de la page d'acceuil ici "/" pour que ça soit la page par défaut quand on arrive sur le site
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Produit::class);
        // ->findBy([], ['id' => 'DESC'], 5) permet de récupérer les cinq derniers id ajouter à mon entité produit
        $dernierProduits = $repository->findBy([], ['id' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'dernierProduits' => $dernierProduits,
        ]);
    }
    // controller de la page qui affiche tout les produits à la page /liste
    #[Route('/liste', name: 'app_liste')]
    public function liste(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Produit::class);
        // ->finAll() pour ici récupérer toutes les classes de mon entité produit
        $produits = $repository->findAll();
        
        return $this->render('home/Liste.html.twig', [
            'produits' => $produits,
        ]);
    }

    // controller de la page d'information de chaques produit à la page /produit/id du produit/
    #[Route('/produit/{id}', name: 'produitInfo')]
    public function infoProduit(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    { 
        // la variable commentaire à pour valeur la variable produit auquel on applique la méthode
        // getCommentaire pour récupérer les commentaires associés aux produit
        $commentaire = $produit->getCommentaire();
        // $addCommentaire pour créer un nouveau commentaire
        $addCommentaire = new Commentaire();

        //création d'un formulaire avec CommentaireType et la variable $addCommentaire
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
            //ajout du rendu du formulaire pour déposer un commentaire
            'form' => $form->createView()
        ]);
    }
}
