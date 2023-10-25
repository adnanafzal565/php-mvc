<?php

	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;

	class UserController extends Controller
	{
		public function reset_password($email, $token)
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$new_password = $_POST["new_password"];
				$confirm_password = $_POST["confirm_password"];

				if ($new_password != $confirm_password)
				{
					echo json_encode([
		        		"status" => "error",
		        		"message" => "Password mis-match."
		        	]);

		        	exit;
				}

				$is_requested = $this->load_model("UsersModel")->is_reset_password($email, $token);

				if (!$is_requested)
				{
					echo json_encode([
		        		"status" => "error",
		        		"message" => "Request failed."
		        	]);

		        	exit;
				}

				$this->load_model("UsersModel")->reset_password($email, $token);

				echo json_encode([
	        		"status" => "success",
	        		"message" => "Password has been reset. You can try login now."
	        	]);

	        	exit;
			}

			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/reset-password.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function send_recovery_email()
		{
			$email = $_POST["email"];
			$user = $this->load_model("UsersModel")->get_by_email($email);

			if ($user == null)
			{
				echo json_encode([
	        		"status" => "error",
	        		"message" => "User not found."
	        	]);

	        	exit;
			}

			if (!is_null($user->reset_password))
			{
				echo json_encode([
	        		"status" => "error",
	        		"message" => "Recovery email is already sent."
	        	]);

	        	exit;
			}

        	$token = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
			$this->load_model("UsersModel")->set_reset_token($token);

			$email = file_get_contents("views/emails/reset-password.php");
            $variables = array(
            	"{{ APP_NAME }}" => APP_NAME,
                "{{ website_url }}" => URL,
                "{{ token }}" => $token,
                "{{ name }}" => $user->name,
                "{{ email }}" => $user->email
            );
         
            foreach ($variables as $key => $value)
                $email = str_replace($key, $value, $email);
            $this->send_mail($_POST["email"], "Recovery Email - " . APP_NAME, $email);

            echo json_encode([
        		"status" => "success",
        		"message" => "Recovery email has been sent."
        	]);
		}

		public function forgot_password()
		{
			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/forgot-password.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function change_password()
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$user = $this->auth();

				$password = $_POST["password"];
				$new_password = $_POST["new_password"];
				$confirm_password = $_POST["confirm_password"];

				if (!password_verify($password, $user->password))
				{
					echo json_encode([
		        		"status" => "error",
		        		"message" => "In-correct password."
		        	]);

		        	exit;
				}

				if ($new_password != $confirm_password)
				{
					echo json_encode([
		        		"status" => "error",
		        		"message" => "Password mis-match."
		        	]);

		        	exit;
				}

				$this->load_model("UsersModel")->change_password($user->id);

				echo json_encode([
	        		"status" => "success",
	        		"message" => "Password has been changed."
	        	]);

	        	exit;
			}

			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/change-password.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function save_profile()
		{
			$user = $this->auth();
			$file_path = $user->profile_image ?? null;
			
			if (isset($_FILES["profile_image"])
				&& $_FILES["profile_image"]["size"] > 0)
			{
				$type = strtolower($_FILES["profile_image"]["type"]);
				if (!in_array($type, ["image/jpeg", "image/jpg", "image/png"]))
				{
					echo json_encode([
		        		"status" => "error",
		        		"message" => "Only JPG or PNG is allowed."
		        	]);
		        	exit;
				}

				$file_path = "uploads/" . basename($_FILES["profile_image"]["name"]);
				move_uploaded_file($_FILES["profile_image"]["tmp_name"], $file_path);

				if (file_exists($user->profile_image))
				{
					unlink($user->profile_image);
				}
			}

			$this->load_model("UsersModel")->save_profile($user->id, $file_path);

			echo json_encode([
        		"status" => "success",
        		"message" => "Profile has been updated."
        	]);
		}

		public function profile()
		{
			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/profile.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function logout()
		{
			$user = $this->auth();
			$this->load_model("UsersModel")->logout($user->id);

			echo json_encode([
        		"status" => "success",
        		"message" => "User has been logged-out."
        	]);
		}

		public function me()
		{
			$user = $this->auth();

			$user_obj = [
    			"id" => $user->id,
    			"name" => $user->name,
    			"email" => $user->email,
    			"profile_image" => "",
    			"type" => $user->type
    		];

    		if (!empty($user->profile_image))
    		{
    			$user_obj["profile_image"] = URL . "/" . $user->profile_image;
    		}

			echo json_encode([
        		"status" => "success",
        		"message" => "Data has been fetched.",
        		"user" => $user_obj
        	]);
		}

		public function login()
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$user = $this->load_model("UsersModel")->login();
				$password = $_POST["password"];
	            
	            if ($user == null)
		        {
		        	echo json_encode([
	            		"status" => "error",
	            		"message" => "Email does not exist."
	            	]);

	            	exit;
		        }

		        if (!password_verify($password, $user->password))
		        {
		        	echo json_encode([
	            		"status" => "error",
	            		"message" => "In-correct password."
	            	]);

	            	exit;
		        }

		        if ($user->verified_at == NULL)
		        {
		        	echo json_encode([
	            		"status" => "error",
	            		"message" => "Email not verified."
	            	]);

	            	exit;
		        }

		        $payload = [
		        	"id" => $user->id,
				    "iat" => time(),
				    "exp" => strtotime("+30 days", strtotime(date("Y-m-d H:i:s")))
				];

				/**
				 * IMPORTANT:
				 * You must specify supported algorithms for your application. See
				 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
				 * for a list of spec-compliant algorithms.
				 */
				$jwt = JWT::encode($payload, $this->jwt_key, 'HS256');

				$this->load_model("UsersModel")->update_access_token($user->id, $jwt);

		        echo json_encode([
            		"status" => "success",
            		"message" => "User has been logged-in.",
            		"token" => $jwt
            	]);

				exit;
			}

			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/login.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function verify()
		{
			$email = $_POST["email"];
			$code = $_POST["code"];

			if (!$email || !$code)
			{
				echo json_encode([
            		"status" => "error",
            		"message" => "Please provide email and code."
            	]);

            	exit;
			}

			$user = $this->load_model("UsersModel")->get_by_email($email);
			if ($user == null)
			{
				echo json_encode([
            		"status" => "error",
            		"message" => "Email does not exists."
            	]);

            	exit;
			}

			if ($user->verification_code != $code)
			{
				echo json_encode([
            		"status" => "error",
            		"message" => "Verification code mis-match."
            	]);

            	exit;
			}

			$this->load_model("UsersModel")->do_verify_email($email, $code);

			echo json_encode([
        		"status" => "success",
        		"message" => "Email has been verified. Please login again."
        	]);
		}

		public function verify_email($email)
		{
			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/verify-email.php";
			require_once VIEW . "/includes/footer.php";
		}

		public function register()
		{
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$UsersModel = $this->load_model("UsersModel");
	            if ($UsersModel->is_exists($_POST["email"]))
	            {
	            	echo json_encode([
	            		"status" => "error",
	            		"message" => "Email already exists."
	            	]);

	            	exit;
	            }

	            $verification_code = $UsersModel->register();
                
                $email = file_get_contents("views/emails/verification.php");
                $variables = array(
                	"{{ APP_NAME }}" => APP_NAME,
                    "{{ website_url }}" => URL,
                    "{{ verification_code }}" => $verification_code,
                    "{{ name }}" => $_POST["name"],
                    "{{ email }}" => $_POST["email"]
                );
             
                foreach ($variables as $key => $value)
                    $email = str_replace($key, $value, $email);
                $this->send_mail($_POST["email"], "Verify Email - " . APP_NAME, $email);

                echo json_encode([
            		"status" => "success",
            		"message" => "Account has been created. Please verify your email address."
            		// "message" => "Account has been created. Please login now."
            	]);

				exit;
			}

			require_once VIEW . "/includes/header.php";
			require_once VIEW . "/register.php";
			require_once VIEW . "/includes/footer.php";
		}
	}