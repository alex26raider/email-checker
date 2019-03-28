<?php

namespace Alexpriftuli\EmailChecker;


/**
 * Class Email.
 */
class Email
{
    /**
     * email
     */
    private $email;

    /**
     * domain
     */
    private $domain;

    /**
     * user
     */
    private $user;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        $parts = $this->parseEmail($email);
        $this->setUser($parts[0]);
        $this->setDomain($parts[1]);
    }

    private function parseEmail($email)
    {
        $parts = explode('@', $email);
        $domain = array_pop($parts);
        $user = implode('@', $parts);

        return [$user, $domain];
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function __construct($email = false)
    {
        $this->setEmail($email);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}