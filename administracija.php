<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Administracija | " . $site_name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php
	include_once('head.inc');
 ?>
<script type="text/JavaScript">
	function getHTTPObject(){
		if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
		else if (window.XMLHttpRequest) return new XMLHttpRequest();
		else {
			alert("Your browser does not support AJAX.");
			return null;
		}
	}

	function prikazi_korisnike(){
		if(httpObject.readyState == 4){
			document.getElementById('prikazi_korisnike').innerHTML = httpObject.responseText;
		}
	}

	function prikazi_k(){
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "ajax.php?korisnicko_ime=" +document.getElementById('korisnicko_ime').value, true);
			httpObject.send(null);
			httpObject.onreadystatechange = prikazi_korisnike;
		}
	}

	function izbrisi(){
		setTimeout("prekini()",3000);
	}

	function prekini(){
		document.getElementById("prikazi_korisnike").innerHTML="";
	}

	var httpObject = null;
</script>
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
		<div class="center_title_bar">Administracija sajta</div>
		<table style="margin-left:90px;margin-bottom:20px;">
			<tr>
				<td class="naslov_tabele"><h4>Korisnici</h4></td>
			</tr>
			<tr>
				<td class="prva_kolona" style="text-align:center;margin:0;padding:0;">
					<form name="korisnici" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="text" name="korisnicko_ime" class="text_input" id="korisnicko_ime" style="margin-bottom:5px;margin-top:5px;margin-left:15px;" onKeyUp="prikazi_k();" onBlur="izbrisi();" value="Unesite korisničko ime"/>
					</form>
				</td>
			</tr>
			<tr>
				<td style="height:0px;margin:0;padding:0;">
					<div id="prikazi_korisnike">
						<?php
							if(isset($_GET['id'])){
								$korisnik=$_GET['id'];

								if(isset($korisnik)){
									$upit_brisanje="DELETE FROM korisnici WHERE id_korisnika='$korisnik'";
									$rezultat_brisanje=mysql_query($upit_brisanje);
									if($rezultat_brisanje){
										$upit_brisanje="DELETE FROM komentari WHERE id_korisnika='$korisnik'";
										$rezultat_brisanje=mysql_query($upit_brisanje);
										echo "<div class='korisnik'>";
										echo "<div class='korisnicko_ime'>Korisnik obrisan</div>";
										echo "<div class='opcije'></div>";
										echo "<div class='opcije'></div>";
										echo "</div>";
									}else{
										echo "<div class='korisnik'>";
										echo "<div class='korisnicko_ime'>Greška</div>";
										echo "<div class='opcije'></div>";
										echo "<div class='opcije'></div>";
										echo "</div>";
									}
								}
							}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td class="naslov_tabele"><h4>Sajt</h4></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="kategorije.php">Kategorije</a></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="podkategorije.php">Podkategorije</a></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="atributi_proizvoda.php">Atributi proizvoda</a></td>
			</tr>
			<tr>
				<td class="naslov_tabele"><h4>Slajder</h4></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="dodaj_slajder.php">Dodaj sliku</a></td>
			</tr>
			<tr>
				<td class="druga_kolona"><a href="izmeni_slajder.php">Izmeni slike</a></td>
			</tr>
		</table>
		<table>
			<tr>
				<td class="naslov_tabele"><h4>Narudžbine</h4></td>
			</tr>
			<?php
					$upit_pristigle_narudzbine="SELECT COUNT(*) AS cnt FROM narudzbine WHERE status='0'";
					$rezultat_pristigle_narudzbine=mysql_query($upit_pristigle_narudzbine,$konekcija);
					$broj_pristigle_narudzbine = mysql_fetch_array($rezultat_pristigle_narudzbine);
			?>
			<tr>
				<td class="prva_kolona"><a href="narudzbine.php">Pregled (<?php echo $broj_pristigle_narudzbine['cnt']; ?>)</a></td>
			</tr>
			<tr>
				<td class="naslov_tabele"><h4>Proizvodi</h4></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="dodaj_proizvode.php">Dodaj proizvode</a></td>
			</tr>
			<tr>
				<td class="druga_kolona"><a href="izmeni_proizvode.php">Izmeni proizvode</a></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="izbrisi_proizvode.php">Izbriši proizvode</a></td>
			</tr>
			<tr>
				<td class="druga_kolona"><a href="proizvodi_akcija.php">Stavi na akciju</a></td>
			</tr>
			<tr>
				<td class="prva_kolona"><a href="skini_sa_akcije.php">Skini sa akcije</a></td>
			</tr>
			<?php
					$upit_izbrisani_proizvodi="SELECT COUNT(*) AS cnt FROM proizvodi pr JOIN podkategorije p ON pr.id_podkategorije=p.id_podkategorije JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE pr.status='0' AND k.status='1' AND p.status='1'";
					$rezultat_izbrisani_proizvodi=mysql_query($upit_izbrisani_proizvodi,$konekcija);
					$broj_izbrisani_proizvodi = mysql_fetch_array($rezultat_izbrisani_proizvodi);
			?>
			<tr>
				<td class="druga_kolona"><a href="izbrisani_proizvodi.php">Izbrisani proizvodi (<?php echo $broj_izbrisani_proizvodi['cnt']; ?>)</a></td>
			</tr>
			<?php
					$upit_pristigli_komentari="SELECT COUNT(*) AS cnt FROM komentari WHERE status='0'";
					$rezultat_pristigli_komentari=mysql_query($upit_pristigli_komentari,$konekcija);
					$broj_pristigli_komentari=mysql_fetch_array($rezultat_pristigli_komentari);
			?>
			<tr>
				<td class="prva_kolona"><a href="pristigli_komentari.php">Pristigli komentari (<?php echo $broj_pristigli_komentari['cnt']; ?>)</a></td>
			</tr>
			<tr>
				<td class="druga_kolona"><a href="izmeni_komentare.php">Upravljanje komentarima</a></td>
			</tr>
		</table>
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
