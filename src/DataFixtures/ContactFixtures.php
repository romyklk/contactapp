<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        
        $gender = ["male","female"];

        for($i=0;$i<=150;$i++){

            $contact = new Contact();

            $contactGender = mt_rand(0,1);

           

            if($contactGender ==0){
                $typeGender = "men";
            }else{
                $typeGender = "women";
            }

            

            $contact->setFirstName($faker->firstName($gender[$contactGender]))
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setPhone( $faker->e164PhoneNumber('fr_FR')) 
                    ->setAddress($faker->streetAddress('fr_FR'))
                    ->setZipCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setCountry("France")
                    ->setPicture("https://randomuser.me/api/portraits/". $typeGender."/".mt_rand(1,99).".jpg")
                    ->setSexe($contactGender);

            $manager->persist($contact);
        }

        $manager->flush();


    }
}
