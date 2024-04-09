<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Créer des catégories
        for( $i = 0 ; $i <= 3 ; $i++ ){
            $category = new Category();
            $category->setTitle($faker->title())
                    ->setDescription($faker->text());
            $manager->persist($category);

            //Créer des articles
            for($y =1; $y <= 4; $y++){
                $article = new Article();
                $article->setTitle($faker->title())
                        ->setContent($faker->text())
                        ->setImage($faker->imageUrl(360, 360, 'animals', true))
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setCategory($category);
                $manager->persist($article);
                // Créer des commentaires
                for($k = 1; $k <= 5; $k++){
                    $comment = new Comment();
                    $comment->setAuthor($faker->name())
                            ->setContent($faker->text())
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
       
    }
}
