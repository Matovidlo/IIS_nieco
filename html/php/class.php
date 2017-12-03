<?php

class Login
{
	private $login;
	private $password;

	public function __construct($login = "", $password = "")
	{
		require_once("./database/mysql_init.php");
		$this->login = $login;
		$this->password = $password;
	}

	public function compare_password()
	{
		$mysql = new mysql_class();
		$mysql = $mysql->get_status();
		$this->login = mysqli_real_escape_string($mysql, $this->login);
		$this->password = mysqli_real_escape_string($mysql, $this->password);

		$query = "SELECT Login, Heslo FROM Osoba WHERE Osoba.Login='$this->login'";
		$result = mysqli_query($mysql, $query);

		if ($result->num_rows === 1) {
			$this->password = base64_encode(hash("sha256", $this->password, true));
			$passwd = $result->fetch_assoc();
			if ($this->password === $passwd['Heslo']) {
				$_SESSION['login'] = $this->login;
				if (!isset($_SESSION['start_time'])) {
					$str_time = time();
					$_SESSION['timestamp'] = $str_time;
					$_SESSION['logged_in'] = true;
				}
				mysqli_close($mysql);
				return 2;
			}
			mysqli_close($mysql);
			return 1;
		}
		mysqli_close($mysql);
		return 0;
	}

	public function get_login()
	{
		return $login;
	}

	public function get_password()
	{
		return $password;
	}
	public function init_session()
	{
	  $this->check_session();
	  ini_set("default_charset", "utf-8");
	  $str_time = time();
	  $_SESSION['timestamp'] = $str_time;
	}

	public function check_session()
	{
		if(time() - $_SESSION['timestamp'] > 900) {
			session_destroy();
			$_SESSION['logged_in'] = false;
			header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/"); //redirect to index.php
		} else {
		}
		// end session
	}
	/* return "administrator", "garant" or "student" */
	public function get_user() {
		$mysql = new mysql_class();
		$mysql = $mysql->get_status();

		$this->login = mysqli_real_escape_string($mysql, $this->login);

		// session is for administrator
		$query = "SELECT Login FROM Spravca WHERE Spravca.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows === 1) {
			mysqli_close($mysql);
			return "administrator";
		}
		// Garant query
		$query = "SELECT Login FROM Zamestnanec WHERE Zamestnanec.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows === 1) {
			mysqli_close($mysql);
			return "garant";
		}
		// Student query
		$query = "SELECT Login FROM Student WHERE Student.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows === 1) {
			mysqli_close($mysql);
			return "student";
		}
		mysqli_close($mysql);
		return "error";
	}

	public function show_attributes()
	{
		echo "<pre>";
		echo "login: " . $this->login . "\npassword: " . $this->password . "\n";
		echo "</pre>";
	}
}




class Student {
	private $login;
	private $pole_rokov = array();
	private $mysql;

	public function __construct($login)
	{
		require_once("./database/mysql_init.php");
		$this->mysql = new mysql_class();
		$this->mysql = $this->mysql->get_status();
		$this->login = $login;
	}

	private function check_update_query($post, $where, $type="Email") {
		$query = "UPDATE Osoba SET $type='$post'WHERE Osoba.Login='$where'";
		$result = mysqli_query($this->mysql, $query);
		return $result;
		// return true;
	}

	public function change_information()
	{
		$str_time = time();
		$_SESSION['timestamp'] = $str_time;
		if (!empty($_POST["heslo"]) && is_string($_POST["heslo"])) {
			if (!empty($_POST["heslo_potvrd"]) && is_string($_POST["heslo_potvrd"])) {
				if ($_POST["heslo"] != $_POST["heslo_potvrd"]) {
					$swal = new Swal_select("error", "Heslo", "sa nezhoduje, prosím vyplňte znova");
					$swal->print_msg();
					exit();
				}
			} else {
				exit();
			}

			$heslo = base64_encode(hash("sha256", $_POST["heslo"], true));
			$this->check_update_query($heslo, $this->login, "Heslo");
		}

		if(!empty($_POST["email"]) && is_string($_POST["email"])) {
			$this->check_update_query($_POST["email"], $this->login);
		}
		if (!empty($_POST["mesto"]) && is_string($_POST["mesto"])) {
			$this->check_update_query($_POST["mesto"], $this->login, "Mesto");
		}
		if (!empty($_POST["psc"]) && is_numeric($_POST["psc"])) {
			$this->check_update_query($_POST["psc"], $this->login, "PSC");
		}
		if (!empty($_POST["adresa"]) && is_string($_POST["adresa"])) {
			$this->check_update_query($_POST["adresa"], $this->login, "Adresa");
		}

		$swal = new Swal_select("success", "Informácie", "boli zmenené");
		$swal->print_msg();

	}

