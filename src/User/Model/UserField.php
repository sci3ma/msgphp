<?php

declare(strict_types=1);

namespace MsgPhp\User\Model;

use MsgPhp\User\User;
use MsgPhp\User\UserIdInterface;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
trait UserField
{
    /**
     * @var User
     */
    private $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserId(): UserIdInterface
    {
        return $this->user->getId();
    }
}
