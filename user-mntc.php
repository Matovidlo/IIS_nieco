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
      $admin->edit_user();
    }
    if (isset($_GET["login"])) {
      $admin->delete_user();
    }
?>

<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS - Prehľad užívateľov</title>

    <!-- Bootstrap core CSS -->

    <!-- <script src="./bootstrap/js/bootstrap.min.js"></script> -->
    <link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./html/template.css" rel="stylesheet">
    <script src="./html/bootstrap/js/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./html/bootstrap/css/sweetalert.css">
</head>

<body data-gr-c-s-loaded="true" style="">
    <div class="container-fluid" style="">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-slack sidebar" style="">
            <div style="">
                <div style="">
                  <img src="../img/logo_sh.png" style="height: 85px;">
                </div>
            </div>
          <ul class="nav nav-pills  flex-column">
            <li class="nav-item ">
              <a class="nav-link " href="list-subj.php">Zoznam predmetov<span class="sr-only">(current)</span></a>
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
              <a class="nav-link active" href="user-mntc.php">Správa účtov</a>
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
          <h1>Zoznam všetkých používateľov</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete videť všetkých používateľov.
          </span>
          <h2>Vytvoriť účet</h2>
            <button type="button" onclick="window.location.href='create-user.php'" class="btn btn-success" >Vytvor účet</button>
          <br>
          <br>

          <h2>Prehľad používateľov</h2>
          <div class="table-responsive">
            <form method="POST">
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th>Login</th>
                  <th>Meno</th>
                  <th>Email</th>
                  <th>Mesto</th>
                  <th>Typ účtu</th>
                  <th>Registrovaný od</th>
                  <th>Posledná zmena</th>
                  <th>Akcie</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $admin->show_users();
                ?>
              </tbody>
            </table>
          </div>
          <button type="submit" name="Submit" class="btn btn-secondary">Uložiť zmeny</button>
          <br>
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
  header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/index.php");
}
?>