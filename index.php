<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<script src="./html/bootstrap/js/bootstrap.min.js"></script>
<link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

	<?php
	if (isset($_POST["input_login"]) && isset($_POST["input_password"])) {
		require_once('./html/php/class.php');
		$login_class = new Login();

		if (!$login_class->compare_password()) {
			// TODO swal
		} else {
			//subtract new timestamp from the old one
			if(time() - $_SESSION['timestamp'] > 900) {
			    echo"<script>alert('15 Minutes over!');</script>";
			    unset($_SESSION['username'], $_SESSION['password'], $_SESSION['timestamp']);
			    $_SESSION['logged_in'] = false;
			    header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/"); //redirect to index.php
			} else {
			    $_SESSION['timestamp'] = time(); //set new timestamp
			    set($_SESSION['input_login'], $_SESSION['input_password'], $_SESSION['timestamp']);
			}
		}
		die();
	}
	?>
<body background="./img/clouds.jpg" style="background-repeat: no-repeat;background-size: 100%;">
	<div class="container" style="max-width: 300px; margin-top: 10%; margin-bottom:10%;">
		<form method=POST>
			<div class="form-group">
				<label for="exampleInputLogin">Login</label>
				<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter login" name="input_login">
				<small id="emailHelp" class="form-text text-muted">We'll never share your login with anyone else.</small>
			</div>

			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="input_password">
			</div>


			<!-- TODO reload page with new one
				<div class="form-check">
				<label class="form-check-label">
				<input type="checkbox" class="form-check-input">
				Check me out
				</label>
			</div> -->
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
</body>
