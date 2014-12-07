<?php session_start();
	  include("logovanje.inc");

		if(!isset($_GET['id_proizvoda']) && !isset($_POST['proizvod'])){
			header('Location:index.php');
		}

		$id_proizvoda = "";
		$jedan_proizvod = "";

		if(isset($_GET['id_proizvoda'])){
			$id_proizvoda=$_GET['id_proizvoda'];
		}
		if(isset($_POST['proizvod'])){
			$jedan_proizvod=$_POST['proizvod'];
		}

		$upit_proizvod="SELECT pr.id_proizvoda, pr.naziv_proizvoda, pr.cena_proizvoda, pr.nova_cena, pr.slika_proizvoda, po.naziv_podkategorije FROM proizvodi pr JOIN podkategorije po ON pr.id_podkategorije=po.id_podkategorije WHERE pr.id_proizvoda='$id_proizvoda' OR pr.id_proizvoda='$jedan_proizvod'";
		$rezultat_proizvod=mysql_query($upit_proizvod,$konekcija);

		if($rezultat_proizvod){
			$proizvod=mysql_fetch_array($rezultat_proizvod);
		}

		$naziv_proizvoda = $proizvod['naziv_proizvoda'];
		$kategorija_proizvoda = $proizvod['naziv_podkategorije'];

		$title = $naziv_proizvoda . " | " . $kategorija_proizvoda . " | " . $site_name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?></title>
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
		<div id="osnovno_proizvod">
			<div id="podkategorija_proizvoda"><?php echo $proizvod['naziv_proizvoda']; ?></div>
			<div id="slika_proizvoda"><a href="<?php echo "slike/proizvodi/" . $proizvod['id_proizvoda'] . "/".$proizvod['slika_proizvoda']; ?>" data-lightbox="roadtrip" data-title="<?php echo $proizvod['naziv_proizvoda']; ?>"><img src="<?php echo "slike/proizvodi/" . $proizvod['id_proizvoda'] . "/velike/".$proizvod['slika_proizvoda']; ?>"/></a></div>
			<div id="dodatno_proizvod">
				<div id="proizvod_cena">
					<?php
						$stara=$proizvod['cena_proizvoda'];
						$nova = $proizvod['nova_cena'];
						if($nova<$stara){
							echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>Cena: ".$stara." RSD</span></div>";
							echo "<div class='prod_price'><span class='price' style='color:black;'>Cena: ".round($nova, 2)." RSD</span></div>";
						}else{
							echo "<div class='prod_price'><span class='price'style='color:black'>Cena: ".$proizvod['nova_cena']." RSD</span></div>";
						}
					?>
				</div>
				<div id="proizvod_cart">
					<form>
						<label>Količina</label>
						<input type="text" class="search_text" id="proizvod_kolicina" value="1"/>
						<input type="button" class="search_bt" value="Kupi" id="dodaj_u_korpu"/>
						<input id="id_jedan_proizvod" type="hidden" value="<?php echo $id_proizvoda.$jedan_proizvod; ?>"/>
					</form>
				</div>
			</div>
		</div>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Karakteristike</a></li>
				<li><a href="#tabs-2">Komentari</a></li>
			</ul>
			<div id="tabs-1">
				<table style="float:none;">
					<?php
						$upit_detalji_proizvoda="SELECT a.naziv_atributa, d.vrednost FROM detalji_proizvoda d JOIN atributi a ON d.id_atributa=a.id_atributa WHERE d.id_proizvoda='$id_proizvoda' OR d.id_proizvoda='$jedan_proizvod' ORDER BY d.id_atributa";
						$rezultat_detalji_proizvoda=mysql_query($upit_detalji_proizvoda,$konekcija);
						$broj_detalji_proizvoda=mysql_num_rows($rezultat_detalji_proizvoda);

						if($rezultat_detalji_proizvoda){
							if($broj_detalji_proizvoda>0){
								while($detalj=mysql_fetch_array($rezultat_detalji_proizvoda)){
									echo "<tr>";
									echo "<td class='atribut'>".$detalj['naziv_atributa']."</td>";
									echo "<td class='detalj'>".$detalj['vrednost']."</td>";
									echo "</tr>";
								}
							}
						}
					?>
				</table>
			</div>
			<div id="tabs-2">
				<?php
					$uloga = "";
					if(isset($_SESSION['uloga'])){
						$uloga=$_SESSION['uloga'];
					}
					if($uloga!=""){
				?>
				<form name="komentari" action="<?php echo $_SERVER['PHP_SELF']; ?>#tabs-2" method="post">
				<table>
				<tr>
					<td>
						<textarea name="komentar"></textarea>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">
						<input id="jedan_proizvod" type="hidden" name="proizvod" value="<?php echo $id_proizvoda.$jedan_proizvod; ?>"/>
						<input type="submit" name="unesi_komentar" value="Unesi" class="add_bt"/>
					</td>
				</tr>
				</table>
				</form>
				<?php
					if(isset($_POST['unesi_komentar'])){
						$komentar=$_POST['komentar'];
						$datum=@date("Y-m-d H:i:s");
						$id_korisnika=$_SESSION['id_korisnika'];

						$reg_komentar="^[A-ZŽĆČĐŠ]{1}[A-Za-zžćčđšŽĆČĐŠ0-9\.\,\?\!-\/ ]{3,199}$";

						$greske=array();

						if(@!ereg($reg_komentar,$komentar)){
							$greske[]="Greška komentar";
						}

						if(count($greske)==0){
							$upit_unos_komentara="INSERT INTO komentari VALUES('','".$jedan_proizvod."','".$id_korisnika."','ne','".$komentar."','".$datum."')";
							$rezultat_unos_komentara=mysql_query($upit_unos_komentara,$konekcija);

							if($rezultat_unos_komentara){
								echo "<div class='komentar' style='text-align:center;'>Komentar je uspešno dodat, uskoro će biti prikazan</div>";
							}else{
								echo "<div class='komentar' style='text-align:center;'>Greska pri dodavanju</div>";
							}
						}else{
							foreach($greske as $greska){
								echo "<div class='komentar' style='text-align:center;'>".$greska."</div>";
							}
						}
					}
				?>
				<?php
					}else{
						echo "<div class='komentar' style='text-align:center;'>Da bi komentarisali morate biti ulogovani</div>";
					}
				?>
				<?php
					$paginator = pagination("komentari", "WHERE (id_proizvoda='$id_proizvoda' OR id_proizvoda='$jedan_proizvod') AND status='1'", "&id_proizvoda=$id_proizvoda$jedan_proizvod#tabs-2", "proizvod.php");
					$pagination = $paginator['pagination'];
					$rowsperpage = $paginator['rowsperpage'];
					$offset = $paginator['offset'];

					$upit_komentari_proizvoda="SELECT kom.datum_dodavanja, kor.id_korisnika,  kor.korisnicko_ime, kom.komentar FROM komentari kom JOIN korisnici kor ON kom.id_korisnika=kor.id_korisnika JOIN proizvodi p ON kom.id_proizvoda=p.id_proizvoda WHERE (kom.id_proizvoda='$id_proizvoda' OR kom.id_proizvoda='$jedan_proizvod') AND kom.status='1' ORDER BY kom.id_komentara DESC LIMIT $rowsperpage OFFSET $offset";
					$rezultat_komentari_proizvoda=mysql_query($upit_komentari_proizvoda,$konekcija);
					$broj_komentari_proizvoda=mysql_num_rows($rezultat_komentari_proizvoda);
					if($rezultat_komentari_proizvoda){
						if($broj_komentari_proizvoda>0){
							while($komentar=mysql_fetch_array($rezultat_komentari_proizvoda)){
								$dan=substr($komentar['datum_dodavanja'],-11,2);
								$mesec=substr($komentar['datum_dodavanja'],-14,2);
								$goRSDa=substr($komentar['datum_dodavanja'],0,4);
								$vreme=substr($komentar['datum_dodavanja'],-8,8);
								$datum=$dan."-".$mesec."-".$goRSDa;
								echo "<div class='komentar'>";
								echo "Komentarisao:<a href='profil.php?id=".$komentar['id_korisnika']."' target='_BLANK' style='color:black;font-weight:bold;margin-left:5px;'>".$komentar['korisnicko_ime']."</a><br/>";
								echo "<p style='margin-top:10px;margin-bottom:10px;'>".$komentar['komentar']."</p>";
								echo $datum." ".$vreme."<br/>";
								echo "</div>";
							}
							echo $pagination;
						}else{
							echo "<div class='komentar' style='margin-left:0px;text-align:center;'>Trenutno nema komentara za ovaj proizvod</div>";
						}
					}
				?>
			</div>
			<script>
				$( "#tabs" ).tabs();
			</script>
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
