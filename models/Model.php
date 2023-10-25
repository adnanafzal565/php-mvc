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
		    	profile_image TEXT NULL,
		    	type ENUM ('user', 'recruiter', 'admin') DEFAULT 'user',
		    	verified_at DATETIME NULL DEFAULT NULL,
		    	created_at DATETIME DEFAULT CURRENT_TIMESTAMP
			)");

			// $this->connection->query("ALTER TABLE users MODIFY COLUMN type ENUM('user', 'recruiter', 'admin') DEFAULT 'user'");

			$sql = "SELECT * FROM users WHERE type = 'admin'";
			$result = $this->connection->prepare($sql);
			$result->execute();
			$admin = $result->fetchObject();

			if ($admin == null)
			{
				$sql = "INSERT INTO `users`(`name`, `email`, `password`, verification_code, type, created_at, verified_at) VALUES (:name, :email, :password, :verification_code, :type, UTC_TIMESTAMP(), UTC_TIMESTAMP())";
		        $result = $this->connection->prepare($sql);
		        $result->execute([
		            "name" => "Admin",
		            "email" => "admin@gmail.com",
		            "password" => password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT),
		            "verification_code" => 0,
		            "type" => "admin"
		        ]);
			}
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