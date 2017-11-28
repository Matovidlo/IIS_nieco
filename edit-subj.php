<?php
  session_start();
  require_once('./html/php/class.php');
  // if
  $login_class = new Login($_SESSION['login']);
  $login_class->init_session();
  if ($login_class->get_user() == "garant") {
    require_once("./html/php/garant.php");
    $garant = new Garant($_SESSION['login']);
    if (isset($_POST["Submit"])) {
      $garant->edit_subject();
    }
?>

<html lang="en" class="gr__getbootstrap_com">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IIS - Zmeň predmet</title>

    <!-- Bootstrap core CSS -->

    <!-- <script src="./bootstrap/js/bootstrap.min.js"></script> -->
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
          <h1>Editácia predmetu</h1>
          <span class=".text-left" style="margin-bottom: 15px; display: block;">
            Úprava špecifikácie predmetu.
          </span>
          <h2>
            <?php
            if(isset($_GET["skr"]) && isset($_GET["rok"])) {
              echo $_GET["skr"];
            }
            else {
              header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/list-subj.php");
            }
            $info = $garant->get_subject_info();
            ?>

          </h2>
          <form method="POST">
            <div class="form-row">
              <div class="form-group col-md-1">
                <label for="inputEmail4">Skratka</label>
                <input type="text" class="form-control" id="inputEmail4" placeholder="XXX" value="<?php echo $info["Skratka_predmetu"];?>" name="skratka" required>
              </div>
              <div class="form-group col-md-4">
                <label for="inputPassword4">Názov</label>
                <input type="text" class="form-control" id="inputPassword4" placeholder="Informačné systémy" value="<?php echo $info["Nazov"];?>" name="nazov" required>
              </div>
              <div class="form-group col-md-1">
                <label for="inputAddress">Rok</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="XXXX" value="<?php echo $info["Ak_rok"];?>" name="rok">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-1">
                <label for="inputAddress">Kredity</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="4" value="<?php echo $info["Pocet_kreditov"];?>" name="kredity" required>
              </div>
              <div class="form-group col-md-1">
                <label for="inputAddress">Kapacita</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="500" name="cap" value="<?php echo $info["Limit_prihlasenych"];?>" required>
              </div>
              <div class="form-group col-md-1">
                <label for="inputState">Fakulta</label>
                <select id="inputState" class="form-control" name="faculty">
                  <option value="FIT" selected>FIT</option>
                  <option value="FP">FP</option>
                  <option value="FEKT">FEKT</option>
                  <option value="FSI">FSI</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label for="inputState">Typ</label>
                <select id="inputState" class="form-control" name="typ">
                  <option value="P" selected>Povinný</option>
                  <option value="PV">Povinno-voliteľný</option>
                  <option value="V">Voliteľný</option>
                </select>
              </div>
              <div class="form-group col-md-1">
                <label for="inputPassword4">Študijný odbor</label>
                <select id="inputState" class="form-control" name="odbor">
                  <!-- TODO na zaklade dotazu pridat options dokoncit selected -->
                  <option value="BIT" selected>BIT</option>
                  <option value="BGR">BGR</option>
                  <option value="MIT">MIT</option>
                  <option value="MBI">MBI</option>
                  <option value="MIM">MIM</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="inputEmail4">Semester</label>
                <select id="inputState" class="form-control" name="semester">
                  <option value="Zimny" selected>Zimný</option>
                  <option value="Letny">Letný</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label for="inputEmail4">Rocnik</label>
                <select id="inputState" class="form-control" name="rocnik">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
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