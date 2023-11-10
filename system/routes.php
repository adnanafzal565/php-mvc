<?php

// add_route("/blog/detail/:id/:sub_id", "UserController", "test_blog_detail");
// add_route("/user/profile/:id", "UserController", "test_profile");
// add_route("/my", "UserController", "test_my");

add_route("/", "HomeController", "index");

add_route("/register", "UserController", "register");
add_route("/login", "UserController", "login");
add_route("/user", "UserController", "me");
add_route("/profile", "UserController", "profile");
add_route("/save-profile", "UserController", "save_profile");
add_route("/logout", "UserController", "logout");

add_route("/verify-email/:email", "UserController", "verify_email");
add_route("/verify", "UserController", "verify");

add_route("/forgot-password", "UserController", "forgot_password");
add_route("/send-recovery-email", "UserController", "send_recovery_email");
add_route("/reset-password/:email/:token", "UserController", "reset_password");
add_route("/change-password", "UserController", "change_password");

add_route("/admin", "AdminController", "index");
add_route("/admin/login", "AdminController", "login");
add_route("/admin/stats", "AdminController", "stats");