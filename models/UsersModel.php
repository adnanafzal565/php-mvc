<?php

class UsersModel extends Model
{
    private $table = "users";

    public function count_by_type($type)
    {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE type = ?";
        $result = $this->connection->prepare($sql);
        $result->execute([$type]);
        $result = $result->fetchObject();
        if ($result == null)
        {
            return 0;
        }
        return $result->total;
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM users";
        $result = $this->connection->prepare($sql);
        $result->execute();
        $result = $result->fetchObject();
        if ($result == null)
        {
            return 0;
        }
        return $result->total;
    }

    public function change_password($user_id)
    {
        $password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

        $sql = "UPDATE `" . $this->table . "` SET password=:password WHERE id = :user_id";
        
        $this->connection->prepare($sql)->execute([
            "password" => $password,
            "user_id" => $user_id
        ]);
    }

    public function save_profile($user_id, $profile_image)
    {
        $sql = "UPDATE `" . $this->table . "` SET name = :name, profile_image = :profile_image WHERE id = :user_id";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "name" => $_POST["name"],
            "profile_image" => $profile_image,
            "user_id" => $user_id
        ]);
    }

    public function logout($user_id)
    {
        $sql = "UPDATE `" . $this->table . "` SET access_token = NULL WHERE id = :user_id";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "user_id" => $user_id
        ]);
    }

    public function update_access_token($user_id, $token)
    {
        $sql = "UPDATE `" . $this->table . "` SET access_token = :token WHERE id = :user_id";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "token" => $token,
            "user_id" => $user_id
        ]);
    }

    public function register()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $sql = "INSERT INTO `" . $this->table . "`(`name`, `email`, `password`, verification_code, created_at) VALUES (:name, :email, :password, :verification_code, UTC_TIMESTAMP())";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "verification_code" => $verification_code
        ]);

        // mysqli_query($this->connection, $sql);

        return $verification_code;
    }

    public function is_exists($email)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE email = :email";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "email" => $email
        ]);

        return $result->fetch() != null;

        // $result = mysqli_query($this->connection, $sql);
        // return mysqli_num_rows($result) > 0;
    }

    public function login()
    {
        $response["error"] = "";
        $response["msg"] = "";

        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = :email";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "email" => $email
        ]);

        return $result->fetchObject();

        // $result = mysqli_query($this->connection, $sql);
    }

    public function get($user_id)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `id` = :user_id";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "user_id" => $user_id
        ]);

        return $result->fetchObject();

        // $result = mysqli_query($this->connection, $sql);

        // if (mysqli_num_rows($result) > 0)
        //     return mysqli_fetch_object($result);
        // else
        //     return null;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM `" . $this->table . "` ORDER BY id DESC";
        $result = mysqli_query($this->connection, $sql);

        $data = array();
        while ($row = mysqli_fetch_object($result))
        {
            array_push($data, $row);
        }
        return $data;
    }

    public function get_by_email($email)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE `email` = :email";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "email" => $email
        ]);

        return $result->fetchObject();

        // $result = mysqli_query($this->connection, $sql);

        // if (mysqli_num_rows($result) > 0)
        //     return mysqli_fetch_object($result);
        // else
        //     return null;
    }

    public function do_verify_email($email, $code)
    {
        $sql = "UPDATE `" . $this->table . "` SET verified_at=UTC_TIMESTAMP() WHERE email = :email AND verification_code = :code";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "email" => $email,
            "code" => $code
        ]);
    }

    public function set_reset_token($token)
    {
        $email = $_POST["email"];

        $sql = "UPDATE `" . $this->table . "` SET reset_password=:token WHERE email = :email";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "token" => $token,
            "email" => $email
        ]);

        // mysqli_query($this->connection, $sql);
    }

    public function is_reset_password($email, $token)
    {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE email = :email AND reset_password = :token";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "email" => $email,
            "token" => $token
        ]);
        return ($result->fetchObject() != null);

        // $result = mysqli_query($this->connection, $sql);
        // return mysqli_num_rows($result) > 0;
    }

    public function reset_password($email, $token)
    {
        $password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

        $sql = "UPDATE `" . $this->table . "` SET password=:password, reset_password = NULL WHERE email = :email AND reset_password = :token";
        $result = $this->connection->prepare($sql);
        $result->execute([
            "password" => $password,
            "email" => $email,
            "token" => $token
        ]);

        // mysqli_query($this->connection, $sql);
    }
}