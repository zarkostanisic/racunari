<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Kategorije | Administracija | " . $site_name;
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
			//mysql_query("update podkategorije set status='1'", $konekcija);mysql_query("update proizvodi set status='1'", $konekcija);mysql_query("update kategorije set status='1'", $konekcija);
			if(isset($_GET['id'])){
				$id=$_GET['id'];//id kategorije
				$ispis = "";
				$upit_brisanje_kategorija="UPDATE kategorije SET status='0' WHERE id_kategorije='$id'";//brisanje kategorija
				$rezultat_brisanje_kategorija=mysql_query($upit_brisanje_kategorija,$konekcija);
				if($rezultat_brisanje_kategorija){
					$podkategorije = "SELECT id_podkategorije FROM podkategorije WHERE id_kategorije='$id'";
					$rez_podkategorije = mysql_query($podkategorije, $konekcija);

					if(mysql_num_rows($rez_podkategorije) > 0){
						$niz_podkategorija = "";
						while($podkategorija = mysql_fetch_array($rez_podkategorije)){
							$niz_podkategorija[] = $podkategorija['id_podkategorije'];

						}
						$ids = join(',',$niz_podkategorija);
						mysql_query("UPDATE proizvodi SET status='0' WHERE id_podkategorije IN($ids)", $konekcija);
					}
					$upit_brisanje_podkategorije = "UPDATE podkategorije SET status='0' WHERE id_kategorije='$id'";
					$rezultat_brisanje_podkategorija = mysql_query($upit_brisanje_podkategorije, $konekcija);

					$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Kategorija uspešno obrisana</div>";
				}else{
					$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri brisanju</div>";
				}
			}
		?>
		<div class="center_title_bar">Kategorije(dodavanje, brisanje, izmena)</div>
		<table>
			<form name="dodaj_k" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<tr>
				<td class="prva_kolona">Naziv kategorije:</td>
				<td class="druga_kolona"><input type="text" name="naziv_kategorije" class="text_input"/></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="dodaj_kategoriju" value="Dodaj" class="add_bt"/><input type='reset' class='add_bt'/></td>
			</tr>
			</form>
		</table>
		<?php
			if(isset($_POST['dodaj_kategoriju'])){//dodavanje kategorije
				$naziv_kategorije=$_POST['naziv_kategorije'];

				$reg_naziv_kategorije="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš ]{2,19}$";

				$greske=array();

				if(@!ereg($reg_naziv_kategorije,$naziv_kategorije)){
					$greske[]="Greška naziv kategorije";
				}

				if(count($greske)==0){
					$upit="INSERT INTO kategorije VALUES('','".$naziv_kategorije."', '1')";
					$rezultat=mysql_query($upit,$konekcija);

					$last_id = mysql_insert_id();

					if($rezultat){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Kategorija je uspešno dodata, <a href='podkategorije.php?kategorija=$last_id' style='color:red;text-decoration:underline;' target='_BLANK'>dodajte podkategorije</a></div>";
					}else{
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri dodavanju</div>";
					}
				}else{
					foreach($greske as $greska){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}
			}
		?>
		<table>
			<tr>
				<th class="naslov_tabele">Naziv kategorije</th>
				<th class="naslov_tabele">Brisanje</th>
				<th class="naslov_tabele">Izmena</th>
			</tr>
		<?php

			$upit_k="SELECT * FROM kategorije WHERE status='1'";//prikaz kategorija u formi tabele
			$rezultat_k=mysql_query($upit_k,$konekcija);
			$broj_k=mysql_num_rows($rezultat_k);

			if($rezultat_k){
				if($broj_k>0){
					while($kategorija=mysql_fetch_array($rezultat_k)){
						echo "<tr>";
						echo "<td class='prva_kolona'>".$kategorija['naziv_kategorije']."</td>";
						echo "<td class='druga_kolona'><a href='".$_SERVER['PHP_SELF']."?id=".$kategorija['id_kategorije']."'>Izbriši</a></td>";
						echo "<td class='prva_kolona'><a href='izmeni_kategoriju.php?id=".$kategorija['id_kategorije']."' target='_BLANK'>Izmeni</a></td>";
						echo "</tr>";
					}
				}else{
					$ispis .= "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno ne postoji ni jedna kategorija, dodajte kategoriju</div>";
				}
			}
		?>
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
