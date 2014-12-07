<?php
	@$konekcija=mysql_connect("mysql5.000webhost.com","a9826744_racuna","8panama8") or die('Nije uspela konekcija sa serverom ' . mysql_error());
	@$baza=mysql_select_db("a9826744_racuna") or die('Nije uspelo selektovanje baze ' . mysql_error());
	@mysql_set_charset("utf8",$konekcija);
?>
