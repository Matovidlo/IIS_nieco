<?php

class Login
{
// 	/* TODO session */
	private $login;
	private $password;

	public function __construct()
	{
		$this->login = $_POST["input_login"];
		$this->password = $_POST["input_password"];
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
				echo "login: " .  $row["Login"] . " heslo " . $row["Heslo"] . "<br>";
			}
		}
		return true;
	}

	public function get_login()
	{
		return $login;
	}

	public function get_password()
	{
		return $password;
	}

	public function show_attributes()
	{
		echo "<pre>";
		echo "login: " . $this->login . "\npassword: " . $this->password . "\n";
		echo "</pre>";
	}
}

