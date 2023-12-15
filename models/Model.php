<?php

class Model
{
	protected $connection = null;
	
	public function __construct()
	{
	    if ($this->connection == null)
	    {
		    // $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		    $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
	    }
	}

    public function secure_input($input)
    {
        $input = str_replace("'", "", $input);
        $input = str_replace("\"", "", $input);
        $input = str_replace(" ", "-", $input);
        $input = mysqli_real_escape_string($this->connection, $input);
        return $input;
    }
}