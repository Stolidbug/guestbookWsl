<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConferenceFixtures extends Fixture implements DependentFixtureInterface
{
    const CONFE_REFERENCE = 'conference';

    public function load(ObjectManager $manager)
    {
        for ($j = 1; $j <= 15; ++$j) {
            $figure = new Conference();
            $figure->setCity("Conference de la ville n° $j")
                ->setYear("2020")
                ->setIsInternational(1)
                ->setContent('Accusatorem ducebatur cruciatibus mansit et confessus incusare in consorte 
                    laceratus nec sputamine ut cruento passus inmobilis renidens temporum imitatus est fundato praecepit
                     praecepit imitatus morte consorte nec incusare Zenonem avulsam flagitaret sollemnia morte superbiam
                      atque nec nec quaedam quaedam mansit inplorans nec est qui et tamquam intrepidus multatus inpegit 
                      tamquam cruciatibus confutatus Stoicum veterem torvum Stoicum passus oculos libertatemque torvum 
                      excarnificari laceratus tandem atque oculos iniquitati obtrectatorem cum quaedam temporum tamquam 
                      cruciatibus linguam iustitiam avulsam accusatorem avulsam et et ita tandem caelo audacem laceratus
                       diutius flagitaret membra fundato nec excarnificari quemquam pectore pertinacius qui mentiretur 
                       mansit quaedam diutius passus obtrectatorem.')
                ->setImage(sprintf('%s', 'avatar.png'))
                ->setCreatedAt(new \DateTime())
                ->setUsers($this->getReference(sprintf('user-%d', ($j % 10) + 1)));
            $manager->persist($figure);
            $this->setReference(sprintf('trick-%d', $j), $figure);
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