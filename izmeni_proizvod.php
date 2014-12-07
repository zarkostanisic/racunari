<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni proizvod | Administracija | " . $site_name;
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
			include("funkcije_slike.inc");
			$id_izmena = "";
			$id_proizvoda = "";
			$ispis = "";
			if(isset($_GET['id_proizvoda'])){
				$id_proizvoda=$_GET['id_proizvoda'];
			}

			if(isset($_POST['izmeni_proizvod'])){
				$id_izmena=$_POST['id_izmena'];
				$naziv_proizvoda=$_POST['naziv_proizvoda'];
				$cena_proizvoda=$_POST['cena_proizvoda'];
				$slika_proizvoda=$_FILES['slika_proizvoda'];
				$naziv_atributa=$_POST['naziv_atributa'];
				$atribut=$_POST['atribut'];

				$reg_cena_proizvoda="^[0-9]{1,10}\.[0-9]{2}$";

				$greske=array();

				if($naziv_proizvoda==""){
					$greske[]="Greska naziv proizvoda";
				}

				if($slika_proizvoda['name']!=""){
					if($slika_proizvoda['type']!="image/jpg"&&$slika_proizvoda['type']!="image/jpeg"&&$slika_proizvoda['type']!="image/png"){
						$greske[]="Greska tip slike";
					}
				}

				if(@!ereg($reg_cena_proizvoda,$cena_proizvoda)){
					$greske[]="Greska cena";
				}

				$upit_slika="SELECT cena_proizvoda, nova_cena, snizenje, id_proizvoda, slika_proizvoda FROM proizvodi WHERE id_proizvoda='$id_izmena'";
				$rezultat_slika=mysql_query($upit_slika,$konekcija);
				$slika=mysql_fetch_array($rezultat_slika);
				$nova_cena = $cena_proizvoda;
				$popust = 0;
				if($cena_proizvoda == $slika['cena_proizvoda']){
					$cena_proizvoda = $slika['cena_proizvoda'];
					$nova_cena = $slika['nova_cena'];
					$popust = $slika['snizenje'];
				}
				if(count($greske)==0){
					$datum=@date("dmYHis");
					if($slika_proizvoda['name']==""){
						$upit_izmena="UPDATE proizvodi SET naziv_proizvoda='$naziv_proizvoda',cena_proizvoda='$cena_proizvoda', nova_cena='$nova_cena', snizenje='$popust' WHERE id_proizvoda='".$id_izmena."'";
						$rezultat_izmena=mysql_query($upit_izmena,$konekcija);
					}else{
						$novi_naziv_proizvoda = strtolower(str_replace(' ', '', $naziv_proizvoda));

						$f = explode('.', $slika_proizvoda['name']);
  					$ext = $f[count($f)-1];
  					$novi_naziv =  $novi_naziv_proizvoda.'_'.time().'.'.$ext;

  					$last_id = $slika['id_proizvoda'];

						$folder_proizvodi = 'slike/proizvodi/' . $last_id . '/';
						$folder_male = 'slike/proizvodi/' . $last_id . '/male/';
						$folder_velike = 'slike/proizvodi/' . $last_id . '/velike/';

						@unlink($folder_proizvodi.$slika['slika_proizvoda']);
						@unlink($folder_male.$slika['slika_proizvoda']);
						@unlink($folder_velike.$slika['slika_proizvoda']);

						if(@move_uploaded_file($slika_proizvoda['tmp_name'],$folder_proizvodi.$novi_naziv)){
							@malaslika($folder_proizvodi.$novi_naziv,$folder_male.$novi_naziv,120,120);
							@malaslika($folder_proizvodi.$novi_naziv,$folder_velike.$novi_naziv,160,160);

							$upit_izmena="UPDATE proizvodi SET naziv_proizvoda='$naziv_proizvoda',slika_proizvoda='".$novi_naziv."',cena_proizvoda='$cena_proizvoda', nova_cena='$nova_cena', snizenje='$popust' WHERE id_proizvoda='".$id_izmena."'";
							$rezultat_izmena=mysql_query($upit_izmena,$konekcija);
						}
					}

					if($rezultat_izmena){
						for($i=0;$i<count($naziv_atributa);$i++){
							if($naziv_atributa[$i] == ""){
								$naziv_atributa[$i] = "-";
							}
							$upit_detalji="UPDATE detalji_proizvoda SET vrednost='".$naziv_atributa[$i]."' WHERE id_detalja='".$atribut[$i]."'";
							$rezultat_detalji=mysql_query($upit_detalji,$konekcija);
						}
						if($rezultat_detalji){
							$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno izmenjeno</div>";
						}else{
							$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri izmeni</div>";
						}
					}
				}else{
					foreach($greske as $greska){
						$ispis.="<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}

			}

			$upit_proizvod="SELECT pr.id_proizvoda, pr.naziv_proizvoda, cena_proizvoda FROM proizvodi pr JOIN podkategorije po ON pr.id_podkategorije=po.id_podkategorije WHERE pr.id_proizvoda='$id_proizvoda' OR pr.id_proizvoda='$id_izmena'";
			$rezultat_proizvod=mysql_query($upit_proizvod,$konekcija);
			$broj_proizvod=mysql_num_rows($rezultat_proizvod);

			if($rezultat_proizvod){
				if($broj_proizvod==1){
					$proizvod=mysql_fetch_array($rezultat_proizvod);

					$upit_detalji="SELECT d.vrednost, a.id_atributa, a.naziv_atributa, d.id_detalja FROM detalji_proizvoda d JOIN atributi a ON d.id_atributa=a.id_atributa WHERE d.id_proizvoda='".$proizvod['id_proizvoda']."' ORDER BY a.id_atributa";
					$rezultat_detalji=mysql_query($upit_detalji,$konekcija);
					$broj_detalji=mysql_num_rows($rezultat_detalji);

					echo "<div class='center_title_bar'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<table style='margin-left:100px;'>";
					echo "<form name='izmeni_p' action='izmeni_proizvod.php' method='post' enctype='multipart/form-data'>";
					echo "<tr><td></td><td><input type='hidden' name='id_izmena' value='".$proizvod['id_proizvoda']."'/>";
					echo "<tr><td class='prva_kolona'>Naziv proizvoda</td><td class='druga_kolona'><input type='text' name='naziv_proizvoda' value='".$proizvod['naziv_proizvoda']."' class='text_input'/>";
					if($rezultat_detalji){
						if($broj_detalji>0){
							while($detalj=mysql_fetch_array($rezultat_detalji)){
								echo "<tr>";
								echo "<td class='prva_kolona'>".$detalj['naziv_atributa']."</td>";
								echo "<td class='druga_kolona'><input type='text' name='naziv_atributa[]' value='".$detalj['vrednost']."' class='text_input'/><input type='hidden' name='atribut[]' value='".$detalj['id_detalja']."'/></td>";
								echo "</tr>";
							}
						}
					}
					echo "<tr><td class='prva_kolona'>Slika proizvoda</td><td class='druga_kolona'><input type='file' name='slika_proizvoda'/></td></tr>";
					echo "<tr><td class='prva_kolona'>Cena proizvoda</td><td class='druga_kolona'><input type='text' name='cena_proizvoda' value='".$proizvod['cena_proizvoda']."' class='text_input'/></td></tr>";
					echo "<tr><td></td><td><input type='submit' name='izmeni_proizvod' value='Izmeni' class='add_bt'/><td></tr>";
					echo "</form>";
					echo "</table>";
				}
			}
		?>
		<?php echo $ispis; ?>
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
