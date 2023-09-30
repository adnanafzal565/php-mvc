<ul class="list-group" id="left-menu-profile">
	<li class="list-group-item">
		<a href="<?php echo URL; ?>/user/profile">My profile</a>	
	</li>

	<li class="list-group-item">
		<a href="<?php echo URL; ?>/user/change_password">Change password</a>
	</li>
</ul>

<style>
	.list-group-item.active a {
		color: white;
	}
	.list-group-item a {
		text-decoration: none;
	}
</style>

<script>
	const li = document.querySelectorAll("#left-menu-profile li")
	for (let a = 0; a < li.length; a++) {
		li[a].className = "list-group-item"

		const anchor = li[a].querySelector("a")

		if (anchor.getAttribute("href") == window.location.href) {
			li[a].className = "list-group-item active"
		}
	}
</script>