<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Atributi proizvoda | Administracija | " . $site_name;
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
			if(isset($_GET['id'])){//brisanje atributa
				$id=$_GET['id'];

				$upit_b="DELETE FROM atributi WHERE id_atributa='$id'";
				$rezultat_b=mysql_query($upit_b,$konekcija);

				if($rezultat_b){
					$ispis_brisanje="<div class='komentar' style='margin-left:70px;text-align:center;'>Atribut je uspešno obrisan</div>";
				}else{
					$ispis_brisanje="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri brisanju</div>";
				}
			}
		?>
		<?php
			if(isset($_POST['dodaj_atribut'])){//dodavanje atributa
				$podkategorija=$_POST['podkategorija'];
				$naziv_atributa=$_POST['naziv_atributa'];

				$reg_naziv_atributa="^[A-ZŽĆČĐŠ]{1}[A-Za-z0-9ŽĆČĐŠžćčđš ]{0,19}$";

				$greske=array();
				$ispis_unos = "";
				if(@!ereg($reg_naziv_atributa,$naziv_atributa)){
						$greske[]="Greška naziv atributa";
				}

				if($podkategorija=="0"){
					$greske[]="Morate izabrati kategoriju";
				}

				if(count($greske)==0){
					$upit="INSERT INTO atributi VALUES('','".$podkategorija."','".$naziv_atributa."')";
					$rezultat=mysql_query($upit,$konekcija);
					if($rezultat){
						$ispis_unos.="<div class='komentar' style='margin-left:70px;text-align:center;'>Atribut je uspešno  dodat</div>";
					}else{
						$ispis_unos.="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri dodavanju</div>";
					}
				}else{
					foreach($greske as $greska){
						$ispis_unos.="<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}
			}
		?>
		<div class="center_title_bar">Atributi proizvoda(dodavanje, brisanje, izmena)</div>
		<table>
		<form name="dodaj_a" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<tr>
			<td class="prva_kolona">Naziv atributa:</td>
			<td class="druga_kolona"><input type="text" name="naziv_atributa" class="text_input"/></td>
		</tr>
		<tr>
			<td class="prva_kolona">Izaberi proizvod:</td>
			<td class="druga_kolona">
				<select name="podkategorija" style="height:20px;" class="select">
					<option value="0">Izaberi</option>
					<?php
						$upit_p="SELECT id_podkategorije, naziv_podkategorije FROM podkategorije WHERE status='1'";//ispisivanje padajuce liste za podkategorije
						$rezultat_p=mysql_query($upit_p,$konekcija);
						$broj_p=mysql_num_rows($rezultat_p);

						$p = 0;
						if(isset($_GET['podkategorija'])){
							$p=$_GET['podkategorija'];
						}

						if($rezultat_p){
							if($broj_p>0){
								while($podkategorije=mysql_fetch_array($rezultat_p)){
									if($podkategorija==$podkategorije['id_podkategorije']||($p!="0"&&$p==$podkategorije['id_podkategorije'])){
										echo "<option value='".$podkategorije['id_podkategorije']."' selected>".$podkategorije['naziv_podkategorije']."</option>";
									}else{
										echo "<option value='".$podkategorije['id_podkategorije']."'>".$podkategorije['naziv_podkategorije']."</option>";
									}
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="dodaj_atribut" value="Dodaj" class="add_bt"/><input type='reset' class='add_bt'/></td>
		</tr>
		</form>
		</table>
		<?php
			if(isset($ispis_unos)){ echo $ispis_unos; }
		?>
		<table>
			<tr>
				<th colspan="4" class='prva_kolona'>
					<form name="prikazi_podkategorije" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<select name="filter" class="select">
					<option value="0">Izaberi</option>
					<?php
						$upit_p="SELECT id_podkategorije, naziv_podkategorije FROM podkategorije WHERE status='1'";//ispisivanje padajuce liste za podkategorije
						$rezultat_p=mysql_query($upit_p,$konekcija);
						$broj_p=mysql_num_rows($rezultat_p);
						$p=$_GET['podkategorija'];

						if($rezultat_p){
							if($broj_p>0){
								while($podkategorije=mysql_fetch_array($rezultat_p)){
									if(($podkategorija==$podkategorije['id_podkategorije'])||($p==$podkategorije['id_podkategorije'])){
										echo "<option value='".$podkategorije['id_podkategorije']."' selected>".$podkategorije['naziv_podkategorije']."</option>";
									}else{
										echo "<option value='".$podkategorije['id_podkategorije']."'>".$podkategorije['naziv_podkategorije']."</option>";
									}
								}
							}
						}
					?>
					</select>
					<input type="submit" name="prikazi" value="Prikaži" class="search_bt filter"/></td>
					</form>
				</th>
			</tr>
			<tr>
				<th class="naslov_tabele">Naziv</th>
				<th class="naslov_tabele">Kategorija</th>
				<th class="naslov_tabele">Opcije</th>
			</tr>
			<?php
				$filter = "";
				if(isset($_POST['dodaj_atribut'])){//filtriranje atributa prema podkategorijama
					$podkategorija=$_POST['podkategorija'];
					if((isset($podkategorija))){
						if(($podkategorija!="0")){
							$upit_a="SELECT a.naziv_atributa, a.id_atributa, p.naziv_podkategorije, a.id_podkategorije FROM atributi a JOIN podkategorije p ON a.id_podkategorije=p.id_podkategorije WHERE a.id_podkategorije='$podkategorija' ORDER BY p.naziv_podkategorije";
						}else{
                    $upit_a="SELECT a.naziv_atributa, a.id_atributa, p.naziv_podkategorije, a.id_podkategorije FROM atributi a JOIN podkategorije p ON a.id_podkategorije=p.id_podkategorije WHERE a.id_podkategorije='$podkategorija' ORDER BY p.naziv_podkategorije LIMIT 0";
            }
					}
				}else if(isset($_POST['prikazi'])&&!($_POST['filter']=="0")){
						$filter=$_POST['filter'];
						$upit_a="SELECT a.naziv_atributa, a.id_atributa, p.naziv_podkategorije, a.id_podkategorije FROM atributi a JOIN podkategorije p ON a.id_podkategorije=p.id_podkategorije WHERE a.id_podkategorije='$filter' ORDER BY p.naziv_podkategorije";
				}else if(isset($_GET['podkategorija'])){
					$p=$_GET['podkategorija'];
					$upit_a="SELECT a.naziv_atributa, a.id_atributa, p.naziv_podkategorije, a.id_podkategorije FROM atributi a JOIN podkategorije p ON a.id_podkategorije=p.id_podkategorije WHERE a.id_podkategorije='$p' ORDER BY p.naziv_podkategorije";
				}else{
					$upit_a="SELECT a.naziv_atributa, a.id_atributa, p.naziv_podkategorije, a.id_podkategorije FROM atributi a JOIN podkategorije p ON a.id_podkategorije=p.id_podkategorije ORDER BY p.naziv_podkategorije LIMIT 0";
				}
				$rezultat_a=mysql_query($upit_a,$konekcija);

				$broj_rezultat_a=mysql_num_rows($rezultat_a);

				if($broj_rezultat_a>0){

					while($atribut=mysql_fetch_array($rezultat_a)){//prikaz atributa u formi tabele
						echo "<tr>";
						echo "<td class='prva_kolona'>".$atribut['naziv_atributa']."</td>";
						echo "<td class='druga_kolona'>".$atribut['naziv_podkategorije']."</td>";
						echo "<td class='druga_kolona'><a href='atributi_proizvoda.php?id=".$atribut['id_atributa']."&podkategorija=".$atribut['id_podkategorije']."'>Izbriši</a> / <a href='izmeni_atribut.php?id=".$atribut['id_atributa']."' target='_BLANK'>Izmeni</a></td>";
						echo "</tr>";
					}

				}else{
					$ispis_brisanje = "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno ne postoji ne jedan atribut, dodajte atribut</div>";
					if($filter == 0){
						$ispis_brisanje = "<div class='komentar' style='margin-left:70px;text-align:center;'>Izaberite podkategoriju</div>";
					}
				}
			?>
		</table>
		<?php if(isset($ispis_brisanje)){ echo $ispis_brisanje; } ?>
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
