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
				// TODO session
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

	public function check_session() {
		if(time() - $_SESSION['timestamp'] > 900) {
			//echo"<script>alert('15 Minutes over!');</script>";
			// unset($_SESSION['username'], $_SESSION['password'], $_SESSION['timestamp']);
			// session_unset();
			session_destroy();
			$_SESSION['logged_in'] = false;
			// header("Location: http://www.stud.fit.vutbr.cz/~xvasko12/IIS/"); //redirect to index.php
		} else {
		}
		// end session
	}
	/* return "administrator", "garant" or "student" */
	public function get_user() {
		require_once("./database/mysql_init.php");
		$mysql = new mysql_class();
		$mysql = $mysql->get_status();

		$this->login = mysqli_real_escape_string($mysql, $this->login);

		// session is for administrator
		$query = "SELECT Login FROM Osoba NATURAL JOIN Spravca WHERE Osoba.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows > 0) {
			$login = $result->fetch_assoc();
			if ($_SESSION['login'] === $login['Login']) {
				mysqli_close($mysql);
				return "administrator";
			}
		}
		// Garant query
		$query = "SELECT Login FROM Osoba NATURAL JOIN Zamestnanec WHERE Osoba.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows > 0) {
			$login = $result->fetch_assoc();
			if ($_SESSION['login'] === $login['Login']) {
				mysqli_close($mysql);
				return "garant"; // TODO
			}
		}
		// Student query
		$query = "SELECT Login FROM Osoba NATURAL JOIN Student WHERE Osoba.Login='$this->login'";
		$result = mysqli_query($mysql, $query);
		if ($result->num_rows) {
			$login = $result->fetch_assoc();
			if ($_SESSION['login'] == $login['Login']) {
				mysqli_close($mysql);
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

