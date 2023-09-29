<?php

	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;

	class UserController extends Controller
	{
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

			echo json_encode([
        		"status" => "success",
        		"message" => "Data has been fetched.",
        		"user" => [
        			"id" => $user->id,
        			"name" => $user->name,
        			"email" => $user->email
        		]
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

			require_once VIEW . "/header.php";
			require_once VIEW . "/login.php";
			require_once VIEW . "/footer.php";
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
			require_once VIEW . "/header.php";
			require_once VIEW . "/verify-email.php";
			require_once VIEW . "/footer.php";
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
                
                $email = file_get_contents("views/emails/verification-email.php");
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

			require_once VIEW . "/header.php";
			require_once VIEW . "/register.php";
			require_once VIEW . "/footer.php";
		}
	}