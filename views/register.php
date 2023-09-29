<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
	<div class="row">
		<div class="offset-md-4 col-md-4">

			<h2 style="margin-bottom: 30px;">Register</h2>

			<form method="POST" enctype="multipart/form-data" onsubmit="doRegister()">
				<div class="form-group">
					<label>Enter name</label>
					<input type="text" name="name" class="form-control" required />
				</div>

				<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
					<label>Enter email</label>
					<input type="email" name="email" class="form-control" required />
				</div>

				<div class="form-group" style="margin-bottom: 20px;">
					<label>Enter password</label>
					<input type="password" name="password" class="form-control" required />
				</div>

				<button type="submit" name="submit" class="btn btn-primary">
					Register
					<i class="fa fa-spinner" id="loader" style="display: none;"></i>
				</button>
			</form>
		</div>
	</div>
</div>

<script>
	async function doRegister() {
		event.preventDefault()

		const form = event.target
		const formData = new FormData(form)
		
		form.submit.setAttribute("disabled", "disabled")
		$("#loader").show()

		const response = await axios.post(
			BASE_URL + "/user/register",
			formData
		)

		form.submit.removeAttribute("disabled")
		$("#loader").hide()

		if (response.data.status == "success") {
			swal.fire("Register", response.data.message, "success")
		} else {
			swal.fire("Register", response.data.message, "error")
		}
	}
</script>