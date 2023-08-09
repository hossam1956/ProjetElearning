<?php
namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;

class LoggedInVoter extends AbstractVoter
{   

    protected function supports($attribute, $subject)
    {
        return $attribute === 'HAS_SPECIFIC_ROLE';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
    

    $user = $token->getUser();

    if (!$user) {
        return self::ACCESS_DENIED;
    }
    

    return self::ACCESS_DENIED;
    }
}
?>