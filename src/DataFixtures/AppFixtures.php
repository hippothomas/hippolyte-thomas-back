<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use App\Entity\ApiKey;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Project;
use App\Entity\Social;
use App\Entity\Tag;
use App\Entity\Technology;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

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

        // Generating Tags
        $tags = [];
        for ($i = 0; $i < 10; ++$i) {
            $tag = new Tag();
            $tag->setName($faker->word());
            $manager->persist($tag);

            $tags[] = $tag;
        }

        $published_dates = [
            null,
            new \DateTime('-1 month'),
            new \DateTime('-1 week'),
            new \DateTime('-1 day'),
            new \DateTime('-1 hour'),
            new \DateTime('now'),
            new \DateTime('+1 day'),
            new \DateTime('+1 week'),
            new \DateTime('+1 month'),
            new \DateTime('+1 year'),
        ];

        // Generating Posts
        for ($i = 0; $i < 10; ++$i) {
            $post = new Post();
            $title = implode(' ', (array) $faker->words(3));
            $post->setTitle($title)
                    ->setUuid(Uuid::v4())
                    ->setContent('<p>'.implode('</p><p>', (array) $faker->paragraphs(5)).'</p>')
                    ->setFeatured($faker->boolean(10))
                    ->setPrimaryTag($tags[mt_rand(0, count($tags) - 1)])
                    ->setPublished($published_dates[$i]);

            // Add featured image
            $image = new Media();
            $image->setFileName($test_pictures[mt_rand(0, count($test_pictures) - 1)])
                    ->setCaption($faker->sentence(10));
            $manager->persist($image);
            $post->setFeatureImage($image);

            // Add tags
            for ($j = 1; $j < mt_rand(1, 5); ++$j) {
                $post->addTag($tags[mt_rand(0, count($tags) - 1)]);
            }

            $manager->persist($post);
        }

        // Generating Admin User
        $user = new User();

        $user->setUsername('admin')
             ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        // Generating API Keys
        $api_key = new ApiKey();

        $api_key->setKey('00000000-0000-0000-0000-000000000000')
                ->setAccount($user);

        $manager->persist($api_key);

        $manager->flush();
    }
}
