<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Komentari proizvoda | Administracija | " . $site_name;
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
		<div class='center_title_bar'>Komentari proizvoda</div>
		<?php
				$id_proizvoda=$_GET['id_proizvoda'];

				if(isset($_GET['izbrisi'])){
					$izbrisi=$_GET['izbrisi'];

					$upit_izbrisi_komentar="DELETE FROM komentari WHERE id_komentara='$izbrisi'";
					$rezultat_izbrisi_komentar=mysql_query($upit_izbrisi_komentar,$konekcija);
					if($rezultat_izbrisi_komentar){
						echo "<div class='komentar' style='text-align:center;margin-left:70px;'>Komentar je uspešno obrisan</div>";
					}else{
						echo "<div class='komentar' style='text-align:center;margin-left:70px;'>Greška</div>";
					}
				}

				$condition = "WHERE id_proizvoda='$id_proizvoda' AND status='da'";
				$paginator = pagination("komentari", "$condition", "&id_proizvoda=$id_proizvoda", "prikazi_komentare.php");
				$pagination = $paginator['pagination'];
				$rowsperpage = $paginator['rowsperpage'];
				$offset = $paginator['offset'];
				$page = $paginator['page'];

				$upit_komentari="SELECT k.datum_dodavanja, kor.id_korisnika, kor.korisnicko_ime, p.id_proizvoda, p.naziv_proizvoda, k.id_komentara, k.komentar FROM komentari k JOIN proizvodi p ON k.id_proizvoda=p.id_proizvoda JOIN korisnici kor ON k.id_korisnika=kor.id_korisnika WHERE p.id_proizvoda='$id_proizvoda' AND k.status='da' ORDER BY k.id_komentara DESC LIMIT $rowsperpage OFFSET $offset";
				$rezultat_komentari=mysql_query($upit_komentari,$konekcija);
				$broj_komentari=mysql_num_rows($rezultat_komentari);
				$ispis_brisanje = "";
				$ispis_str = "";
				if($rezultat_komentari){
					if($broj_komentari>0){
						$i=0;
						while($komentar=mysql_fetch_array($rezultat_komentari)){
							$dan=substr($komentar['datum_dodavanja'],-11,2);
							$mesec=substr($komentar['datum_dodavanja'],-14,2);
							$godina=substr($komentar['datum_dodavanja'],0,4);
							$vreme=substr($komentar['datum_dodavanja'],-8,8);
							$datum=$dan."-".$mesec."-".$godina;
							echo "<div class='komentar' style='margin-left:70px;'>";
							echo "Proizvod:<a href='proizvod.php?id_proizvoda=".$komentar['id_proizvoda']."' target='_BLANK' style='color:black;font-weight:bold;margin-right:220px;margin-left:5px;text-decoration:none;'>".$komentar['naziv_proizvoda']."</a><br/>";
							echo "Komentarisao:<a href='profil.php?id=".$komentar['id_korisnika']."' target='_BLANK' style='color:black;font-weight:bold;margin-left:5px;text-decoration:none;'>".$komentar['korisnicko_ime']."</a><br/>";
							echo "<p style='margin-bottom:-10px;margin-top:2px;font-weight:bold;'>".$komentar['komentar']."</p><br/>";
							echo $datum." ".$vreme."<br/>";
							echo "<a href='izmeni_komentar.php?id=".$komentar['id_komentara']."' target='_BLANK' style='margin-left:20px;text-decoration:none;color:red;'>Izmeni komentar</a>";
							echo "<a href='prikazi_komentare.php?izbrisi=".$komentar['id_komentara']."&page=$page&id_proizvoda=$id_proizvoda' style='margin-left:260px;text-decoration:none;color:red;'>Izbriši komentar</a>";
							echo "</div>";
						$i++;
						}
						echo $pagination;
					}else{
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema komentara za ovaj proizvod</div>";
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
