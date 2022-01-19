<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_list")
     */
    public function list(): Response
    {

        $movieModel = new Movies();
        $movies = $movieModel->getAllMovies();
        // on renvoir une réponse de type jsonResponse
        // c'est la même chose Response, en plus spécifique
        // car ça rajoute le contenType dans les headers
        return $this->json($movies);
    }
}
