                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal" id="logoutModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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

    <!-- Page level plugins -->
    <!-- <script src="<?php //echo PUBLIC_PATH; ?>/admin/vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="<?php //echo PUBLIC_PATH; ?>/admin/js/demo/chart-area-demo.js"></script> -->
    <!-- <script src="<?php //echo PUBLIC_PATH; ?>/admin/js/demo/chart-pie-demo.js"></script> -->

    <script>
        async function getUser() {
            const response = await axios.post(
                BASE_URL + "/user",
                null,
                {
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("accessToken")
                    }
                }
            )

            if (response.data.status == "success") {
                window.user = response.data.user

                if (window.user.type != "admin") {
                    window.location.href = BASE_URL + "/admin/login"
                }

                if (typeof isOnDashboard !== "undefined" && isOnDashboard) {
                    initDashboard()
                }

                if (typeof isOnHeader !== "undefined" && isOnHeader) {
                    initHeader()
                }
            } else {
                // swal.fire("Login", response.data.message, "error")
                window.location.href = BASE_URL + "/admin/login"
            }
        }

        getUser()
    </script>

</body>

</html>