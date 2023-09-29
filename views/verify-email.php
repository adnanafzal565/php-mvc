<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
	<div class="row">
		<div class="offset-md-4 col-md-4">

			<h2 style="margin-bottom: 30px;">Verify email</h2>

			<form method="POST" enctype="multipart/form-data" onsubmit="doVerify()">
				<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
					<label>Enter email</label>
					<input type="email" name="email" value="<?php echo $email; ?>" class="form-control" required />
				</div>

				<div class="form-group" style="margin-bottom: 20px;">
					<label>Enter verification code</label>
					<input type="text" name="code" class="form-control" required />
				</div>

				<button type="submit" name="submit" class="btn btn-primary">
					Verify
					<i class="fa fa-spinner" id="loader" style="display: none;"></i>
				</button>
			</form>
		</div>
	</div>
</div>

<script>
	async function doVerify() {
		event.preventDefault()

		const form = event.target
		const formData = new FormData(form)
		
		form.submit.setAttribute("disabled", "disabled")
		$("#loader").show()

		const response = await axios.post(
			BASE_URL + "/user/verify",
			formData
		)

		form.submit.removeAttribute("disabled")
		$("#loader").hide()

		if (response.data.status == "success") {
			swal.fire("Verify email", response.data.message, "success")
		} else {
			swal.fire("Verify email", response.data.message, "error")
		}
	}
</script>