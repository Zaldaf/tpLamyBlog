<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //Initialiser Faker
        $faker=Factory::create("fr_FR");
        for ($i=0;$i<100;$i++){

            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->paragraphs(3,true))
                ->setCreateAt($faker->dateTimeBetween("-6 months"));
            //Associer l'article a une catégorie, récuperer une référence d'une catégorie
            $numUtilisateur = $faker->numberBetween(0,9);
            $random = $faker->numberBetween(0,5);
            if ($random > 1){
                $commentaire->setIdUtilisateur($this->getReference("utilisateur".$numUtilisateur ));
            }

            $numArticles = $faker->numberBetween(0,99);
            $commentaire->setIdArticle($this->getReference("article".$numArticles ));
            //Générer l'ordre INSERT
            //INSERT INTO article values ("titre 1","contenue de l'article)

            $manager->persist($commentaire);

        }


        // Envoyer l'ordre INSERT vers la base
        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UtilisateurFixtures::class,
            ArticleFixtures::class
        ];
    }
}
