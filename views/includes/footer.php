
		<script>
			async function getUser() {
				const response = await axios.post(
					BASE_URL + "/user",
					null,
					{
						headers: {
							Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
						}
					}
				)

				if (response.data.status == "success") {
					user = response.data.user

					if (typeof isOnChangePassword !== "undefined" && isOnChangePassword)
						initChangePassword()

					if (typeof isOnProfile !== "undefined" && isOnProfile)
						initProfile()
				} else {
					// swal.fire("Login", response.data.message, "error")
				}

				if (typeof isOnHeader !== "undefined" && isOnHeader) {
					initHeader()
					headerApp.user = user
				}

				if (typeof initApp !== "undefined" && initApp)
					initApp()
			}

			getUser()
		</script>

	</body>
</html>