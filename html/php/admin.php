<?php
require_once('./html/php/garant.php');

class Admin extends Garant{
	private $mysql;
	private $login;

	public function __construct($login)
	{
		require_once("./database/mysql_init.php");
		$this->login = $login;
		$this->mysql = new mysql_class();
		$this->mysql = $this->mysql->get_status();
	}

	public function generate_division() {
		$query = "SELECT * FROM Studijny_program";
		$result = mysqli_query($this->mysql, $query);
		$curr = 0;
		if ($result->num_rows > 0) {
			while( $row = $result->fetch_assoc()) {
				if (($curr % 3) === 0) {
					echo <<<EOL
					<div class="row">
						<div class="col">
						<div class="card">
							<h4 class="card-header">{$row["Odbor"]}</h4>
							<div class="card-body">
								<h4 class="card-title">Detaily</h4>
								<h6 class="card-subtitle mb-2 text-muted"><b>Typ:</b> {$row["Typ_studia"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Skratka programu:</b> {$row["Skratka_programu"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Akreditácia do:</b> {$row["Akreditacia"]}</h6>
								<p class="card-text">{$row["Popis"]}.</p>
								<a href="edit-field.php?skr={$row["Skratka_programu"]}&rok={$row["Ak_rok"]}" class="btn btn-secondary">Upraviť</a>
								<button type="button" onclick="{$row['Skratka_programu']}_{$row["Ak_rok"]}()" class="btn btn-danger" >Vymaž</button>
							</div>
						 </div>
					</div>
					<script>
        function {$row['Skratka_programu']}_{$row["Ak_rok"]}() {
        	var redirect = "list-field.php?prog={$row['Skratka_programu']}&rok={$row["Ak_rok"]}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
				} else {
					echo <<<EOL
						<div class="col">
						<div class="card">
							<h4 class="card-header">{$row["Odbor"]}</h4>
							<div class="card-body">
								<h4 class="card-title">Detaily</h4>
								<h6 class="card-subtitle mb-2 text-muted"><b>Typ:</b> {$row["Typ_studia"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Skratka programu:</b> {$row["Skratka_programu"]}</h6>
								<h6 class="card-subtitle mb-2 text-muted"><b>Akreditácia do:</b> {$row["Akreditacia"]}</h6>
								<p class="card-text">{$row["Popis"]}.</p>
								<a href="edit-field.php?skr={$row["Skratka_programu"]}&rok={$row["Ak_rok"]}" class="btn btn-secondary">Upraviť</a>
								<button type="button" onclick="{$row['Skratka_programu']}_{$row["Ak_rok"]}()" class="btn btn-danger" >Vymaž</button>
							</div>
						 </div>
						 </div>
						 <script>
        function {$row['Skratka_programu']}_{$row["Ak_rok"]}() {
        	var redirect = "list-field.php?prog={$row['Skratka_programu']}&rok={$row["Ak_rok"]}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
				}
				if (($curr % 3) === 2) {
					echo "</div><br>";
				}
				$curr++;
			}
		}
	}

	public function delete_program()
	{
		$program = mysqli_real_escape_string($this->mysql, $_GET["prog"]);
		$rok = mysqli_real_escape_string($this->mysql, $_GET["rok"]);

		// DELETE students, osoba, and predmet
		$query = "SELECT Login FROM Student NATURAL JOIN Osoba WHERE Student.Skratka_programu='$program' AND Student.Ak_rok=$rok ";
		$result = mysqli_query($this->mysql, $query);

		while($row = $result->fetch_assoc()) {
			print_r($row["Login"]);
			$query = "DELETE FROM Zamestnanec WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);

			$query = "DELETE FROM Prihlasuje WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);


			$query = "DELETE FROM Student WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);
			if ($qry) {
				// echo "Success";
			} else {
				// echo "Failure";
			}

			$query = "DELETE FROM Osoba WHERE Login='" . $row["Login"] . "'";
			$qry = mysqli_query($this->mysql, $query);
			if ($qry) {
				// echo "Success";
			} else {
				// echo "Failure";
			}
		}
		// Odstran vsetkych co su mimo oborovy
		$query = "SELECT Skratka_predmetu FROM Predmet WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		$vysl = mysqli_query($this->mysql, $query);
		while($row= $vysl->fetch_assoc()) {
			$query = "DELETE FROM Prihlasuje WHERE Skratka_predmetu='" . $row["Skratka_predmetu"] ."'";
			$result = mysqli_query($this->mysql, $query);
		}

		$query = "DELETE FROM Predmet WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Studijny_program WHERE Skratka_programu='$program' AND Ak_rok=$rok";
		mysqli_query($this->mysql, $query);

	}

	public function show_users()
	{
		$query = "SELECT * FROM Osoba NATURAL JOIN Spravca";
		$this->user_query($query, "Spravca");
		$query = "SELECT * FROM Osoba NATURAL JOIN Zamestnanec WHERE Login NOT IN (SELECT Login FROM Osoba NATURAL JOIN Spravca)";
		$this->user_query($query);
		$query = "SELECT * FROM Osoba NATURAL JOIN Student";
		$this->user_query($query, "Student");

	}

	private function user_query($query, $type="Zamestnanec") {
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			if ($type == "Student") {
				echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student" selected>Študent</option>
							<option value="Garant" >Garant</option>
							<option value="Admin">Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
				<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
			} else if ($type == "Spravca") {
				echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student" >Študent</option>
							<option value="Garant" >Garant</option>
							<option value="Admin" selected>Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
					<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;

			} else {
			echo <<<EOL
					<tr>
					<td>{$row["Login"]}</td>
					<td>{$row["Meno"]}</td>
					<td>{$row["Email"]}</td>
					<td>{$row["Mesto"]}</td>
					<td>
						<select id="inputState" class="form-control" name="{$row["Login"]}">
							<option value="Student">Študent</option>
							<option value="Garant" selected>Garant</option>
							<option value="Admin">Admin</option>
						</select>
					</td>
					<td>26. 11. 2017 19:27:11</td>
	        <td>26. 11. 2017 19:27:11</td>
	        <td>
	          <button type="button" onclick="func{$row['Login']}()" class="btn btn-danger" >Vymaž</button>
	        </td>
					</tr>
					<script>
        function func{$row['Login']}() {
        	var redirect = "user-mntc.php?login={$row['Login']}";
        	var result = confirm("Want to delete?");
        	if (result) {
        		window.location.href=redirect;
        	}
        }
        </script>
EOL;
			}
		}
	}

	public function delete_user() {
		$login = mysqli_real_escape_string($this->mysql, $_GET["login"]);
		$query = "DELETE FROM Prihlasuje WHERE Login='$login'";
		mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Student WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);
		if ($result) {
			// echo "Success";
		} else {
			// echo "Failure";
		}

		$query = "DELETE FROM Spravca WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Zamestnanec WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);

		$query = "DELETE FROM Osoba WHERE Login='$login'";
		$result = mysqli_query($this->mysql, $query);
	}

	public function create_user()
	{
		if(!empty($_POST["meno"]) && !empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["heslo"]) && !empty($_POST["heslo_potvrd"]) && !empty($_POST["mesto"]) && !empty($_POST["psc"]) && !empty($_POST["typ"]) && !empty($_POST["rocnik"])) {

			$meno = mysqli_real_escape_string($this->mysql, $_POST["meno"]);
			$login = mysqli_real_escape_string($this->mysql, $_POST["login"]);
			$email = mysqli_real_escape_string($this->mysql, $_POST["email"]);
			$heslo = mysqli_real_escape_string($this->mysql, $_POST["heslo"]);
			$heslo_potvrd = mysqli_real_escape_string($this->mysql, $_POST["heslo_potvrd"]);
			$adresa = "";
			if (isset($_POST["adresa"]))
				$adresa = mysqli_real_escape_string($this->mysql, $_POST["adresa"]);
			$mesto = mysqli_real_escape_string($this->mysql, $_POST["mesto"]);
			$psc = mysqli_real_escape_string($this->mysql, $_POST["psc"]);
			$typ = mysqli_real_escape_string($this->mysql, $_POST["typ"]);
			$rocnik = mysqli_real_escape_string($this->mysql, $_POST["rocnik"]);
			$semester = 1 + (($rocnik - 1) * 2);

			if ($heslo != $heslo_potvrd) {
				break;
			}
			$heslo = base64_encode(hash("sha256", $heslo, true));
			$query = "INSERT INTO Osoba VALUES('$login', '$email', '$meno', '$heslo' , '$adresa', '$mesto', '$psc')";
			mysqli_query($this->mysql, $query);

			if (!empty($_POST["odbor"])) {
				// Student
				$odbor = $_POST["odbor"];
				$odbor = explode("-", $odbor);
				$query = "INSERT INTO Student VALUES ('$login', $rocnik, '$semester', '$odbor[0]',  $odbor[1])";
				$result = mysqli_query($this->mysql, $query);
				if ($result) {
					// echo "Succes";
				} else {
					// echo "Fail";
				}
			} else if (!empty($_POST["ustav"])) {
				// Zamestnanec / Spravca
				$ustav = $_POST["ustav"];
				$vyucuje = $_POST["vyucuje"];
				$query = "INSERT INTO Zamestnanec VALUES ('$login', '$ustav', $vyucuje)";
				mysqli_query($this->mysql, $query);
				if ($typ == "admin") {
					$query = "INSERT INTO Spravca VALUES ('$login')";
					mysqli_query($this->mysql, $query);
				}
			}

		}
	}

	public function edit_user()
	{
		$query = "SELECT Login FROM Osoba";
		$result= mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$change = $_POST["{$row["Login"]}"];
			$query = "SELECT * FROM Student WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Student")) {
				continue;
			}
			$query = "SELECT * FROM Spravca WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Admin")) {
				continue;
			}
			$query = "SELECT * FROM Zamestnanec WHERE Login='" . $row["Login"] . "'";
			$vysledok = mysqli_query($this->mysql, $query);
			if ($this->change_user_permissions($change, $vysledok, "Garant")) {
				continue;
			}
		}
	}

