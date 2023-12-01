<?php

require_once('env.php');

class Daily {

    public $day;
    public $year;

    private $cookie;
    private $url;
    private $dotenv;

    function __construct($day, $year)
    {
        $this->day = $day;
        $this->year = $year;

        $this->dotenv = new Env();

        $this->cookie = $this->dotenv->get('SESSION_COOKIE');
        $this->url = sprintf($this->dotenv->get('BASE_URL_STRING'), $this->year, $this->day);

        echo $this->url . PHP_EOL;
    }

    function get() : string|Exception {
        if(!file_exists('day-' . $this->day . '-input.txt') || true) {          
            $headers = [];
            $headers[] = 'Cookie: session=' . $this->cookie;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);

            if($data === false)
            {
                echo 'Curl error: ' . curl_error($ch);
            }

            curl_close($ch);
        
            // Save to file
            try {
                file_put_contents('day-01-input.txt', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $data = file_get_contents('day-01-input.txt');
        }

        return $data;
    }

}