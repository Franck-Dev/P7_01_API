<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $Clients=array('Client1','Client2','Client3');
        for ($i = 0; $i < 10; $i++) {
            $client= new Clients;
            $client=$this->getReference($Clients[random_int(0,2)]);
            $user = new Users();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setUserName('Utilisateur'.$i);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));
            $user->setRoles(['ROLE_USER']);
            $user->setClient($client);

            $manager->persist($user);

            $manager->flush();
        }
    }
}
