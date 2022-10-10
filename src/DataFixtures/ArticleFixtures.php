<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    //demander a symfony d'injecter le slugger au niveau du constructeur

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        //Initialiser Faker
        $faker=Factory::create("fr_FR");
        for ($i=0;$i<100;$i++){

            $article = new Article();
            $article->setTitre($faker->words($faker->numberBetween(3,10),true))
                ->setContenu($faker->paragraphs(3,true))
                ->setCreatAt($faker->dateTimeBetween("-6 months"))
                ->setSlug($this->slugger->slug($article->getTitre())->lower());
            $this->addReference("article".$i,$article);
            //Associer l'article a une catégorie, récuperer une référence d'une catégorie
            $numCategorie = $faker->numberBetween(0,8);
            $article->setCategorie($this->getReference("categorie".$numCategorie));
            $article->setIsPublie($faker->numberBetween(0,4)>1);
            //Générer l'ordre INSERT
            //INSERT INTO article values ("titre 1","contenue de l'article)

            $manager->persist($article);

        }


        // Envoyer l'ordre INSERT vers la base
        $manager->flush();
    }

    public function getDependencies()
    {
        return[
          CategorieFixtures::class
        ];
    }
}
