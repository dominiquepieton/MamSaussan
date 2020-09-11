<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $roleAdmin = new Role();

        $roleAdmin->setTitle('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $adminUser = new User();
        $adminUser->setFirstName('Dominique')
                  ->setLastName('Pieton')
                  ->setEmail('pietondominique@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser,'MymamPietonDOm091977!'))
                  ->setNameChild('nono')
                  ->addUserRole($roleAdmin);

        $manager->persist($adminUser);          
        $users = [];
        
        for($i=1; $i<=10; $i++){
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setNameChild($faker->firstname)
                 ->setHash($hash);

            $manager->persist($user);
            $users[] = $user;

        }

        $manager->flush();
    }
}
