<div class="container" style="margin-top: 50px;">
	<div class="row">

		<div class="offset-md-4 col-md-4">
			<h2 style="margin-bottom: 30px;">
				Reset password
			</h2>

			<form method="POST" onsubmit="resetPassword()">

				<input type="hidden" name="email" value="<?php echo $email; ?>" required />
				<input type="hidden" name="token" value="<?php echo $token; ?>" required />

		        <div class="form-group">
		            <label>New password</label>
		            <input type="password" class="form-control" name="new_password" required />
		        </div>

		        <br />

		        <div class="form-group">
		            <label>Confirm password</label>
		            <input type="password" class="form-control" name="confirm_password" required />
		        </div>

		        <br />

		        <button type="submit" name="submit" class="btn btn-primary">
					Reset
					<i class="fa fa-spinner" id="loader" style="display: none;"></i>
				</button>
			</form>
		</div>
	</div>
</div>

<script>
	async function resetPassword() {
		event.preventDefault()

		const form = event.target
		const formData = new FormData(form)
		
		form.submit.setAttribute("disabled", "disabled")
		$("#loader").show()

		const response = await axios.post(
			BASE_URL + "/reset-password/" + form.email.value + "/" + form.token.value,
			formData
		)

		form.submit.removeAttribute("disabled")
		$("#loader").hide()

		if (response.data.status == "success") {
			swal.fire("Reset password", response.data.message, "success")
				.then(function () {
					window.location.href = BASE_URL + "/login"
				})
		} else {
			swal.fire("Reset password", response.data.message, "error")
		}
	}
</script>