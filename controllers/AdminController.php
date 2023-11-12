<?php

class AdminController extends Controller
{
	public function stats()
	{
		$user = $this->auth(false, "admin");
		$users = $this->load_model("UsersModel")->count_by_type("user");

		echo json_encode([
    		"status" => "success",
    		"message" => "Data has been fetched.",
    		"users" => $users
    	]);
        exit;
	}

	public function login()
	{
		require_once ADMIN_VIEW . "/login.php";
	}

	public function index()
	{
		require_once ADMIN_VIEW . "/includes/header.php";
		require_once ADMIN_VIEW . "/index.php";
		require_once ADMIN_VIEW . "/includes/footer.php";
	}
}