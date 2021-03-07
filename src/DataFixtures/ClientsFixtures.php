<?php

namespace App\DataFixtures;

use App\Entity\Clients;
use App\Entity\ApiToken;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Clients=array('Client1','Client2','Client3');
        $Description=array('The best','The first','Le roi des Mobiles');

        for($i=0;$i<count($Clients);$i++){
            $Client= new Clients();
            $Client->setUsername($Clients[$i]);
            $Client->setDescription($Description[$i]);
            $apiToken1 = new ApiToken($Client);
            $manager->persist($apiToken1);

            // $manager->persist($Client);
            $manager->flush();
            $this->addReference($Clients[$i],$Client);
        }
    }
}
