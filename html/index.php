<!DOCTYPE html>
<head>
<script src="./bootstrap/js/bootstrap.min.js"></script>
<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
	<?php
	if (isset($_GET["input_email"])) {
		require_once('php/class.php');
		$login_class = new Login();
		$login_class->show_attributes();
		die();
	}
	?>
<body background="../img/clouds.jpg" style="background-repeat: no-repeat;background-size: 100%;">
	<div class="container" style="max-width: 300px; margin-top: 10%; margin-bottom:10%;">
		<form>
			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="input_email">
				<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
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
