<div id="dashboardApp" style="display: contents;">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" v-text="users"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const isOnDashboard = true

    function initDashboard() {
        Vue.createApp({

            data() {
                return {
                    users: 0
                }
            },

            async mounted() {
                const response = await axios.post(
                    BASE_URL + "/admin/stats",
                    null,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem("accessToken")
                        }
                    }
                )

                if (response.data.status == "success") {
                    this.users = response.data.users
                } else {
                    swal.fire("Login", response.data.message, "error")
                }
            }
        }).mount("#dashboardApp")
    }
</script>