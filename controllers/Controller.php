<?php

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

	class Controller {
		protected $header;
        protected $footer;
        protected $title;
        protected $user;

        protected $jwt_key = "adnanafzal565_php_mvc_jwt";
		
		public function __construct() {
			$this->header = VIEW . "layout/header.php";
            $this->footer = VIEW . "layout/footer.php";

            $this->title = "Home";
            $this->user = $this->get_logged_in_user();
		}

        protected function auth($optional = false, $type = "")
        {
            try
            {
                $token = trim(explode("Bearer", getallheaders()["Authorization"])[1]);
                $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
                $id = $decoded->id;
                $exp = $decoded->exp;

                if ($exp < time())
                {
                    if ($optional)
                    {
                        return null;
                    }

                    echo json_encode([
                        "status" => "error",
                        "message" => "You have been logged-out."
                    ]);

                    exit;
                }

                $user = $this->load_model("UsersModel")->get($id);

                if ($user == null)
                {
                    if ($optional)
                    {
                        return null;
                    }

                    echo json_encode([
                        "status" => "error",
                        "message" => "You have been logged-out."
                    ]);

                    exit;
                }

                if (!empty($type))
                {
                    if ($user->type != $type && $user->type != "admin")
                    {
                        echo json_encode([
                            "status" => "error",
                            "message" => "Un-authorized."
                        ]);

                        exit;
                    }
                }

                return $user;
            }
            catch (\Exception $exp)
            {
                echo json_encode([
                    "status" => "error",
                    "message" => "You have been logged-out."
                ]);

                exit;
            }
        }
		
		protected function get_header() {
            return $this->header;
        }

        protected function get_footer() {
            return $this->footer;
        }

		public function send_mail($to, $subject, $body) {
            // for live
            /*$headers = 'From: AdnanTech <your_email>' . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
            return mail($to, $subject, $body, $headers);*/

            try
            {
                $mail = new PHPMailer;

                // $mail->SMTPDebug = 3;                               // Enable verbose debug output

                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                             // Enable SMTP authentication
                $mail->Username = SMTP_FROM;                 // SMTP username
                $mail->Password = SMTP_PASSWORD;                           // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

                $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
                $mail->addAddress($to);     // Add a recipient
                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = $subject;
                $mail->Body    = $body;

                if(!$mail->send()) {

                    // echo json_encode([
                    //     "status" => "error",
                    //     "message" => "Mailer Error: " . $mail->ErrorInfo
                    // ]);
                    // exit;

                    // die('Mailer Error: ' . $mail->ErrorInfo);
                    // echo "<p>Mailer Error: " . $mail->ErrorInfo . "</p>";
                }
            }
            catch (\Exception $exp)
            {
                // 
            }
        }
		
		protected function load_model($model_name) {
			$path = "models/" . $model_name . ".php";
			if (file_exists($path)) {
				require_once($path);
				return new $model_name();
			} else {
				return null;
			}
		}

        public function is_admin_logged_in() {
            return isset($_SESSION["admin"]);
        }

        public function get_logged_in_user()
        {
            if (isset($_SESSION["user"]))
                return $_SESSION["user"];
            else
                return null;
        }

        public function do_logout()
        {
            unset($_SESSION["user"]);
            unset($_SESSION["admin"]);

            session_destroy();
            header("Location: " . URL . "user/login");
        }

        public function isMobile()
        {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        }

        public function time_elapsed_string($datetime, $full = false)
        {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        public function goto_admin_login()
        {
            header("Location: " . URL . "admin/login");
        }

        public function get_logged_in_admin()
        {
            if ($this->is_admin_logged_in())
                return $this->load_model("AdminModel")->get_admin($_SESSION["admin"]);
            else
                return null;
        }
	}
?>