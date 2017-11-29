<?php
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();
  if ($login_class->get_user() == "administrator") {
    require_once("./html/php/admin.php");
    $admin = new Admin($_SESSION['login']);
    if (isset($_POST["Submit"])) {
      $admin->create_rule();
    }
?>


<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS - Vytvor pravidlo</title>

    <!-- Bootstrap core CSS -->

    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./html/template.css" rel="stylesheet">
</head>

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
              <a class="nav-link" href="list-subj.php">Zoznam predmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-field.php">Študíjne obory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-rules.php">Pravidlá registrácií</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="user-mntc.php">Správa účtov</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Profil</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="./html/php/logout.php">Odhlásiť</a>
            </li>
          </ul>
        </nav>


        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Vytvorenie pravidla</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Vyplnťe informácie o pravidle
          </span>
          <h2>Nové pravidlo</h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-3 required" >
                <label for="inputEmail4">Počet kreditov k ukončeniu štúdia</label>
                <input type="number" class="form-control" id="inputEmail4" placeholder="60" name="kredity" value="<?php if (isset($_POST['kredity'])) echo $_POST["kredity"];?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inputPassword4">Maximálny počet registrácií</label>
                <input type="number" class="form-control" id="inputPassword4" placeholder="12" name="pocet" value="<?php if (isset($_POST['pocet'])) echo $_POST["pocet"];?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3 required">
                <label for="inputAddress">ročný kreditový strop štúdia</label>
                <input type="number" class="form-control" id="inputAddress" placeholder="75" name="strop" value="<?php if (isset($_POST['strop'])) echo $_POST["strop"];?>" required>
              </div>
            </div>
            <button type="submit" name="Submit" class="btn btn-primary">Uložiť</button>
          </form>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body>
</html>
<?php
} else {
  header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/");
}
?>