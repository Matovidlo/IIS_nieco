<?php
$save = 0;
if (isset($_POST["input_login"]) && isset($_POST["input_password"])) {
	$login_class = new Login($_POST["input_login"], $_POST["input_password"]);

	if ($login_class->compare_password() === 1) {
		// TODO swal fail but save
		$swal = new Swal_select("success", "Informácie", "boli zmenené");
		$swal->print_msg();
		$save = 1;
	} else if ($login_class->compare_password() === 0){
		$swal = new Swal_select("success", "Informácie", "boli zmenené");
		$swal->print_msg();
		// TODO swal fail
	}
}
?>