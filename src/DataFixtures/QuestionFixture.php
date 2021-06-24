<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <= 10; $i++){
            $question = new Question();
            $question->setTitle('question '.$i.' generated');
            $question->setPromoted('true');
            $question->setStatus('draft');
            $question->setCreatedAt(new \DateTime());
            $manager->persist($question);
            $manager->flush();
        }

    }
}
