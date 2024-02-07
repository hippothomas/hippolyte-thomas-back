<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use App\Entity\Media;
use App\Entity\Project;
use App\Entity\Social;
use App\Entity\Technology;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');

        // Random picture only used for tests purpose
        $test_pictures = [
            'tests/picture1.jpg',
            'tests/picture2.jpg',
            'tests/picture3.jpg',
            'tests/picture4.jpg',
            'tests/picture5.jpg',
        ];

        // Adding profile picture for AboutMe
        $picture = new Media();
        $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures) - 1)])
                ->setCaption($faker->sentence(10));

        $manager->persist($picture);

        // Generating About Me informations
        $about_me = new AboutMe();
        $about_me->setName($faker->name('men'))
                 ->setJob($faker->sentence(4))
                 ->setDescription('<p>'.implode('</p><p>', (array) $faker->paragraphs(3)).'</p>')
                 ->setPicture($picture);

        $manager->persist($about_me);

        // Generating Socials
        for ($i = 0; $i < 3; ++$i) {
            $picture = new Media();
            $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures) - 1)]);

            $manager->persist($picture);

            $social = new Social();
            $social->setName($faker->word())
                    ->setLink($faker->url())
                    ->setPicture($picture);

            $manager->persist($social);
        }

        // Generating Technologies
        $technologies = [];
        for ($i = 0; $i < 10; ++$i) {
            $techno = new Technology();
            $techno->setName($faker->word());
            $manager->persist($techno);

            $technologies[] = $techno;
        }

        // Generating Projects
        for ($i = 0; $i < 10; ++$i) {
            $project = new Project();
            $name = implode(' ', (array) $faker->words(3));
            $project->setName($name)
                    ->setIntroduction($faker->sentence(10))
                    ->setDescription('<p>'.implode('</p><p>', (array) $faker->paragraphs(5)).'</p>');

            // Adding random technologies
            for ($j = 1; $j < mt_rand(1, 5); ++$j) {
                $project->addTechnology($technologies[mt_rand(0, count($technologies) - 1)]);
            }

            // Adding random pictures
            for ($j = 1; $j <= mt_rand(1, 4); ++$j) {
                $picture = new Media();
                $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures) - 1)])
                        ->setCaption($faker->sentence(10));
                $manager->persist($picture);

                $project->addPicture($picture);
            }

            $manager->persist($project);
        }

        // Generating Admin User
        $user = new User();

        $user->setUsername('admin')
             ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
