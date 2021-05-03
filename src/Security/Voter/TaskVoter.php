<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['TASK_DELETE'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Task $subject */

        switch ($attribute) {
            case 'TASK_DELETE':
                return $this->isDelete($subject, $user);
        }

        return false;
    }

    private function isDelete(Task $task, User $user)
    {
        // The author can remove the stain
        if ($task->getAuthor() === $user)
            return true;

        // Only admin can delete an anonymous task
        return $task->isAnonymous() && $this->security->isGranted('ROLE_ADMIN');
    }
}
