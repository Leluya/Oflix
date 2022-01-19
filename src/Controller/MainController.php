<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="main_")
 */
class MainController extends AbstractController
{

    /**
     * Page par défaut
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
       // return new Response("blabla");

       // @link https://symfony.com/doc/current/page_creation.html#rendering-a-template
        // Avec Twig et Abstractcontroller on a des méthode de 'render' pour générer le HTML
        // à partir des templates twig

        $modelMovie = new Movies();

       return $this->render('main/index.html.twig', ["movies" => $modelMovie->getAllMovies()]);
    }

    /**
     * @Route("/show/{id}", name="show", requirements={"id": "\d+"})
     * @return Reponse
     */
    public function show($id): Response
    {
        $modelMovie = new Movies();
        $movie = $modelMovie->getOneMovie($id);
        dump($movie);
        return $this->render('main/show.html.twig',
            [
                "movie" => $movie
            ]
        );
    }

     /**
    * Affiche tout les movies ou les résultats de recherche
    * @Route("/list", name="list")
     * 
     * @return Response
    */
    public function list(): Response
    {
        $modelMovie = new Movies();
        //dump($modelMovie);
        return $this->render('main/list.html.twig', 
            [
                "movies" => $modelMovie->getAllMovies()
            ]
        );
    }

    /**
     * User favorites list
     * 
     * @Route("/favorites", name="favorites")
     */
    public function favorites()
    {
        return $this->render('main/favorites.html.twig');
    }

}