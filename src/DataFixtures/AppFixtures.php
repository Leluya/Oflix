<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i<= 20; $i++)
        {
            // je veux pouvoir creer un Movie
            $newMovie =  new Movie();
            $newMovie->setTitle("Film #" . $i);

            $newMovie->setDuration(rand(30, 180));

            // rand(1, 2) => soit 1 soit 2
            // si rand(1, 2) == 1 alors 'Film' sinon 'Série'
            $type = rand(1, 2) == 1 ? 'Film' : 'Série';

            $newMovie->setType($type);
            // rating ne peux être Null
            $newMovie->setRating(mt_rand(0, 5));
            $newMovie->setReleaseDate(new DateTime("now"));
            $newMovie->setSummary('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur rhoncus maximus ipsum non volutpat. Quisque a velit quis metus consequat pulvinar.');
            $newMovie->setSynopsis('Aenean blandit, tortor ac pellentesque luctus, arcu enim aliquam augue, ac malesuada est magna a elit. Integer venenatis lacus id elit lacinia tincidunt. Cras purus leo, faucibus dictum dictum id, convallis id neque. Pellentesque consequat lorem a lacus egestas tempor. Nunc rutrum, ipsum interdum ullamcorper porta, metus velit faucibus lorem, in ullamcorper ligula odio a ipsum. In scelerisque enim eget sem vehicula, eu aliquet neque accumsan. Curabitur sit amet eros ut dui congue tristique et nec erat. Pellentesque est lorem, eleifend ac feugiat sit amet, scelerisque ut odio. Cras vel lectus ante. Sed est elit, fermentum sit amet neque a, tincidunt gravida urna. Proin hendrerit ex at lorem cursus tincidunt. Nunc ultricies rhoncus iaculis.');
            
            // très utile pour avoir des images différentes aléatoire pendant les tests
            $newMovie->setPoster('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
            
            // je veux des saisons pour UNIQUEMENT les séries
            if ($type == 'Série')
            {
                $nbSeason = rand(0, 5); // entre 0 et 5
                for ($j = 1; $j <= $nbSeason; $j++ ) // si 0 saison on passe pas dans la boucle
                {
                    $newSeason = new Season();
                    $newSeason->setNumber($j);
                    $newSeason->setEpisodesNumber(mt_rand(6, 24));
                    
                    // ne pas oublier de faire un persist
                    // pour que le manager prenne connaisance de ce nouvel objet
                    $manager->persist($newSeason);
                    
                    $newMovie->addSeason($newSeason);
                }
            }

            $manager->persist($newMovie);

            $newGenre = new Genre();
            $newGenreName = ["Animation","Action","Comedie","Horreur","science-fiction"];
            $newGenre->setName($newGenreName);
            $manager->persist($newGenre);
            $newMovie->addGenre($newGenre);
        }

        

        $manager->flush();
    }
}
