<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni podkategoriju | Administracija | " . $site_name;
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

			if(isset($_GET['id'])){
				$id=$_GET['id'];
				$k=$id;
			}

			if(isset($_POST['izmeni_podkategoriju'])){//izmena podkategorije
				$izmeni=$_POST['izmeni'];
				$k=$izmeni;

				$naziv_podkategorije=$_POST['naziv_podkategorije'];
				$kategorija=$_POST['kategorija'];

				$reg_naziv_podkategorije="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš ]{0,19}$";

				$greske=array();
				$ispis = "";
				if(@!ereg($reg_naziv_podkategorije,$naziv_podkategorije)){
					$greske[]="Greška naziv podkategorije";
				}

				if(count($greske)==0){
					$upit_i="UPDATE podkategorije SET naziv_podkategorije='$naziv_podkategorije',id_kategorije='$kategorija' WHERE id_podkategorije='$k'";
					$rezultat_i=mysql_query($upit_i,$konekcija);

					if($rezultat_i){
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno, novi naziv je <b>$naziv_podkategorije</div>";
					}else{
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri izmeni</div>";
					}
				}else{
					foreach($greske as $greska){
						$ispis.="<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}
			}

			$upit="SELECT * FROM podkategorije WHERE id_podkategorije='$k'";//prikazivanje vrednosti podkategorije
			$rezultat=mysql_query($upit,$konekcija);

			if($rezultat){
				$podkategorija=mysql_fetch_array($rezultat);
			}
		?>
		<div class="center_title_bar">Izmeni podkategoriju</div>
		<table style="margin-left:100px;">
		<form name="izmeni_p" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<tr>
			<td class='prva_kolona'>Naziv podkategorije:</td>
			<td class='druga_kolona'><input type="text" name="naziv_podkategorije" value="<?php echo $podkategorija['naziv_podkategorije']; ?>" class="text_input"/></td>
		</tr>
		<tr>
			<td class="prva_kolona">Izaberite kategoriju</td>
			<td class="druga_kolona">
				<select name="kategorija" class="select">
					<?php
						$upit_k="SELECT * FROM kategorije";//ispisivanje padajuce liste za kategorije
						$rezultat_k=mysql_query($upit_k,$konekcija);
						$broj_k=mysql_num_rows($rezultat_k);

						if($rezultat_k){
							if($broj_k>0){
								while($kategorija=mysql_fetch_array($rezultat_k)){
									if($podkategorija['id_kategorije']==$kategorija['id_kategorije']){
										echo "<option value='".$kategorija['id_kategorije']."' selected>".$kategorija['naziv_kategorije']."</option>";
									}else{
										echo "<option value='".$kategorija['id_kategorije']."'>".$kategorija['naziv_kategorije']."</option>";
									}

								}
							}
						}
					?>
				</select>
			</td>
			<td><input type="hidden" name="izmeni" value="<?php echo $podkategorija['id_podkategorije']; ?>"/></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="izmeni_podkategoriju" value="Izmeni" class="add_bt"/></td>
		</tr>
		</form>
		</table>
		<?php if(isset($ispis)){ echo $ispis; }  ?>
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
