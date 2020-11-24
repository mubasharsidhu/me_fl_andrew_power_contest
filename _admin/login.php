<?php
session_start();

	require_once('../_core/_functions.php');

	if(isset($_SESSION['_logged_admin'])) {	
		header('location: index.php');	
		die();
	}

	if(isset($_POST['submit']) && isset($_POST['login_user']) && isset($_POST['login_pass'])) {

		$user = mysqli_real_escape_string($db,$_POST['login_user']);
		$pass = mysqli_real_escape_string($db,$_POST['login_pass']);
		
		if($user && $pass) {
			$check = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = 'admin_user' AND `set_value` = '".$user."' LIMIT 1");
			if(mysqli_num_rows($check)) {
				$check_pass = mysqli_query($db,"SELECT * FROM `settings` WHERE `set_key` = 'admin_pass' AND `set_value` = '".hash('sha512',$pass)."' LIMIT 1");
				if(mysqli_num_rows($check_pass)) {
					$_SESSION['_logged_admin'] = 1;
					header('location: index.php');
					die();
				}
			}
		}

		$error_msg = 'Invalid credentials';
	
	}
?><!DOCTYPE HTML>
<html>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">

	<link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@100;900&family=Roboto:wght@300;400;700;900&family=Satisfy&display=swap">
	<link rel="stylesheet" media="all" href="css/login.css" />

</head>

<body>

	<div class="admin_login_b">
	
		<div class="admin_login_box_sub">

			<div class="admin_login_logo">Contest Platform</div>
			<div class="admin_login_version">version 1.4.2</div>
	
			<?php if(isset($error_msg) && $error_msg) { ?>
			<div class="admin_error"><?=$error_msg;?></div>
			<?php } ?>

			<div class="admin_login_form">

				<form action="" method="post">

					<div class="pad5">
						<input name="login_user" type="text" placeholder="Username" class="admin_login_user" />
					</div>

					<div class="pad5">
						<input name="login_pass" type="password" placeholder="Password" class="admin_login_pass" />
					</div>

					<div class="pad5plus">
						<button type="submit" name="submit" class="admin_login_submit">
							<i class="fas fa-check"></i>&nbsp;&nbsp;Login
						</button>
					</div>
	
				</form>

			</div>

		</div>

	</div>

	<script type="text/javascript" src="../_js/all.min.js"></script>

</body>
</html>