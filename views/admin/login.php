<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo APP_NAME; ?></title>

        <!-- Custom fonts for this template-->
        <link href="<?php echo PUBLIC_PATH; ?>/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="<?php echo PUBLIC_PATH; ?>/admin/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- <script src="<?php echo JS; ?>/jquery.js"></script> -->
        <script src="<?php echo JS; ?>/jquery-3.3.1.min.js"></script>
        <script src="<?php echo PUBLIC_PATH; ?>/assets/js/bootstrap.min.js"></script>
        <!-- <script src="<?php //echo JS; ?>/bootstrap.js"></script> -->
        <script src="<?php echo JS; ?>/axios.min.js"></script>
        <script src="<?php echo JS; ?>/sweetalert2@11.js"></script>
        <!-- <script src="<?php //echo JS; ?>/all.js"></script> -->
        <script src="<?php echo JS; ?>/vue.global.js"></script>
        <!-- <script src="<?php echo JS; ?>/vue.global.prod.js"></script> -->

        <script src="<?php echo PUBLIC_PATH; ?>/admin/js/script.js?v=<?php echo time(); ?>"></script>

    </head>

    <body class="bg-gradient-primary">

        <input type="hidden" id="BASE_URL" value="<?php echo URL; ?>" />

        <script>
            window.user = null
            const BASE_URL = document.getElementById("BASE_URL").value
            const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
        </script>

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-md-6">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Admin Login!</h1>
                                        </div>
                                        <form class="user" onsubmit="doLogin()">
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control form-control-user"
                                                    id="exampleInputEmail" aria-describedby="emailHelp"
                                                    placeholder="Enter Email Address...">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Password">
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                                Login

                                                <i class="fa fa-spinner" id="loader" style="display: none;"></i>
                                            </button>
                                            <hr>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="<?php echo PUBLIC_PATH; ?>/admin/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo PUBLIC_PATH; ?>/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?php echo PUBLIC_PATH; ?>/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?php echo PUBLIC_PATH; ?>/admin/js/sb-admin-2.min.js"></script>

        <script>
            async function doLogin() {
                event.preventDefault()

                const form = event.target
                const formData = new FormData(form)
                
                form.submit.setAttribute("disabled", "disabled")
                $("#loader").show()

                const response = await axios.post(
                    BASE_URL + "/user/login",
                    formData
                )

                form.submit.removeAttribute("disabled")
                $("#loader").hide()

                if (response.data.status == "success") {
                    localStorage.setItem("accessToken", response.data.token)
                    window.location.href = BASE_URL + "/admin"
                } else {
                    swal.fire("Login", response.data.message, "error")
                }
            }
        </script>

    </body>

</html>