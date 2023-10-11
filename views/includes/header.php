<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo APP_NAME; ?></title>

		<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>/all.css" />

		<script src="<?php echo JS; ?>/jquery.js"></script>
		<script src="<?php echo JS; ?>/bootstrap.js"></script>
		<script src="<?php echo JS; ?>/axios.min.js"></script>
		<script src="<?php echo JS; ?>/sweetalert2@11.js"></script>
		<script src="<?php echo JS; ?>/all.js"></script>
		<script src="<?php echo JS; ?>/vue.global.prod.js"></script>
	</head>

	<body>

		<input type="hidden" id="BASE_URL" value="<?php echo URL; ?>" />

		<script>
			window.user = null
			const BASE_URL = document.getElementById("BASE_URL").value
		</script>

		<nav class="navbar navbar-expand-lg navbar-light bg-light" id="headerApp">
			<div class="container-fluid">
				<a class="navbar-brand" href="<?php echo URL; ?>"><?php echo APP_NAME; ?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="<?php echo URL; ?>">Home</a>
						</li>

						<template v-if="user == null">
							<li class="nav-item">
								<a class="nav-link" href="<?php echo URL; ?>/user/login">Login</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo URL; ?>/user/register">Register</a>
							</li>
						</template>

						<li class="nav-item dropdown" v-else>
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
								v-text="user.name"></a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<li><a class="dropdown-item" href="<?php echo URL; ?>/user/profile">Profile</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" v-on:click="doLogout" href="javascript:void(0)">Logout</a></li>
							</ul>
						</li>
					</ul>
					<!-- <form class="d-flex">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
						<button class="btn btn-outline-success" type="submit">Search</button>
					</form> -->
				</div>
			</div>
		</nav>

		<script>
			const isOnHeader = true

			function initHeader() {
				Vue.createApp({
					data() {
						return {
							user: window.user
						}
					},

					methods: {
						async doLogout() {
							const response = await axios.post(
								BASE_URL + "/user/logout",
								null,
								{
									headers: {
										Authorization: "Bearer " + localStorage.getItem("accessToken")
									}
								}
							)

							if (response.data.status == "success") {
								this.user = null
								window.user = null
								localStorage.removeItem("accessToken")
								window.location.reload()
							} else {
								// swal.fire("Login", response.data.message, "error")
							}
						}
					}
				}).mount("#headerApp")
			}
		</script>