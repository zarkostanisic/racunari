<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni atribut | Administracija | " . $site_name;
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

			if(isset($_POST['izmeni_atribut'])){//izmena atributa
				$izmeni=$_POST['izmeni'];
				$k=$izmeni;

				$naziv_atributa=$_POST['naziv_atributa'];
				$podkategorija=$_POST['podkategorija'];

				$reg_naziv_atributa="^[A-ZŽĆČĐŠ]{1}[A-Za-z0-9ŽĆČĐŠžćčđš ]{0,19}$";

				$greske=array();
				$ispis = "";
				if(@!ereg($reg_naziv_atributa,$naziv_atributa)){
					$greske[]="Greška naziv atributa";
				}

				if(count($greske)==0){
					$upit_i="UPDATE atributi SET naziv_atributa='$naziv_atributa',id_podkategorije='$podkategorija' WHERE id_atributa='$k'";
					$rezultat_i=mysql_query($upit_i,$konekcija);

					if($rezultat_i){
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno, novi naziv je <b>$naziv_atributa</div>";
					}else{
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri izmeni</div>";
					}
				}else{
					foreach($greske as $greska){
						$ispis.="<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}
			}

			$upit="SELECT * FROM atributi WHERE id_atributa='$k'";//selektovanje vrednosti za podkategoriju
			$rezultat=mysql_query($upit,$konekcija);

			if($rezultat){
				$atribut=mysql_fetch_array($rezultat);
			}
		?>
		<div class="center_title_bar">Izmeni atribut</div>
		<table style="margin-left:100px;">
		<form name="izmeni_k" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<tr>
			<td class="prva_kolona">Naziv atributa:</td>
			<td class="druga_kolona"><input type="text" name="naziv_atributa" value="<?php echo $atribut['naziv_atributa']; ?>" class="text_input"/></td>
			<td><input type="hidden" name="izmeni" value="<?php echo $atribut['id_atributa']; ?>"/></td>
		</tr>
				<tr>
			<td class="prva_kolona">Izaberi proizvod:</td>
			<td class="druga_kolona">
				<select name="podkategorija" style="height:20px;" class="select">
					<?php
						$upit_p="SELECT * FROM podkategorije";//ispisivanje padajuce liste za podkategorije
						$rezultat_p=mysql_query($upit_p,$konekcija);
						$broj_p=mysql_num_rows($rezultat_p);

						if($rezultat_p){
							if($broj_p>0){
								while($podkategorija=mysql_fetch_array($rezultat_p)){

									if($atribut['id_podkategorije']==$podkategorija['id_podkategorije']){
										echo "<option value='".$podkategorija['id_podkategorije']."' selected>".$podkategorija['naziv_podkategorije']."</option>";
									}else{
										echo "<option value='".$podkategorija['id_podkategorije']."'>".$podkategorija['naziv_podkategorije']."</option>";
									}
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="izmeni_atribut" value="Izmeni" class="add_bt"/></td>
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
