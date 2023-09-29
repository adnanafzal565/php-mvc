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

		    // $this->connection->query("DROP TABLE IF EXISTS users");

		    $this->connection->query("CREATE TABLE IF NOT EXISTS users(
		    	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
		    	name TEXT NOT NULL,
		    	email TEXT NOT NULL,
		    	password TEXT NOT NULL,
		    	access_token TEXT NULL,
		    	reset_password TEXT NULL,
		    	verification_code TEXT NULL,
		    	verified_at DATETIME NULL DEFAULT NULL,
		    	created_at DATETIME DEFAULT CURRENT_TIMESTAMP
			)");
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