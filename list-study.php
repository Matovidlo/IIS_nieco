<?php
  session_start();
  ini_set("default_charset", "utf-8");
  $str_time = time();
  $_SESSION['timestamp'] = $str_time;
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->check_session();
  if ($login_class->get_user() == "student") {
?>
<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS - prehľad študia</title>

    <!-- Bootstrap core CSS -->

    <!-- <script src="./bootstrap/js/bootstrap.min.js"></script> -->
    <link href="./html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./html//template.css" rel="stylesheet">
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
              <a class="nav-link" href="reg-subj.php">Registrácia pedmetov<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-subj.php">Zapísané predmety</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Prehľad študia</a>
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
          <h1>Zapísané predmety</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Na tejto stránke môžete videť všetky aktuálne zapísané predmety v ak. roku 2017/2018.
          </span>
          <h2>Prehľad zapísaných predmetov</h2>
          <div class="table-responsive">
            <h3>Rok 1</h3>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-responsive">
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
                  <td>35</td>
                  <td>3</td>
                  <td>25</td>
                  <td>63</td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="table-responsive">
            <h3>Rok 2</h3>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-responsive">
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
                  <td>35</td>
                  <td>3</td>
                  <td>25</td>
                  <td>63</td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="table-responsive">
            <h3>Rok 3</h3>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Zimný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-striped  table-hover">
              <thead>
                <tr>
                  <th colspan="7">Letný semester</th>
                </tr>
                <tr>
                  <th>Skratka</th>
                  <th>Typ</th>
                  <th>Kredity</th>
                  <th>Nazov</th>
                  <th>Fakulta</th>
                  <th>Max</th>
                  <th>Zapísaných</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
                <tr>
                  <td>IIS</td>
                  <td>P</td>
                  <td>4</td>
                  <td>Informačné systémy</td>
                  <td>FIT</td>
                  <td>500</td>
                  <td>469</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table table-responsive">
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
                  <td>35</td>
                  <td>3</td>
                  <td>25</td>
                  <td>63</td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="table-striped">
            <h3>Celkovo</h3>
            <table class="table table-responsive">
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
                  <td>35</td>
                  <td>3</td>
                  <td>25</td>
                  <td>63</td>
                </tr>
              </tbody>
            </table>
          </div>
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
  die("Pristup zamedzeny! Nedostatocne prava");
}
?>