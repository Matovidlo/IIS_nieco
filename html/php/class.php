<?php
/* init mysql */
class Login
{
// 	/* TODO session */
	private $email;
	private $password;
	private $mysql;

	public function __construct()
	{
		$this->email = $_GET["input_email"];
		$this->password = $_GET["input_password"];
		$this->mysql = mysqli_connect('localhost:/var/run/mysql/mysql.sock', 'xvasko12', 'pokubam5', 'xvasko12');
		if (this->mysql->connect_error) {
			// TODO swal
		}
	}

	public function compare_password()
	{
		//TODO set session things
	}

	public function get_email()
	{
		return $email;
	}

	public function get_password()
	{
		return $password;
	}

	public function show_attributes()
	{
		echo "<pre>";
		echo "email: " . $this->email . "\npassword: " . $this->password . "\n";
		echo "</pre>";
	}
}
?>
