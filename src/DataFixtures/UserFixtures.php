<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPseudo('Admin');
        $user->setUsername('Admin');
        $user->setImage(sprintf('%s', 'avatar.png'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'));
        $manager->persist($user);

        for ($i = 1; $i <= 50; ++$i) {
            $user = new User();
            $user->setEmail(sprintf('email+%d@email.com', $i));
            $user->setPseudo(sprintf('pseudo+%d', $i));
            $user->setUsername(sprintf('username+%d', $i));
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'));
            $user->setImage(sprintf('%s', 'avatar.png'));
            $manager->persist($user);
            $this->setReference(sprintf('user-%d', $i), $user);
        }
        $manager->flush();
    }
}
