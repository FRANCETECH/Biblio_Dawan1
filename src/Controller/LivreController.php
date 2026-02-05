<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'livre.index')]
    public function index(Request $request, LivreRepository $repository): Response
    {
       
        $livres = $repository->findAll();  
        
        return $this->render('livre/index.html.twig', [ 
            'livres' => $livres  
        ]);
    }

    #[Route('/livre/{slug}-{id}', name: 'livre.show', requirements: ['id' => '\d+', 'slug' => '[A-Za-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, LivreRepository $repository): Response
    {
        $livre = $repository->find($id);

        if (!$livre) {
            throw $this->createNotFoundException("Le livre avec l'id {$id} n'existe pas.");
        }

        if ($livre->getSlug() !== $slug) {
            return $this->redirectToRoute('livre.show', ['slug' => $livre->getSlug(), 'id' => $livre->getId()]);
        }

        return $this->render('livre/show.html.twig', [
            'livre' => $livre
        ]);
    }

    public function edit(Livre $livre, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(LivreType::class, $livre);  
        $form->handleRequest($request);  
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();   
            $this->addFlash('success', 'Le livre a bien été modifié');
            return $this->redirectToRoute('livre.index');
        }

        return $this->render('livre/edit.html.twig', [
            'form' => $form,
            'livre' => $livre
        ]);
    }

    #[Route('/livre/create', name: 'livre.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $livre = new livre();
        $form = $this->createForm(livreType::class, $livre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success', 'La recette a bien été créee');
            return $this->redirectToRoute('livre.index');
        }

        return $this->render('livre/create.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/livre/{id}/edit', name: 'livre.delete', methods: ['DELETE'])]
    public function remove(livre $livre, EntityManagerInterface $em)
    {
        $em->remove($livre);
        $em->flush(); 
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('livre.index');
    }









}
