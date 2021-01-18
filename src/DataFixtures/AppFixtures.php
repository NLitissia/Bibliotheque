<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Livre;
use App\Entity\Pret;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   private $manager;
    private $faker;
    private $RepoLivre;
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker = Factory::create("fr_FR");
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
       $this->manager = $manager;
       $this->RepoLivre = $this->manager->getRepository(Livre::class);
       
       $this->loadAdherent();
       $this->LoadPret();


        $manager->flush();
    }
    /**
     * Creation des Adherents
     *
     * @return void
     */
    public function loadAdherent(){
        $genre = ['male','female'];
        $commune =["75003","75004","75005","75006","75007","75008","75009","75010","75011","75012","75013","75014","75015","75016","75017","75018","75019","75020"];
        for($i=0;$i<=20;$i++) {
            $adherent = new Adherent();
            $adherent->setNom($this->faker->lastName())
                     ->setPrenom($this->faker->firstName($genre[mt_rand(0,1)]))
                     ->setAdresse($this->faker->streetAddress())
                     ->setTelephone($this->faker->phoneNumber())
                     ->setCodeCommune($commune[mt_rand(0,sizeof($commune)-1)])
                     ->setEmail(strtolower($adherent->getNom()."@gmail.com"))
                     ->setPassword($this->passwordEncoder->encodePassword($adherent,$adherent->getNom()));
           
                     $this->addReference('adherent'.$i,$adherent);
             $this->manager->persist($adherent);
            
        }
        $adherent = new Adherent();
        $adherent ->setNom("NEGAA")
                   ->setPrenom("Litissia")
                   ->setAdresse("Rue Haxo")
                   ->setTelephone("0781347171")
                   ->setCodeCommune("75020")
                   ->setEmail("Admin@gmail.com")
                   ->setPassword($this->passwordEncoder->encodePassword($adherent,$adherent->getNom()));

          
          $this->manager->flush();         
    }
    /**
     * Création des Prets
     *
     * @return void
     */
    public function LoadPret(){
        for($i=0;$i<=20;$i++){
            $max = mt_rand(1,5);
            for($j=0;$j<=$max;$j++){
                $pret = new Pret();
                $livre = $this->RepoLivre->find(mt_rand(1,49));
                $pret ->setLivre($livre)
                      ->setAdherent($this->getReference('adherent'.$i))
                      ->setDatePert($this->faker->dateTimeBetween("-6 months"));
                $dateRetourPrevu =date('Y-m-d H:m:n',strtotime('15 days',$pret->getDatePert()->getTimestamp()));
                $dateRetourPrevu = \DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevu);
                $pret -> setDateRetourPrevu($dateRetourPrevu);
                if(mt_rand(1,3)==1){
                    $pret->setDateRetoutRelle($this->faker->dateTimeInInterval($pret->getDatePert(),"+30 days"));
                }
             $this->manager->persist($pret);
            }

        }
       $this->manager->flush(); 

    }
}
