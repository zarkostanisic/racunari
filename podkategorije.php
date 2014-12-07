<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Podkategorije | Administracija | " . $site_name;
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

			if(isset($_GET['id'])){//brisanje podkategorije
				$id=$_GET['id'];//id kategorije

				$upit_brisanje_podkategorija="UPDATE podkategorije SET status='0' WHERE id_podkategorije='".$id."'";//brisanje podkategorija
				$rezultat_brisanje_podkategorija=mysql_query($upit_brisanje_podkategorija,$konekcija);
				if($rezultat_brisanje_podkategorija){

					mysql_query("UPDATE proizvodi SET status='0' WHERE id_podkategorije='$id'", $konekcija);

					$ispis_brisanje="<div class='komentar' style='margin-left:70px;text-align:center;'>Podkategorija uspešno obrisana</div>";
				}else{
					$ispis_brisanje="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri brisanju</div>";
				}
			}
		?>
		<?php
			if(isset($_POST['dodaj_podkategoriju'])){//dodavanje podkategorije
				$kategorija=$_POST['kategorija'];
				$naziv_podkategorije=$_POST['naziv_podkategorije'];

				$reg_naziv_podkategorije="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš ]{0,19}$";

				$greske=array();
				$ispis_unos = "";
				if(@!ereg($reg_naziv_podkategorije,$naziv_podkategorije)){
					$greske[]="Greška naziv podkategorije";
				}

				if($kategorija=="0"){
					$greske[]="Morate izabrati kategoriju";
				}

				if(count($greske)==0){
					$upit="INSERT INTO podkategorije VALUES('','".$kategorija."','".$naziv_podkategorije."','1')";
					$rezultat=mysql_query($upit,$konekcija);

					$last_id = mysql_insert_id();

					if($rezultat){
						$ispis_unos.="<div class='komentar' style='margin-left:70px;text-align:center;'>Podkategorija je uspešno  dodata, <a href='atributi_proizvoda.php?podkategorija=$last_id' style='color:red;text-decoration:underline;' target='_BLANK'>dodajte atribute</a></div>";
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
		<div class="center_title_bar">Podkategorije(dodavanje, brisanje, izmena)</div>
		<table>
		<form name="dodaj_p" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<tr>
			<td class="prva_kolona">Naziv podkategorije:</td>
			<td class="druga_kolona"><input type="text" name="naziv_podkategorije" class="text_input" value="<?php if(isset($naziv_podkategorije)){ echo $naziv_podkategorije; } ?>"/></td>
		</tr>
		<tr>
			<td class="prva_kolona">Izaberi kategoriju:</td>
			<td class="druga_kolona">
				<select name="kategorija" class="select">
					<option value="0">Izaberi</option>
					<?php
						$upit_k="SELECT id_kategorije, naziv_kategorije FROM kategorije WHERE status='1'";//ispisivanje padajuce liste za kategorije
						$rezultat_k=mysql_query($upit_k,$konekcija);
						$broj_k=mysql_num_rows($rezultat_k);
						$k=$_GET['kategorija'];

						if($rezultat_k){
							if($broj_k>0){
								while($kategorije=mysql_fetch_array($rezultat_k)){
									if($kategorija==$kategorije['id_kategorije']||$k==$kategorije['id_kategorije']){
										echo "<option value='".$kategorije['id_kategorije']."' selected>".$kategorije['naziv_kategorije']."</option>";
									}else{
										echo "<option value='".$kategorije['id_kategorije']."'>".$kategorije['naziv_kategorije']."</option>";
									}
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="dodaj_podkategoriju" value="Dodaj" class="add_bt"/><input type='reset' class='add_bt'/></td>
		</tr>
		</form>
		</table>
		<?php
			if(isset($ispis_unos)){ echo $ispis_unos; }
		?>
		<table>
			<tr>
				<th colspan="3" class="prva_kolona">
					<form name="prikazi_podkategorije" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<select name="filter" class="select">
					<option value="0">Sve kategorije</option>
					<?php
						$upit_k="SELECT id_kategorije, naziv_kategorije FROM kategorije WHERE status='1'";//ispisivanje padajuce liste za kategorije
						$rezultat_k=mysql_query($upit_k,$konekcija);
						$broj_k=mysql_num_rows($rezultat_k);
						$k=$_GET['kategorija'];
						if($rezultat_k){
							if($broj_k>0){
								while($kategorije=mysql_fetch_array($rezultat_k)){
									if(($kategorija==$kategorije['id_kategorije'])||($k==$kategorije['id_kategorije'])){
										echo "<option value='".$kategorije['id_kategorije']."' selected>".$kategorije['naziv_kategorije']."</option>";
									}else{
										echo "<option value='".$kategorije['id_kategorije']."'>".$kategorije['naziv_kategorije']."</option>";
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
				<th class="naslov_tabele">Naziv podkategorije</th>
				<th class="naslov_tabele">Kategorija</th>
				<th class="naslov_tabele">Opcije</th>
			</tr>
			<?php
				if(isset($_POST['dodaj_podkategoriju'])){//filtriranje podkategorija prema kategorijama
					$kategorija=$_POST['kategorija'];
					if((isset($kategorija))){
						if(($kategorija!="0")){
							$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE k.id_kategorije='$kategorija' AND p.status='1' ORDER BY k.naziv_kategorije";
						}else{
							$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE p.status='1' ORDER BY k.naziv_kategorije ";
						}
					}
				}else if(isset($_POST['prikazi'])&&!($_POST['filter']=="0")){
						$filter=$_POST['filter'];
						$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE k.id_kategorije='$filter' AND p.status='1' ORDER BY k.naziv_kategorije";
				}else if(isset($_POST['prikazi'])&&($_POST['filter']=="0")){
					$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE p.status='1' ORDER BY k.naziv_kategorije";
				}else if(isset($_GET['kategorija'])){
					$k=$_GET['kategorija'];
					$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE p.id_kategorije='$k' AND p.status='1' ORDER BY k.naziv_kategorije";
				}else{
					$upit_p="SELECT * FROM podkategorije p JOIN kategorije k ON p.id_kategorije=k.id_kategorije WHERE p.status='1' ORDER BY k.naziv_kategorije";
				}
				$rezultat_p=mysql_query($upit_p,$konekcija);
				$broj_p=mysql_num_rows($rezultat_p);

				if($rezultat_p){
					if($broj_p>0){
						while($podkategorija=mysql_fetch_array($rezultat_p)){//ispis podkategorija u formi tabele
							echo "<tr>";
							echo "<td class='prva_kolona'>".$podkategorija['naziv_podkategorije']."</td>";
							echo "<td class='druga_kolona'>".$podkategorija['naziv_kategorije']."</td>";
							echo "<td class='prva_kolona'><a href='podkategorije.php?id=".$podkategorija['id_podkategorije']."&kategorija=".$podkategorija['id_kategorije']."'>Izbriši</a> / <a href='izmeni_podkategoriju.php?id=".$podkategorija['id_podkategorije']."' target='_BLANK'>Izmeni</a></td>";
							echo "</tr>";
						}
					}else{
						$ispis_brisanje = "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno ne postoji ne jedna podkategorija, dodajte podkategoriju</div>";
					}
				}
			?>
			</table>
			<?php
				if(isset($ispis_brisanje)){ echo $ispis_brisanje; };
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
