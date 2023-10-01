<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
	<div class="row">
		<div class="offset-md-4 col-md-4">

			<h2 style="margin-bottom: 30px;">Forgot password</h2>

			<form method="POST" enctype="multipart/form-data" onsubmit="sendRecoveryEmail()">
				<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
					<label>Enter email</label>
					<input type="email" name="email" class="form-control" required />
				</div>

				<button type="submit" name="submit" class="btn btn-primary">
					Send recovery email
					<i class="fa fa-spinner" id="loader" style="display: none;"></i>
				</button>
			</form>

			<p style="margin-top: 10px;">
				<a href="<?php echo URL; ?>/user/login">Login</a>
			</p>
		</div>
	</div>
</div>

<script>
	async function sendRecoveryEmail() {
		event.preventDefault()

		const form = event.target
		const formData = new FormData(form)
		
		form.submit.setAttribute("disabled", "disabled")
		$("#loader").show()

		const response = await axios.post(
			BASE_URL + "/user/send_recovery_email",
			formData
		)

		form.submit.removeAttribute("disabled")
		$("#loader").hide()

		if (response.data.status == "success") {
			swal.fire("Forgot password", response.data.message, "success")
		} else {
			swal.fire("Forgot password", response.data.message, "error")
		}
	}
</script>