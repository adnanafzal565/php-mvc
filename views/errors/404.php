<?php if (isset($message)): ?>

	<div class="container" style="margin-bottom: 50px;">
		<div class="row">
			<div class="offset-md-3 col-md-6">
				<img src="<?= URL . "/" . VIEW; ?>/errors/images/banner.png"
					style="position: relative;
						left: 50%;
						transform: translateX(-50%);" />

				<h2 class="text-center" style="margin-top: 20px;"><?php echo $message; ?></h2>
			</div>
		</div>
	</div>

<?php else:	?>

	<!DOCTYPE HTML>
	<html>
		<head>
			<title><?php echo APP_NAME; ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
			<link rel="stylesheet" href="<?= URL . "/" . VIEW; ?>/errors/css/style.css" />
		</head>

		<body>
			<div class="wrap">
				<h1><?php echo APP_NAME; ?></h1>
				<div class="banner">
					<img src="<?= URL . "/" . VIEW; ?>/errors/images/banner.png" alt="" />
				</div>
				<div class="page">
					<h2>Sorry, we can't find that page!</h2>
				</div>
			</div>
		</body>
	</html>

<?php endif; ?>