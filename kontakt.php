<?php session_start();
	  include("logovanje.inc");

	  $title = "Kontakt | " . $site_name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php
	include_once('head.inc');
 ?>
</head>
<body>
<div id="main_container">
	<div class="top_bar">
    	<?php
			include("logovanje_forma.inc");//logovanje korisnika
		?>
    </div>
	<div id="header">
		<?php
			include("slajder.inc");//slajder
		?>
    </div>
    <div id="main_content">
		<div id="menu_tab">
			<?php
				include("meni.inc");//gornji meni
			?>
		</div><!-- end of menu tab -->
   <div class="left_content">
		<?php
			include("levi_meni.inc");//levi meni
		?>
	</div><!-- end of left content -->
   <div class="center_content">
		<div class="center_title_bar">Kontakt</div>
		<table style="margin-left:100px;margin-top:50px;">
		<form name="kontakt" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<tr>
				<td class="prva_kolona">Vaše ime:</td>
				<td class="druga_kolona"><input type="text" name='ime' class="text_input" style="width:200px;"/></td>
			</tr>
			<tr>
				<td class="prva_kolona">Vaš e-mail:</td>
				<td class="druga_kolona"><input type="text" name='email' class="text_input" style="width:200px;"/></td>
			</tr>
			<tr>
				<td class="prva_kolona">Vaša poruka:</td>
				<td class="druga_kolona"><textarea name='poruka' style="width:200px;height:100px;border:1px solid gray;"></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="posalji" value="Pošalji" class="add_bt" />
					<input type="reset" name="reset" value="Obriši" class="add_bt"/>
				</td>
			</tr>
		</form>
		</table>
		<?php
			if(isset($_POST['posalji'])){

					$ime=$_POST['ime'];
					$email=$_POST['email'];
					$poruka=$_POST['poruka'];
					$nas_email=$glavni_email;
					$subject = 'Poruka sa sajta!';
					$message = 'Ime: '.$ime.'\n \n'.$poruka;
					$headers = 'From: '.$email . "\r\n" .
					'Reply-To: '.$email. "\r\n" .
					'X-Mailer: PHP/' . phpversion();;
					ini_set("SMTP", $smtp_server);
					ini_set("sendmail_from", $email);
					ini_set("smtp_port", $smtp_port);
					$mail="/^[A-z0-9\._-]+". "@". "[A-z0-9][A-z0-9-]*". "(\.[A-z0-9_-]+)*". "\.([A-z]{2,6})$/";
					if(!preg_match("/^[A-z]{3,}$/", $ime)){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Nepravilno uneto ime. Mora da sadrži slova i više od 3 karaktera.</div>";
					}
					elseif(!preg_match($mail, $email)){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Nepravilno unet e-mail. Unesite ispravan e-mail.</div>";
					}
					elseif(!preg_match("/^[A-z0-9\s\._-]{20,}$/", $poruka)){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Poruka mora da sadrži više od 20 i manje od 2 000 karaktera!</div>";
					}
					elseif(@mail($nas_email ,$subject ,$message ,$headers )){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>E-mail je poslat</div>";
					}
					else{echo"<div class='komentar' style='margin-left:70px;text-align:center;'>Neuspelo slanje email-a. Proverite unesene podatke.</div>";}
			}
		?>
	</div><!-- end of center content -->
	</div><!-- end of main content -->
	<div class="right_content">
		<?php
			include("desni_meni.inc");
		?>
	</div>
	<div class="footer">
		<?php
			include("futer.inc");//futer
		?>
	</div>
</div>
<!-- end of main_container -->
</body>
</html>
