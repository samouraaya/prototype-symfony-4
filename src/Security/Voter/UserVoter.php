<?php

namespace App\Security\Voter;

use App\Entity\Config\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserVoter extends Voter
{
    const EDIT='EDIT_USER';
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
    return $attribute===self::EDIT &&
        $subject instanceof User;

    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User ||
            !$subject instanceof User  ) {
            return false;
        }
        return $subject->getId()=== $user->getId();
        // ... (check conditions and return true to grant permission) ...



    }
}
