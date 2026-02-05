<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController 
{
    
    #[Route('/', name: 'home')] 
    public function index(Request $request, LivreRepository $rep): Response 
    {   
        $livres = $rep->findAll();
 
        return $this->render('home/index.html.twig', [ 
            'controller_name' => 'HomeController',
            'livres' => $livres
        ]);
  
    }
}
