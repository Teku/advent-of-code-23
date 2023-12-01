<?php

class Env {

    public $path;
    private $data;

    function __construct()
    {
        $this->path = realpath(__DIR__."/.env");

        //Check .env environment file exists
        if(!is_file($this->path)){
            throw new ErrorException("Environment File is Missing.");
        }
        
        //Check .env environment file is readable
        if(!is_readable($this->path)){
            throw new ErrorException("Permission Denied for reading the ".($this->path).".");
        }

        $this->loadVariables();
    }

    private function loadVariables() {
        $var_arrs = array();
        // Open the .env file using the reading mode
        $fopen = fopen($this->path, 'r');

        if($fopen){
            //Loop the lines of the file
            while (($line = fgets($fopen)) !== false){
                // Check if line is a comment
                $line_is_comment = (substr(trim($line),0 , 1) == '#') ? true: false;
                // If line is a comment or empty, then skip
                if($line_is_comment || empty(trim($line)))
                    continue;

                // Split the line variable and succeeding comment on line if exists
                $line_no_comment = explode("#", $line, 2)[0];

                // Split the variable name and value
                $env_ex = preg_split('/(\s?)\=(\s?)/', $line_no_comment);
                $env_name = trim($env_ex[0]);
                $env_value = isset($env_ex[1]) ? trim($env_ex[1]) : "";
                $var_arrs[$env_name] = $env_value;
            }
            // Close the file
            fclose($fopen);

            $this->data = $var_arrs;
        } else {
            $this->data = array();
        }
    }

    function get($key) {
        if(array_key_exists($key, $this->data)){
            return $this->data[$key];
        } else {
            echo $key. " is not defined in the.env file.";
            return false;
        }
    }
}