<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ConfeVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {

        return in_array($attribute, ['EDIT'])
            && $subject instanceof \App\Entity\Conference;
    }

    protected function voteOnAttribute($attribute, $confe, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }


        switch ($attribute) {
            case 'EDIT':
                return $confe->getUser()->getId() == $user->getId();
        }

        return false;
    }
}