	public function get_study()
	{
		$query = "SELECT DISTINCT Ak_rok FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "'";

		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				array_push($this->pole_rokov, $row["Ak_rok"]);
			}
		}
	}

	public function filled() {
		return !empty($this->pole_rokov);
	}

	public function get_year($current=false) {
		if ($current) {
			array_push($this->pole_rokov, date("Y"));
		}
		echo $this->pole_rokov[0];
	}

	public function get_subject($term="Zimny", $points=false)
	{
		$query = "SELECT * FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] .  "' AND Predmet.Ak_rok=" . $this->pole_rokov[0] . " AND Predmet.Semester='$term'";
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if (!$points) {
				echo <<<HEREDOC
				<tr>
					<td>{$row['Skratka_predmetu']}</td>
					<td>{$row['Typ']}</td>
					<td>{$row['Pocet_kreditov']}</td>
					<td>{$row['Nazov']}</td>
					<td>{$row['Fakulta']}</td>
					<td>{$row['Limit_prihlasenych']}</td>
					<td>{$row['Obsadenost']}</td>
				</tr>
HEREDOC;
				} else {
					//TODO body
					echo <<<HEREDOC
				<tr>
					<td>{$row['Skratka_predmetu']}</td>
					<td>{$row['Typ']}</td>
					<td>{$row['Pocet_kreditov']}</td>
					<td>{$row['Nazov']}</td>
					<td>{$row['Fakulta']}</td>
					<td>0</td>
				</tr>
HEREDOC;
				}
			}
		}
	}

	public function get_count($all=false) {
		echo "<tr>";
		if (!$all) {
			// query for one year
			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . $this->pole_rokov[0] . " AND Predmet.Typ='P'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . $this->pole_rokov[0] . " AND Predmet.Typ='PV'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . $this->pole_rokov[0] . " AND Predmet.Typ='V'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . $this->pole_rokov[0];
			$this->perform_count_query($query);
		} else {
			// overall queries
			// FIXME co si jak
			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Typ='P'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Typ='PV'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Typ='V'";
			$this->perform_count_query($query);

			$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "'";
			$this->perform_count_query($query);
		}

		echo "</tr>";
		array_shift($this->pole_rokov);
	}

	public function show_subj_register($type = "Zimny")
	{
		// Get obor to Session
		$query = "SELECT Skratka_programu, Rocnik FROM Student WHERE Student.Login='" . $_SESSION["login"] ."'";
		$result = mysqli_query($this->mysql, $query);
		$data = mysqli_fetch_assoc($result);
		$obor = $data["Skratka_programu"];
		$rocnik = $data["Rocnik"];
		$_SESSION["obor"] = $obor;
		$_SESSION["rocnik"] = $rocnik;

		$query = "SELECT * FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y") . "  AND Predmet.Semester='$type' AND Predmet.Rocnik=$rocnik";
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					echo <<<HEREDOC
				<tr>
					<td>{$row['Skratka_predmetu']}</td>
					<td>{$row['Typ']}</td>
					<td>{$row['Pocet_kreditov']}</td>
					<td>{$row['Nazov']}</td>
					<td>{$row['Fakulta']}</td>
					<td>{$row['Limit_prihlasenych']}</td>
					<td>{$row['Obsadenost']}</td>
					<td>
						<input type="checkbox" name="{$row["Skratka_predmetu"]}" checked>
					</td
				</tr>
