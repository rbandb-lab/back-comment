<?php

namespace Infra\Symfony6\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Infra\Symfony6\ORM\Doctrine\Entity\Comment;
use Infra\Symfony6\ORM\Doctrine\Entity\Rating;
use Infra\Symfony6\ORM\Doctrine\Entity\User;
use Ramsey\Uuid\Uuid;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();
        $faker = Factory::create('en');
        for ($c = 0; $c < 10; $c++) {
            /** @var User $author */
            $author = $this->getReference('user-'.rand(0, 5));
            $comment = new Comment(
                id: Uuid::uuid4(),
                postId: rand(1, 2),
                commentContent: $faker->text(100),
                parent: null
            );
            $comment->setUser($author);
            $comment->setCreatedAt($now->getTimestamp() - rand(0, 10000));

            $nbOfRatings = rand(0, 5);
            $raters = [0,1,2,3,4,5];

            for ($r = 0; $r < $nbOfRatings; $r++) {
                shuffle($raters);
                $rater = $raters[0];
                unset($raters[0]);

                /** @var User $ratingAuthor */
                $ratingAuthor = $this->getReference('rating-user-'.$rater);

                $rating = new Rating(
                    Uuid::uuid4(),
                    rand(0, 10)
                );
                $rating->setUser($ratingAuthor);
                $comment->addRating($rating);
                $manager->persist($rating);
            }



            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
