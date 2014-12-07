<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Pristigli komentari | Administracija | " . $site_name;
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
		<div class="center_title_bar">Pristigli komentari</div>
		<?php

			if(isset($_GET['dozvoli'])){
				$dozvoli=$_GET['dozvoli'];
				$upit_dozvoli_komentar="UPDATE komentari SET status='1' WHERE id_komentara='$dozvoli'";
				$rezultat_dozvoli_komentar=mysql_query($upit_dozvoli_komentar,$konekcija);
				if($rezultat_dozvoli_komentar){
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Komentar će sada biti prikazan</div>";
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška</div>";
				}
			}

			if(isset($_GET['izbrisi'])){
				$izbrisi=$_GET['izbrisi'];
				$upit_izbrisi_komentar="DELETE FROM komentari WHERE id_komentara='$izbrisi'";
				$rezultat_izbrisi_komentar=mysql_query($upit_izbrisi_komentar,$konekcija);
				if($rezultat_izbrisi_komentar){
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Komentar je uspešno obrisan</div>>";
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška</div>";
				}
			}

			$condition = "WHERE status='0'";
			$paginator = pagination("komentari", "$condition", "", "pristigli_komentari.php");
			$pagination = $paginator['pagination'];
			$rowsperpage = $paginator['rowsperpage'];
			$offset = $paginator['offset'];
			$page = $paginator['page'];

			$upit_komentari_proizvoda="SELECT kom.datum_dodavanja, kor.korisnicko_ime, kor.id_korisnika, p.id_proizvoda, p.naziv_proizvoda, kom.id_komentara, kom.komentar FROM komentari kom JOIN korisnici kor ON kom.id_korisnika=kor.id_korisnika JOIN proizvodi p ON kom.id_proizvoda=p.id_proizvoda WHERE kom.status='0' ORDER BY kom.id_komentara DESC LIMIT $rowsperpage OFFSET $offset";
			$rezultat_komentari_proizvoda=mysql_query($upit_komentari_proizvoda,$konekcija);
			$broj_komentari_proizvoda=mysql_num_rows($rezultat_komentari_proizvoda);
			$ispis_str = "";
			if($rezultat_komentari_proizvoda){
				if($broj_komentari_proizvoda>0){
					while($komentar=mysql_fetch_array($rezultat_komentari_proizvoda)){
						$dan=substr($komentar['datum_dodavanja'],-11,2);
						$mesec=substr($komentar['datum_dodavanja'],-14,2);
						$godina=substr($komentar['datum_dodavanja'],0,4);
						$vreme=substr($komentar['datum_dodavanja'],-8,8);
						$datum=$dan."-".$mesec."-".$godina;
						echo "<div class='komentar' style='margin-left:70px;'>";
						echo "Proizvod:<a href='proizvod.php?id_proizvoda=".$komentar['id_proizvoda']."' target='_BLANK' style='color:black;font-weight:bold;margin-right:220px;margin-left:5px;text-decoration:none;'>".$komentar['naziv_proizvoda']."</a><br/>";
						echo "Komentarisao:<a href='profil.php?id=".$komentar['id_korisnika']."' target='_BLANK' style='color:black;font-weight:bold;margin-left:5px;text-decoration:none;'>".$komentar['korisnicko_ime']."</a><br/>";
						echo "<p style='font-weight:bold;margin-bottom:-10px;margin-top:2px;'>".$komentar['komentar']."</p><br/>";
						echo $datum." ".$vreme."<br/>";
						echo "<a href='pristigli_komentari.php?dozvoli=".$komentar['id_komentara']."&page=$page' style='margin-left:30px;text-decoration:none;color:red;'>Dozvoli prikaz komentara</>";
						echo "<a href='izmeni_pristigli_komentar.php?id=".$komentar['id_komentara']."' style='margin-left:20px;text-decoration:none;color:red;' target='_BLANK'>Izmeni i dozvoli prikaz komentara</a>";
						echo "<a href='pristigli_komentari.php?izbrisi=".$komentar['id_komentara']."&page=$page' style='margin-left:20px;text-decoration:none;color:red;'>Izbriši komentar</a>";
						echo "</div>";
					}
					echo $pagination;
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Nema pristiglih komentara</div>";
				}
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