HEREDOC;
			}
		}

		$query = "SELECT * FROM Predmet NATURAL JOIN Studijny_program WHERE Predmet.Ak_rok=" . date("Y") . "  AND Predmet.Semester='$type' AND Skratka_programu='$obor' AND Predmet.Rocnik=$rocnik AND Skratka_predmetu NOT IN (SELECT Skratka_predmetu FROM Predmet NATURAL JOIN Prihlasuje NATURAL JOIN Studijny_program WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y") . "  AND Predmet.Semester='$type' AND Predmet.Rocnik=$rocnik)";
		// echo $query;
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
					echo <<<HEREDOC
				<tr>
					<td>{$row['Skratka_predmetu']}</td>
					<td>{$row['Typ']}</td>
					<td>{$row['Pocet_kreditov']}</td>
					<td>{$row['Nazov']}</td>
					<td>{$row['Fakulta']}</td>
					<td>{$row['Limit_prihlasenych']}</td>
					<td>{$row['Obsadenost']}</td>
					<td>
						<input type="checkbox" name="{$row["Skratka_predmetu"]}">
					</td
				</tr>
HEREDOC;
			}
		}
		unset($_SESSION["rocnik"]);

	}

	public function show_subj_count()
	{
		$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y") . " AND Predmet.Typ='P'";
		$this->perform_count_query($query);

		$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y") . " AND Predmet.Typ='PV'";
		$this->perform_count_query($query);

		$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y") . " AND Predmet.Typ='V'";
		$this->perform_count_query($query);

		$query = "SELECT sum(Pocet_kreditov) as total FROM Predmet NATURAL JOIN Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "' AND Predmet.Ak_rok=" . date("Y");
		$this->perform_count_query($query);
	}

	public function change_register_subject()
	{
		// TODO admin moze pridat predmet uzivatelovi z roznych oborov

		// TODO Predmet nemoze byt starsi nez obor

		//  . " AND Skratka_programu='" . $_SESSION["obor"] . "'
		$query = "SELECT Rocny_kreditovy_strop FROM Pravidlo NATURAL JOIN Studijny_program NATURAL JOIN Student WHERE Login='" . $_SESSION["login"] . "'";
		$result = mysqli_query($this->mysql, $query);
		$data = mysqli_fetch_assoc($result);
		$strop = $data["Rocny_kreditovy_strop"];


		$query = "SELECT Skratka_predmetu FROM Predmet NATURAL JOIN Prihlasuje WHERE Login='" . $_SESSION["login"] . "'";
		$result = mysqli_query($this->mysql, $query);
		$registered_already = array();
		while ($row = $result->fetch_assoc()) {
			array_push($registered_already, $row["Skratka_predmetu"]);
		}

		$query = "SELECT Skratka_programu, Skratka_predmetu, Pocet_kreditov FROM Predmet NATURAL JOIN Studijny_program WHERE Predmet.Ak_rok=" . date("Y") . " AND Predmet.Semester='Zimny'";
		$result = mysqli_query($this->mysql, $query);


		$all_subjects = array();
		$summary = 0;
		$fail = false;
		if ($result->num_rows > 0) {
			$accumulator = 0;
			while($row = $result->fetch_assoc()) {
				$predmet = $row['Skratka_predmetu'];
				if(isset($_POST["$predmet"])) {
					$accumulator += $row["Pocet_kreditov"];
					$this->insert_subject($predmet);
					array_push($all_subjects, $predmet);
				} else {
					$this->delete_subject($predmet);
				}
			}
			if ($accumulator < 15) {
				$this->delete_registration();
				$fail = true;
			} else {
				$swal = new Swal_select("success", "Predmety", "boli registrovane");
				$swal->print_msg();
			}
			$summary = $accumulator;
		}

		$query = "SELECT Skratka_programu, Skratka_predmetu, Pocet_kreditov FROM Predmet NATURAL JOIN Studijny_program WHERE Predmet.Ak_rok=" . date("Y") . " AND Predmet.Semester='Letny'";
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			$accumulator = 0;
			while($row = $result->fetch_assoc()) {
				$predmet = $row['Skratka_predmetu'];
				if(isset($_POST["$predmet"])) {
					$accumulator += $row["Pocet_kreditov"];
					array_push($all_subjects, $predmet);
					$this->insert_subject($predmet);
				} else {
					$this->delete_subject($predmet);
				}
			}
			if ($accumulator < 15 || $fail) {
				$this->delete_registration();
				$fail = true;

			} else {
				$swal = new Swal_select("success", "Predmety", "boli registrovane");
				$swal->print_msg();
			}
			$summary += $accumulator;
		}
		if ($summary > $strop || $fail) {
			$this->delete_registration();
			$fail =true;
		}
		if ($fail) {
			$this->change_capacity($registered_already, 1);
		}

		$difference = array_diff($all_subjects, $registered_already);
		if (!empty($difference) && !$fail)
			$this->change_capacity($difference, -1);

		$difference = array_diff($registered_already, $all_subjects);
		if (!empty($difference) && !$fail)
			$this->change_capacity($difference, 1);

		// DEBUG
		// echo " <pre> Uz registrovane" . print_r($registered_already) . " Registracia POST:" . print_r($all_subjects) . " VYSLEDOK" . print_r($difference) ."</pre>";
		unset($_SESSION["obor"]);
	}


	private function add_delete_capacity($subject)
	{
		$query = "SELECT Obsadenost FROM Predmet WHERE Skratka_predmetu='$subject'";
		$result = mysqli_query($this->mysql, $query);
		$data = mysqli_fetch_assoc($result);
		$current = $data["Obsadenost"] + 1;

		$query = "UPDATE Predmet SET Obsadenost=$current WHERE Skratka_predmetu='$subject'";
		$result = mysqli_query($this->mysql, $query);
	}

	private function change_capacity($all_subjects, $value) {
		foreach($all_subjects as $hodnota) {
			$query = "SELECT Obsadenost FROM Predmet WHERE Skratka_predmetu='$hodnota'";
			$result = mysqli_query($this->mysql, $query);
			$data = mysqli_fetch_assoc($result);
			$current = $data["Obsadenost"] + $value;

			$query = "UPDATE Predmet SET Obsadenost=$current WHERE Skratka_predmetu='$hodnota'";
			$result = mysqli_query($this->mysql, $query);
		}
	}

	private function insert_subject($subj)
	{
		$name = $_SESSION["login"];
		$year = date("Y");
		$query = "INSERT INTO Prihlasuje VALUES('$name', '$subj', '$year')";
		$result = mysqli_query($this->mysql, $query);
	}

	private function delete_subject($subj)
	{
		$query = "DELETE FROM Prihlasuje WHERE Prihlasuje.Skratka_predmetu='$subj'";
		$result = mysqli_query($this->mysql, $query);
	}

	private function perform_count_query($query) {
		$result = mysqli_query($this->mysql, $query);
		// die();
		$data = mysqli_fetch_assoc($result);
		if (!empty($data["total"])) {
			echo <<<EOL
			<td>
			{$data["total"]}
			</td>
EOL;
		} else {
			echo "<td> 0 </td>";
		}
	}

	private function delete_registration() {
		$query = "DELETE FROM Prihlasuje WHERE Prihlasuje.Login='" . $_SESSION["login"] . "'";
		$result = mysqli_query($this->mysql, $query);
	}

	// end of class
}


class Swal_select {
	private $string = "";
	private $title = "";
	private $message = "";
	public function __construct($type, $title, $message)
	{
		$this->title = $title;
		$this->message = $message;
		$this->string = "";
		if ($type == "error") {
			$this->string = <<<EOL
			<script>
			swal({	title:"$title",
					text: "$message",
					type: "error",
					html:true });
			</script>
EOL;
		} else if ($type == "warning") {
			$this->string = <<<EOL
			<script>
			swal({	title:"$title",
					text: "$message",
					type: "warning",
					html:true });
			</script>
EOL;
		} else if  ($type == "success") {
			$this->string = <<<EOL
			<script>
			swal({	title:"$title",
					text: "$message",
					type: "success",
					html:true });
			</script>
EOL;
		}
	}

	public function print_msg()
	{
		echo $this->string;
	}

	public function change_message($title="", $message="")
	{

	}
	// end of class
}

