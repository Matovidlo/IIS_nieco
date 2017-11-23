<?php

class Login
{
	private $login;
	private $password;

	public function __construct($login = "", $password = "")
	{
		$this->login = $login;
		$this->password = $password;
	}

	public function compare_password()
	{
		require_once("./database/mysql_init.php");
		$mysql = new mysql_class();
		$mysql = $mysql->get_status();
		$query = "SELECT Login, Heslo FROM Osoba";
		$result = mysqli_query($mysql, $query);

		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row["Login"] === $this->login) {
					$this->password = base64_encode(hash("sha256", $this->password, true));
					if ($this->password === $row["Heslo"]) {
						// session_start();
						$_SESSION['login'] = $_POST["input_login"];
						if (!isset($_SESSION['start_time'])) {
							$str_time = time();
							$_SESSION['timestamp'] = $str_time;
							$_SESSION['logged_in'] = true;
						}
						// TODO session
						return 2;
					}
					return 1;
				}
			}
		}
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

	public function check_session() {
		if(time() - $_SESSION['timestamp'] > 900) {
			echo"<script>alert('15 Minutes over!');</script>";
			// unset($_SESSION['username'], $_SESSION['password'], $_SESSION['timestamp']);
			session_unset();
			$_SESSION['logged_in'] = false;
			// header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/"); //redirect to index.php
		} else {
		}
		// end session
	}
	/* return "administrator", "garant" or "student" */
	public function get_user() {
		// session is for administrator
		// TODO
		if ($_SESSION['login'] == "admin") {
			return "administrator";
		}

		require_once("./database/mysql_init.php");
		$mysql = new mysql_class();
		$mysql = $mysql->get_status();

		$query = "SELECT Login FROM Osoba NATURAL JOIN Zamestnanec";
		$result = mysqli_query($mysql, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			if ($_SESSION['login'] == $row['Login']) {
				return "garant"; // TODO
			}
		}
		$query = "SELECT Login FROM Osoba NATURAL JOIN Student";
		$result = mysqli_query($mysql, $query);

		while ($row = mysqli_fetch_assoc($result)) {
			if ($_SESSION['login'] == $row['Login']) {
				return "student";
			}
		}
	}

	public function show_attributes()
	{
		echo "<pre>";
		echo "login: " . $this->login . "\npassword: " . $this->password . "\n";
		echo "</pre>";
	}
}

class Swal_select {
	public function __construct($type, $title, $message)
	{
		if ($type == "alert") {

		} else if ($type == "warning") {

		} else if  ($type == "success") {

		}
	}
}

