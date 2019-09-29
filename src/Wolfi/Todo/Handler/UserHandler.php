<?php

namespace Wolfi\Todo\Handler;

use Symfony\Component\HttpFoundation\Session\Session;
use Wolfi\Todo\Exception\UserExistsException;
use Wolfi\Todo\Exception\UserNotFoundException;
use Wolfi\Todo\User;

class UserHandler extends Handler
{
    /** @var \PDOStatement[] */
    private $stmtCache = array();

    /**
     * @param string $userName
     * @param string $userMail
     * @param string $userPassword
     * @return User
     * @throws UserExistsException
     */
    public function createUser(string $userName, string $userMail, string $userPassword = null)
    {
        if (!isset($this->stmtCache['createUser'])) {
            $sql = "INSERT INTO user
                      (userName, userMail, userPasshash)
                    VALUES 
                      (:userName, :userMail, :userPasshash)
                    ";
            $this->stmtCache['createUser'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['createUser'];

        $passhash = ($userPassword) ? password_hash($userPassword, PASSWORD_DEFAULT) : null;
        $data = [
            'userName' => $userName,
            'userMail' => $userMail,
            'userPasshash' => $passhash
        ];
        try {
            $stmt->execute($data);
        } catch (\PDOException $exception) {
            if ($exception->errorInfo[1] === 1062) {
                throw new UserExistsException();
            }
        }
        $user = new User();
        $user->init($this->db->lastInsertId(), $userName, $userMail);
        return $user;
    }

    /**
     * @param $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getUser($userId)
    {
        if (!isset($this->stmtCache['getUser'])) {
            $sql = "SELECT * FROM user
                    WHERE userId = :userId
                    ";
            $this->stmtCache['getUser'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['getUser'];
        $stmt->execute(['userId' => $userId]);
        if ($stmt->rowCount() < 1) {
            throw new UserNotFoundException();
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $user = new User();
        $user->init($row['userId'], $row['userName'], $row['userMail']);
        return $user;
    }

    /**
     * @param string $userName
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserByUserName(string $userName): User
    {
        if (!isset($this->stmtCache['getUserByUserName'])) {
            $sql = "SELECT * FROM user
                    WHERE userName = :userName
                    ";
            $this->stmtCache['getUserByUserName'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['getUserByUserName'];
        $stmt->execute(['userName' => $userName]);
        if ($stmt->rowCount() < 1) {
            throw new UserNotFoundException();
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $user = new User();
        $user->init($row['userId'], $row['userName'], $row['userMail']);
        return $user;
    }

    /**
     * @param string $userName
     * @param string $password
     * @return bool
     * @throws UserNotFoundException
     */
    public function authenticateUser(string $userName, string $password)
    {
        if (!isset($this->stmtCache['authenticateUser'])) {
            $sql = "SELECT * FROM user
                    WHERE userName = :userName 
                    ";
            $this->stmtCache['authenticateUser'] = $this->db->prepare($sql);
        }
        $stmt = $this->stmtCache['authenticateUser'];
        $stmt->execute(['userName' => $userName]);
        if ($stmt->rowCount() < 1) {
            throw new UserNotFoundException();
        }
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (password_verify($password, $row['userPasshash'])) {
            return true;
        }
        return false;
    }

    /**
     * @param Session $session
     * @return bool
     */
    public function isLoggedIn(Session $session)
    {
        if ($session->get('todo_userId') && $session->get('todo_userName')) {
            return true;
        }
        return false;
    }
}