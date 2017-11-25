<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<script src="./html/bootstrap/js/bootstrap.min.js"></script>
<link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php
session_start();
require_once('./html/php/class.php');
require_once("./html/php/session.php"); // creates $login_class class
if (!isset($_SESSION['login']) && !isset($_SESSION['logged_in'])) {
?>
<body>
	<div style="
	    background-image: url(./img/bg.png);
	    display: block;
	    width: 100%;
	    height: 100%;
	    position: absolute;
	    z-index: -1;
	    top: 0;
	    background-repeat: no-repeat;
	    background-size: 100%;
	    filter: blur(10pt);
	    -webkit-filter: blur(10pt);
	"></div>
	<div class="container" style="max-width: 300px; margin-top: 10%; margin-bottom:10%;">
		<form method=POST>
			<div class="form-group">
				<label for="exampleInputLogin">Login</label>
				<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter login" name="input_login" value="<?php if($save === 1) echo $_POST["input_login"];?>">
				<small id="emailHelp" class="form-text text-muted">We'll never share your login with anyone else.</small>
			</div>

			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="input_password">
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
</body>
<?php
} else {
	require_once('./html/php/class.php');
	$login_class = new Login();
	$login_class->check_session();
	// echo "<pre>" . $login_class->get_user() . "\n</pre>";
	// TODO rozdel medzi administratora, garanta a studenta
?>

<body background="./img/clouds.jpg" style="background-repeat: no-repeat;background-size: 100%;">
	<div class="container" style="max-width: 300px; margin-top: 10%; margin-bottom:10%;">
		<form method=POST>
			<!-- TODO reload page with new one -->
			<div class="form-check">
				<label class="form-check-label">
				<input type="checkbox" class="form-check-input">
				Check me out
				</label>
			</div>
			<button type="submit" class="btn btn-primary">Press</button>
		</form>
	</div>
</body>

<?php
}
?>