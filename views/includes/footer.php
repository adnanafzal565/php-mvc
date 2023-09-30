
		<script>
			async function getUser() {
				const response = await axios.post(
					BASE_URL + "/user/me",
					null,
					{
						headers: {
							Authorization: "Bearer " + localStorage.getItem("accessToken")
						}
					}
				)

				if (response.data.status == "success") {
					window.user = response.data.user

					if (typeof isOnChangePassword !== "undefined" && isOnChangePassword) {
						initChangePassword()
					}

					if (typeof isOnProfile !== "undefined" && isOnProfile) {
						initProfile()
					}
				} else {
					// swal.fire("Login", response.data.message, "error")
				}

				if (typeof isOnHeader !== "undefined" && isOnHeader) {
					initHeader()
				}
			}

			getUser()
		</script>

	</body>
</html>