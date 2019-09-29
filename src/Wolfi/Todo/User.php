<?php

namespace Wolfi\Todo;

class User
{
    /** @var int */
    private $userId;

    /** @var string */
    private $userName;

    /** @var string */
    private $userMail;

    /**
     * @param int $userId
     * @param string $userName
     * @param string $userMail
     */
    public function init(int $userId, string $userName, string $userMail)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userMail = $userMail;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

}