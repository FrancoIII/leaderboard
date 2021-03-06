<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Challenge;

class ChallengeVoter extends Voter
{
    const HAS_VALIDATED = 'HAS_VALIDATED';
    const CAN_EDITER = 'CAN_EDITER';

    protected function supports($attribute, $subject)
    {
        if(in_array($attribute, array(self::HAS_VALIDATED)) && $subject instanceof Challenge){
            return true;
        }
        if(in_array($attribute, array(self::CAN_EDITER)) && $subject instanceof Challenge){
        return true;
        }
        if(in_array($attribute, array(self::CAN_EDITER)) && $subject == null){
            return true;
        }
        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::HAS_VALIDATED:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->voteHasValidated($subject, $token);
            case self::CAN_EDITER:
                return $this->voteCanEditer($subject, $token);
        }
        return false;
    }

    private function voteHasValidated($subject, TokenInterface $token){

        $user = $token->getUser();

        if($subject->getCreatedBy() == $user){
            return true;
        }

        if(in_array('ROLE_ADMIN', $token->getRoleNames())){
            return true;
        }
        if(in_array('ROLE_MODO', $token->getRoleNames())){
            return true;
        }

        $listValidation = $subject->getValidations();

        foreach($listValidation as $validation){
            if($validation->getCreatedBy() == $user){
                return true;
            }
        }

        return false;
    }

    private function voteCanEditer($subject, TokenInterface $token){

        $user = $token->getUser();

        if($subject == null){
            return true;
        }

        if($subject->getCreatedBy() == $user){
            return true;
        }

        if(in_array('ROLE_ADMIN', $token->getRoleNames())){
            return true;
        }
        if(in_array('ROLE_MODO', $token->getRoleNames())){
            return true;
        }

        return false;
    }
}
