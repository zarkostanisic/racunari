<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni pristigli komentar | Administracija | " . $site_name;
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
		<div class="center_title_bar">Izmeni pristigli komentar</div>
		<?php
			$id_komentara="";
			$id = "";
			if(isset($_GET['id'])){
				$id=$_GET['id'];
			}

			if(isset($_POST['unesi_komentar'])){
				$id_komentara=$_POST['id_komentara'];
				$komentar=$_POST['komentar'];
				$reg_komentar="^[A-ZZCCDS]{1}[A-Za-zzccdsZCCDS0-9\.\,\?\!-\/ ]{3,199}$";

				$greske=array();
				$ispis_izmena = "";
				if(@!ereg($reg_komentar,$komentar)){
					$greske[]="Greska komentar";
				}

				if(count($greske)==0){
					$upit_izmena_komentara="UPDATE komentari SET status='1',komentar='$komentar' WHERE id_komentara='$id' OR id_komentara='$id_komentara'";
					$rezultat_izmena_komentara=mysql_query($upit_izmena_komentara,$konekcija);

					if($rezultat_izmena_komentara){
						$ispis_izmena.="<div class='komentar' style='text-align:center;'>Komentar je uspešno izmenjen, i može da se prikaže</div>";
					}else{
						$ispis_izmena.="<div class='komentar' style='text-align:center;'>Greška pri izmeni</div>";
					}
				}else{
					foreach($greske as $greska){
						$ispis_izmena.="<div class='komentar' style='text-align:center;'>".$greska."</div>";
					}
				}
			}

			if(isset($id)||isset($komentar)){
				$upit_pristigli_komentar="SELECT * FROM komentari WHERE id_komentara='$id' OR id_komentara='$id_komentara'";
				$rezultat_pristigli_komentar=mysql_query($upit_pristigli_komentar,$konekcija);
				$broj_pristigli_komentar=mysql_num_rows($rezultat_pristigli_komentar);

				if($rezultat_pristigli_komentar){
					if($broj_pristigli_komentar>0){
						$pristigli_komentari=mysql_fetch_array($rezultat_pristigli_komentar);
					}
				}
			}
		?>
		<div id="tabs-2" style="margin-left:70px;">
		<form name="komentari" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table>
		<tr>
			<td>
				<textarea name="komentar"><?php echo $pristigli_komentari['komentar']; ?></textarea>
			</td>
		</tr>
		<tr>
			<td style="text-align:center;">
				<input type="hidden" name="id_komentara" value="<?php echo $pristigli_komentari['id_komentara']; ?>"/>
				<input type="submit" name="unesi_komentar" value="Izmeni" class="add_bt"/>
			</td>
		</tr>
		</table>
		</form>
		<?php if(isset($ispis_izmena)){ echo $ispis_izmena; } ?>
		</div>
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
