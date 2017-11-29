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
			return "garant"; // TODO
		}
		// Student query
		$query = "SELECT Login FROM Student WHERE Student.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows === 1) {
			mysqli_close($mysql);
			return "student";
		}
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
					// TODO swal
					die();
				}
			} else {
				die();
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
		// TODO swal
		// if (isset($_POST[""])) {
		// 	$this->check_update_query($POST["adresa"], $this->login);
		// }

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
		// TODO past co si jak
		// if ($term == "Letny") {
			// array_shift($this->pole_rokov);
		// }
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
		$query = "SELECT Skratka_programu, Skratka_predmetu, Pocet_kreditov FROM Predmet NATURAL JOIN Studijny_program WHERE Predmet.Ak_rok=" . date("Y");
		$result = mysqli_query($this->mysql, $query);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$predmet = $row['Skratka_predmetu'];
				// TODO swal
				if(isset($_POST["$predmet"])) {
					$this->insert_subject($predmet);
				} else {
					$this->delete_subject($predmet);
				}
			}
		}
		unset($_SESSION["obor"]);
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

	// end of class
}




// TODO swal
class Swal_select {
	public function __construct($type, $title, $message)
	{
		if ($type == "alert") {

		} else if ($type == "warning") {

		} else if  ($type == "success") {

		}
	}
}

