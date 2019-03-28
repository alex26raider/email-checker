<?php

use PHPUnit\Framework\TestCase;
use Alexpriftuli\EmailChecker;

class EmailCheckerTest extends TestCase
{
    public $email_checker;
    public $email;

    /**
     * EmailCheckerTest constructor.
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->email = new EmailChecker\Email();
        $this->email_checker = new EmailChecker\EmailChecker();
    }

    public function testEmailCheckerIsTrue()
    {
        $emailToCheck = 'amigo.k8@gmail.com';
        $this->email->setEmail($emailToCheck);
        $this->assertEquals(1, $this->email_checker->check($this->email));
    }

    public function testEmailCheckerIsFalse()
    {
        $emailToCheck = 'example@example.com';
        $this->email->setEmail($emailToCheck);
        $this->assertEquals(0, $this->email_checker->check($this->email));
    }

    public function testDisposableMailIsFalse()
    {
        $emailToCheck = 'amigo.k8@0-mail.com';
        $this->email->setEmail($emailToCheck);
        $this->assertEquals(0, $this->email_checker->check($this->email));
    }
}
