<!DOCTYPE html>
<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS-prihlasovanie do predmetov</title>

    <!-- Bootstrap core CSS -->

    <!-- <script src="./bootstrap/js/bootstrap.min.js"></script> -->
	<!-- <script src="./html/bootstrap/js/bootstrap.min.js"></script> -->
	<link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./html/template.css" rel="stylesheet">

</head>

<?php
session_start();
ini_set("default_charset", "utf-8");
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
	$login_class = new Login($_SESSION['login']);
	$login_class->check_session();
	// echo "<pre>" . $login_class->get_user() . "\n</pre>";
	// TODO rozdel medzi administratora, garanta a studenta
	if ($login_class->get_user() == "student") {
		$student = new Student($_SESSION['login']);
		if (isset($_POST["Submit"])) {
			$student->change_information();
		}
?>


<body data-gr-c-s-loaded="true" style="">
    <div class="container-fluid" style="">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-slack sidebar" style="">
            <div style="">
                <div style="">
                    <span>WISv0.5</span>
                </div>
            </div>
          <ul class="nav nav-pills  flex-column">
            <li class="nav-item ">
              <a class="nav-link" href="#">Registrácia pedmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Zapísané predmety</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Registrácia odboru</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Prehľad študia</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="#">Nastavenia</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="#">Odhlásiť</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Profil</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete upraviť svoje osobné informácie.
          </span>
          <h2>Osobné informácie</h2>
          <form method=POST>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email">
              </div>
              <div class="form-group col-md-6">
                <label for="inputPassword4">Heslo</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Heslo" name="heslo">
              </div>
            </div>
            <div class="form-group">
              <label for="inputAddress">Adresa</label>
              <input type="text" class="form-control" id="inputAddress" placeholder="Ulica a popisné číslo" name="adresa">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputCity">Mesto</label>
                <input type="text" class="form-control" id="inputCity" name="mesto">
              </div>
              <div class="form-group col-md-4">
                <label for="inputState">Kraj</label>
                <select id="inputState" class="form-control">
                  <option selected>Trnavský</option>
                  <option>...</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="inputZip">PSČ</label>
                <input type="text" class="form-control" id="inputZip" name="psc">
              </div>
            </div>
            <button type="submit" class="btn btn-primary" name="Submit">Uložiť</button>
          </form>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>
<?php
	}
}
?>
</html>