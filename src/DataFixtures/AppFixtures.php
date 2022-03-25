<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
        
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User;

        $hash = $this->encoder->encodePassword($user, 'admin');
            $user->setHash($hash)
                ->setLogin('test');

            $manager->persist($user);
            $manager->flush();

        $manager->flush();
    }
}
