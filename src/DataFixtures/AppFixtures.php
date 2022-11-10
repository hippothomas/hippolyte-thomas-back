<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Media;
use App\Entity\Social;
use App\Entity\AboutMe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');

        // Adding profile picture for AboutMe
        $picture = new Media();
        $picture->setUrl('https://randomuser.me/api/portraits/men/'.mt_rand(1, 99).'.jpg')
                ->setCaption($faker->sentence(10));

        $manager->persist($picture);

        // Generating About Me informations
        $about_me = new AboutMe();
        $about_me->setName($faker->name('men'))
                 ->setJob($faker->sentence(4))
                 ->setDescription('<p>'.implode('</p><p>', $faker->paragraphs(3)).'</p>')
                 ->setPicture($picture);

        $manager->persist($about_me);

        // Generating Socials
        for ($i=0; $i < 3; $i++) { 
            $picture = new Media();
            $picture->setUrl('https://picsum.photos/id/'.mt_rand(1, 99).'/32/32');

            $manager->persist($picture);
            
            $social = new Social();
            $social->setName($faker->word())
                    ->setLink($faker->url())
                    ->setPicture($picture);

            $manager->persist($social);
        }

        $manager->flush();
    }
}
