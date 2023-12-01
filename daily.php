<?php

require_once('env.php');

class Daily {

    public $day;
    public $year;

    private $cookie;
    private $url;
    private $dotenv;
    private $cache_file;

    function __construct($day, $year)
    {
        $this->day = str_pad($day, 2, '0', STR_PAD_LEFT);
        $this->year = $year;

        $this->dotenv = new Env();

        $this->cookie = $this->dotenv->get('SESSION_COOKIE');
        $this->url = sprintf($this->dotenv->get('BASE_URL_STRING'), $this->year, $this->day);

        $this->cache_file = __DIR__ . '/day-' . $this->day . '/day-' . $this->day . '-input.txt';

        echo $this->url . PHP_EOL;
    }

    function get() : string|Exception {
        if(!file_exists($this->cache_file)) {     
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
                file_put_contents($this->cache_file, $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $data = file_get_contents($this->cache_file);
        }

        return $data;
    }

}