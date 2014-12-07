<?php session_start();
	  unset($_SESSION['uloga']);
	  unset($_SESSION['id_korisnika']);
	  unset($_SESSION['korisnicko_ime']);
	  session_destroy();
	  header("Location:index.php");
?>