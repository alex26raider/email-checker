<?php

namespace Alexpriftuli\EmailChecker;


/**
 * Class EmailChecker.
 */
class EmailChecker
{
    /**
     * PHP Socket.
     *
     * @var 
     */
    protected $socket;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $domain;

    /**
     * @var
     */
    protected $from_email = 'me@example.com';

    /**
     * SMTP Port.
     *
     * @var 25
     */
    protected $port = 25;

    /**
     * Maximum Connection Time to an MTA.
     */
    protected $max_conn_time = 30;

    /**
     * Maximum Read Time from socket.
     */
    protected $max_read_time = 5;

    /**
     * @var
     */
    protected $nameServers = ['192.168.0.1'];

    /*
     * Return 1 if success, otherwise returns the error code (it returns 0 if it's a generic error)
     *
     * @param Email $email
     *
     * @return int
     */
    public function check(Email $email = null)
    {
        if (!is_object($email)) {
            return 0;
        }

        $disposable = json_decode(file_get_contents(__DIR__.'/json/list.json'), true);

        if (in_array($email->getDomain(), $disposable)) {
            return 0;
        }

        $mxs = [];

        list($hosts, $mxRecords) = $this->queryMX($email->getDomain());

        for ($n = 0; $n < count($hosts); $n++) {
            $mxs[$hosts[$n]] = $mxRecords[$n];
        }

        asort($mxs);

        array_push($mxs, $email->getDomain());

        $timeout = $this->max_conn_time / (count($hosts) > 0 ? count($hosts) : 1);

        // connect to SMTP
        foreach ($mxs as $host => $value) {
            if ($this->socket = @fsockopen($host, $this->port, $errno, $errstr, (float) $timeout)) {
                stream_set_timeout($this->socket, $this->max_read_time);
                break;
            }
        }

        if ($this->socket) {
            $reply = fread($this->socket, 2082);

            preg_match('/^([0-9]{3})/ims', $reply, $matches);
            $code = isset($matches[1]) ? $matches[1] : '';

            if ($code != '220') {
                $result = (int)$code;
            }

            $this->send('helo hi');

            $this->send('MAIL FROM: <'.$this->from_email.'>');

            // ask of rcpt
            $reply = $this->send('RCPT TO: <'.$email->getEmail().'>');

            // parse code and message
            preg_match('/^([0-9]{3}) /ims', $reply, $matches);
            $code = isset($matches[1]) ? $matches[1] : '';

            if ($code == '250') {
                // accepted
                $result = 1;
            } elseif ($code == '451' || $code == '452') {
                $result = 1;
            } else {
                $result = (int)$code;
            }

            $this->quit();
        } else {
            $result = 0;
        }

        return $result;
    }

    protected function send($msg)
    {
        fwrite($this->socket, $msg."\r\n");

        $reply = fread($this->socket, 2082);

        return $reply;
    }

    protected function queryMX($domain)
    {
        $hosts = [];
        $mxRecords = [];
        if (function_exists('getmxrr')) {
            getmxrr($domain, $hosts, $mxRecords);
        } else {
            // windows, we need Net_DNS
            require_once 'Net/DNS.php';

            $resolver = new Net_DNS_Resolver();

            // nameservers to query
            $resolver->nameServers = $this->nameServers;
            $resp = $resolver->query($domain, 'MX');
            if ($resp) {
                foreach ($resp->answer as $answer) {
                    $hosts[] = $answer->exchange;
                    $mxRecords[] = $answer->preference;
                }
            }
        }

        return [$hosts, $mxRecords];
    }

    protected function microtime_float()
    {
        list($usec, $sec) = explode(' ', microtime());

        return (float) $usec + (float) $sec;
    }

    protected function quit()
    {
        // quit
        $this->send('quit');
        // close socket
        fclose($this->socket);
    }

}
