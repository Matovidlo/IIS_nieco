<?php
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();


  if ($login_class->get_user() == "student") {
    $student = new Student($_SESSION['login']);
    if(isset($_POST["Submit"])) {
      $student->change_register_subject();
      // header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/reg-subj.php");
    }
?>
<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS - registrácia predmetov</title>

    <!-- Bootstrap core CSS -->

  <link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./html/template.css" rel="stylesheet">
   <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
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
              <a class="nav-link active" href="#">Registrácia pedmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-subj.php">Zapísané predmety</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-study.php">Prehľad študia</a>
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
          <h1>Registrácia predmetov</h1>
          <span class=".text-left" style="width: 45%; display:block; margin-bottom: 15px;">
            V nasledujúcej ponuke si možete zaregistrovať či poprípade odregistrovať predmety. Pre úspešné zvládnutie semestru je potrebné získať aspoň 15 kreditov. Pre postup do ďalšieho ročníka potrebujete za tento akademický rok získať aspoň 30 kreditov.
          </span>
          <h2>Prehľad všetkých predmetov</h2>
          <form method="POST">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th colspan="8">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                  <th>Registrovaný</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Show winter subjects register for current Academic year
                  $student->show_subj_register();
                ?>
              </tbody>
            </table>
            <br>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th colspan="8">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                  <th>Registrovaný</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  // Summer subject registration list
                  $student->show_subj_register("Letny");
                ?>
              </tbody>
            </table>
            <br>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Povinné</th>
                  <th>Povinno-voliteľné</th>
                  <th>Voliteľne</th>
                  <th>Celkovo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                    //TODO
                    $student->show_subj_count();
                  ?>
                </tr>
              </tbody>
            </table>
            <button type="submit" name="Submit" class="btn btn-primary" style="margin: 0 5px 15px 0; float:right;">Potvrď</button>
          </div>
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