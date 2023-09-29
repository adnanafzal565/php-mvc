<?php

    class HomeController extends Controller
    {
        public function index($message = "")
        {
            require_once VIEW . "/header.php";
            require_once VIEW . "/index.php";
            require_once VIEW . "/footer.php";
        }
    }