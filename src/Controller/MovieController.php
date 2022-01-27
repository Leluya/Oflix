<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\RepositoryException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie", requirements={"id": "\d+"})
     */
    public function show(MovieRepository $movieRepos, int $id, CastingRepository $castingRepo): Response
    {
        $movie = $movieRepos->find($id);
        // On voit avec le dump que la propriété seasons n'est pas remplit 
        dump($movie);
        
        // je veux les casting d'un film en particulier : $id
        // j'utilise le findBy pour faire un find avec un critère
        // en SQL : movie_id = $id
        // Je suis en objet/entité
        // je dit donc : fait un filtre sur la propriété 'movie' de l'objet 'casting'
        // la valeur de cette propriété doit être égale à un objet Movie
        // je lui donne donc l'objet $movie pour faire le filtre
        // $criteria : ['propriété' => valeur]
        // $orderBy : ['propriété' => 'ASC/DESC']
        $castingsFilterByMovie = $castingRepo->findBy(['movie' => $movie], ['creditOrder' => 'ASC']);
        dump($castingsFilterByMovie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'castingsFilterByMovie' => $castingsFilterByMovie
        ]);
    }
    
    /**
     * show all movies
     * @Route("/movies", name="movies")
     * @param MovieRepository $repository
     * @return Response
     */
    public function showAll(MovieRepository $repository, GenreRepository $genreRepository): Response
    {
          // $movies = $repository->findAll();
          //$movies = $repository->findAllOrderedByTitle();
          $movies = $repository->findAllOrderedByTitleDQL();
          dump($movies);

          $genres = $genreRepository->findAll();

          return $this->render('movie/list.html.twig', [
            'movies' => $movies,
            'genres' => $genres
        ]);
    }

    // TODO Create
/**
 * methode de creation
 *
 * @param EntityManagerInterface $doctrine 
 * le super manager qui a le droit de faire des modif dans la base
 * @Route("/movie/create", name="movie_create")
 */
    public function create(EntityManagerInterface $doctrine): Response
    {
        // je veux pouvoir creer un Movie
        $newMovie =  new Movie();
        $newMovie->setTitle("Mon voisin totoro III : la vengeance");
        $newMovie->setDuration(90);
        $newMovie->setType("Film");
        $newMovie->setReleaseDate(new DateTime("now"));
        $newMovie->setSummary("lorem");
        $newMovie->setSynopsis("lorem ipsum");
        $newMovie->setPoster("https://m.media-amazon.com/images/M/MV5BYzJjMTYyMjQtZDI0My00ZjE2LTkyNGYtOTllNGQxNDMyZjE0XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg");
        // pas besoin de remplir le rating car c'est  nullable=true
        // id = null 
        dump($newMovie);

        // on demande au super manager de préparer l'insertion
        // pour préparer la base on "persiste" les données
        // la méthode persist correspond à la méthode "prepare" de PDO
        //! càd qu'elle ne fait PAS les requetes SQL 
        $doctrine->persist($newMovie);

        // Exécute les requêtes SQL
        $doctrine->flush();
        
        // id = 3 (ou autre) venant de la BDD
        dump($newMovie);
        
        return $this->redirectToRoute("movies");
    }

/**
     * Movie update
     * 
     * @Route("/movie/update/{id}", requirements={"id"="\d+"})
     */
    public function update(int $id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        // On va chercher l'enregistrement
        $movie = $movieRepository->find($id);

        // @todo Gérer la 404

        // On le modifie (titre au hasard)
        $movie->setTitle('Avatar ' . mt_rand(2, 99));

        // pas d'appel au persist car l'objet est déjà connu par le manager
        // Exécute la requête d'UPDATE
        $doctrine->flush();


        return $this->redirectToRoute('movie', array("id" => $id));
    }

    /**
     * Movie delete
     * 
     * @Route("/movie/delete/{id}", requirements={"id"="\d+"})
     */
    public function delete($id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        // On va chercher l'enregistrement
        $movie = $movieRepository->find($id);

        // @todo Gérer la 404

        // On demande au Manager de le supprimer
        // dans la même idée que le persist prend connaissance d'une entité
        // on lui demande d'oublier cette entité avec remove()
        $doctrine->remove($movie);
        // Exécute la requête d'UPDATE
        $doctrine->flush();

        return $this->redirectToRoute("movies");
    }
    
    /* 
    On peut abuser du framework en lui demandant d'aller chercher
    directement l'entity 
    Il va passer par le repository et faire un find()
    */
    /**
     * @Route("/movie/{id}", name="movie", requirements={"id": "\d+"})
     */
    /*
     public function showById(Movie $movie): Response
    {
        dump($movie);

        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
    */
}
