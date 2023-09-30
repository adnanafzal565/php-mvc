<?php

    class HomeController extends Controller
    {
        public function index($message = "")
        {
            require_once VIEW . "/includes/header.php";
            require_once VIEW . "/index.php";
            require_once VIEW . "/includes/footer.php";
        }
    }