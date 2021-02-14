<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProduitsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Produits=array('Nokia3310','Alcatel','IPhone4','IPhone6','IPhone10','Mico','OneNote','Galaxy 4');
        $Description=array('The best','The first','What\'s','Pas mieux','Pas tout compris','Le roi des Mobiles','Cabine téléphonique','C\est peu dire');

        for($i=0;$i<count($Produits);$i++){
            $Produit= new Produits();
                $Produit  ->setName($Produits[$i])
                        ->setDescription($Description[$i]);

                $manager->persist($Produit);
                $manager->flush();
        }
    }
}