	public function create_division()
	{
		if (!empty($_POST["skratka"]) && !empty($_POST["akredit"]) && !empty($_POST["doba"]) && !empty($_POST["garant"]) && !empty($_POST["pravidlo"])) {

			$skratka = mysqli_real_escape_string($this->mysql, $_POST["skratka"]);
			$akr = mysqli_real_escape_string($this->mysql, $_POST["akredit"]);
			$odbor = mysqli_real_escape_string($this->mysql, $_POST["odbor"]);
			$forma = mysqli_real_escape_string($this->mysql, $_POST["forma"]);
			$doba = mysqli_real_escape_string($this->mysql, $_POST["doba"]);
			$pravidlo = $_POST["pravidlo"];

			$typ = "Doktorandsky";
			if ($skratka[0] == 'M') {
				$typ = "Magistersky";
			} else if ($skratka[0] == 'B') {
				$typ = "Bakalarsky";
			}
			$query = "INSERT INTO Studijny_program VALUES ('$skratka', '$typ', '$odbor'," . date("Y") . ", $akr, $doba, '$forma', $pravidlo, '')";
			$result = mysqli_query($this->mysql, $query);

		}
	}

	public function show_odbor()
	{
		$query = "SELECT Skratka_programu, Ak_rok FROM Studijny_program";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$prog = $row["Skratka_programu"];
			$rok = $row["Ak_rok"];
			echo "<option value='$prog-$rok'> $prog-$rok</option>";
		}
	}

	public function show_rules()
	{
		$query = "SELECT * FROM Pravidlo";
		$result = mysqli_query($this->mysql, $query);
		while($row = $result->fetch_assoc()) {
			$id = $row["Id_pravidla"];
			$pocet = $row["Pocet_kreditov"] . "K-";
			$strop = $row["Rocny_kreditovy_strop"] . "Strop";
			$name = "pravidlo " . $pocet . $strop;
			// echo $name;
			echo "<option value='$id'>$name</option>";
		}
	}


	private function change_user_permissions($change, $result, $old_permissions)
	{
		if ($result->num_rows > 0) {
			$data = mysqli_fetch_assoc($result);
			// echo $data["Login"] . $old_permissions . $change;
			if ($change == $old_permissions) {
				echo $data["Login"];
				return false;
			} else {
				// change of permissions delete user and insert him to another table
				if ($old_permissions == "Student") {
					$query = "DELETE FROM Prihlasuje WHERE Login='" . $data["Login"]  . "'";
					mysqli_query($this->mysql, $query);

					$query = "DELETE FROM Student WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				} else if ($old_permissions == "Admin") {
					$query = "DELETE FROM Spravca WHERE Login='" . $data["Login"]  . "'";
					mysqli_query($this->mysql, $query);
					echo "<br>" . $data["Login"];
					$query = "DELETE FROM Zamestnanec WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				} else {
					$query = "DELETE FROM Zamestnanec WHERE Login='" . $data["Login"]  . "'";
					$this->delete_user_only($query, $change, $data);
				}
				return true;
			}
		}
		return false;
	}

	private function delete_user_only($query, $change, $data)
	{
		$result = mysqli_query($this->mysql, $query);
		$login = $data["Login"];
		$rocnik = 1;
		$semester = 1;
		$skratka = "BIT";
		$year = date("Y");
		if ($change == "Student") {
			$query = "INSERT INTO Student VALUES ('$login' , $rocnik, $semester, '$skratka', $year)";
			$result = mysqli_query($this->mysql, $query);
			if ($result) {
				// TODO swal
			} else {
				// TODO swal
			}
		} else if ($change == "Garant") {
			$query = "INSERT INTO Zamestnanec VALUES ('$login', '', 0)";
			mysqli_query($this->mysql, $query);
		} else {
			$query = "INSERT INTO Zamestnanec VALUES ('$login', '', 0)";
			mysqli_query($this->mysql, $query);
			$query = "INSERT INTO Spravca VALUES('$login')";
			mysqli_query($this->mysql, $query);
		}
	}

}


?>