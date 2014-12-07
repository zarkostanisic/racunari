<div class="left_footer"></div>
<div class="center_footer">
	<a href="index.php">Početna</a>
	<?php
		if($uloga=="administrator"){
			echo "<a href='administracija.php'>Administracija</a>";
		}
		if($uloga==""){
			echo "<a href='registracija.php'>Registracija</a>";
		}
	?>
	<a href="akcije.php">Akcije</a>
	<?php if(isset($_SESSION['id_korisnika'])){?>
	<a href="moj_meni.php">Moj meni</a>
	<?php } ?>
	<a href="kontakt.php">Kontakt</a>
</div>
<div class="right_footer"></div>
<?php
	mysql_close($konekcija);
 ?>
