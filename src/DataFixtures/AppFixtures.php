<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Social;
use App\Entity\AboutMe;
use App\Entity\Project;
use App\Entity\Technology;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface) 
    {
        $this->hasher = $userPasswordHasherInterface;
    }

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
        $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures)-1)])
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
            $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures)-1)]);

            $manager->persist($picture);
            
            $social = new Social();
            $social->setName($faker->word())
                    ->setLink($faker->url())
                    ->setPicture($picture);

            $manager->persist($social);
        }

        // Generating Technologies
        $technologies = [];
        for ($i=0; $i < 10; $i++) { 
            $techno = new Technology();
            $techno->setName($faker->word());
            $manager->persist($techno);

            $technologies[] = $techno;
        }

        // Generating Projects
        for ($i=0; $i < 10; $i++) { 
            $project = new Project();
            $project->setName($faker->words(3, true))
                    ->setIntroduction($faker->sentence(10))
                    ->setDescription('<p>'.implode('</p><p>', $faker->paragraphs(5)).'</p>');

            // Adding random technologies
            for ($j=1; $j < mt_rand(1, 5); $j++) {
                $project->addTechnology($technologies[mt_rand(0, count($technologies)-1)]);
            }

            // Adding random pictures
            for ($j=1; $j <= mt_rand(1, 4); $j++) {
                $picture = new Media();
                $picture->setFileName($test_pictures[mt_rand(0, count($test_pictures)-1)])
                        ->setCaption($faker->sentence(10));
                $manager->persist($picture);

                $project->addPicture($picture);
            }            

            $manager->persist($project);
        }

        // Generating Admin User
        $user = new User();
        $password = $this->hasher->hashPassword(
            $user, "password"
        );

        $user->setUsername("admin")
             ->setRoles(['ROLE_ADMIN'])
             ->setPassword($password);

        $manager->persist($user);

        $manager->flush();
    }
}
