<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni kategoriju | Administracija | " . $site_name;
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
		<?php
			include("konekcija.inc");

			if(isset($_GET['id'])){
				$id=$_GET['id'];
				$k=$id;
			}

			if(isset($_POST['izmeni_kategoriju'])){//izmena kategorije
				$izmeni=$_POST['izmeni'];
				$k=$izmeni;

				$naziv_kategorije=$_POST['naziv_kategorije'];

				$reg_naziv_kategorije="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš ]{2,19}$";

				$greske=array();
				$ispis = "";
				if(@!ereg($reg_naziv_kategorije,$naziv_kategorije)){
					$greske[]="Greška naziv kategorije";
				}

				if(count($greske)==0){
					$upit_i="UPDATE kategorije SET naziv_kategorije='$naziv_kategorije' WHERE id_kategorije='$k'";
					$rezultat_i=mysql_query($upit_i,$konekcija);

					if($rezultat_i){
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno, novi naziv je <b>$naziv_kategorije</div>";
					}else{
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri izmeni</div>";
					}
				}else{
					foreach($greske as $greska){
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška naziv kategorije</div>";
					}
				}
			}

			$upit="SELECT * FROM kategorije WHERE id_kategorije='$k'";//selektovanje vrednosti za kategoriju
			$rezultat=mysql_query($upit,$konekcija);

			if($rezultat){
				$kategorija=mysql_fetch_array($rezultat);
			}
		?>
		<div class="center_title_bar">Izmeni kategoriju</div>
		<table style="margin-left:100px;">
		<form name="izmeni_k" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<tr>
			<td class="prva_kolona">Naziv kategorije:</td>
			<td class="druga_kolona"><input type="text" name="naziv_kategorije" value="<?php echo $kategorija['naziv_kategorije']; ?>" class="text_input"/></td>
			<td><input type="hidden" name="izmeni" value="<?php echo $kategorija['id_kategorije']; ?>"/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="izmeni_kategoriju" value="Izmeni" class="add_bt"/></td>
		</tr>
		</form>
		</table>
		<?php if(isset($ispis)){ echo $ispis; } ?>
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
