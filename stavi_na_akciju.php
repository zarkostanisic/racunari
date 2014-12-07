<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Stavi na akciju | Administracija | " . $site_name;
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
			$id_proizvoda = "";
			$proizvod = "";
			if(isset($_GET['id_proizvoda'])){
				$id_proizvoda=$_GET['id_proizvoda'];
			}
			$ispis_akcija = "";
			if(isset($_POST['stavi'])){
				$proizvod=$_POST['proizvod'];
				$stavi_na_akciju=$_POST['stavi_na_akciju'];
				$popust=$_POST['popust'];
				$stara_cena = $_POST['stara_cena'];
				$snizenje = "0." . $popust;
				$nova_cena = $stara_cena - ($snizenje * $stara_cena);
				$upit_akcija="UPDATE proizvodi SET akcija='$stavi_na_akciju',nova_cena='$nova_cena', snizenje='$popust' WHERE id_proizvoda='$proizvod'";
				$rezultat_akcija=mysql_query($upit_akcija,$konekcija);

				if($upit_akcija){
					$ispis_akcija.="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno</div>";
				}else{
					$ispis_akcija.="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška</div>";
				}
			}

			$upit_proizvod="SELECT * FROM proizvodi WHERE id_proizvoda='$id_proizvoda' OR id_proizvoda='$proizvod'";
			$rezultat_proizvod=mysql_query($upit_proizvod,$konekcija);
			$broj_proizvod=mysql_num_rows($rezultat_proizvod);

			if($rezultat_proizvod){
				if($broj_proizvod==1){
					$proizvod=mysql_fetch_array($rezultat_proizvod);
					echo "<div class='center_title_bar'>Stavljate na akciju proizvod: "."<p style='color:white;display:inline;'>".$proizvod['naziv_proizvoda']."</p>"."</div>";
					echo "<table style='margin-left:150px;'>";
					echo "<form name='akcija' action='".$_SERVER['PHP_SELF']."' method='post'>";
					echo "<tr>";
					echo "<td class='prva_kolona' >Stavi na akciju:</td>";
					echo "<td class='druga_kolona' style='width:70px;'>";
					echo "<select name='stavi_na_akciju' class='select' style='width:70px;'>";
					if($proizvod['akcija']=="ne"){
					echo "<option value='ne' selected>Ne</option>";
					echo "<option value='da'>Da</option>";
					}
					if($proizvod['akcija']=="da"){
					echo "<option value='da' selected>Da</option>";
					echo "<option value='ne' >Ne</option>";
					}
					echo "</select>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td class='prva_kolona'>Popust:</td>";
					echo "<td class='druga_kolona' style='width:70px;'>";
					echo "<select name='popust' class='select' style='width:70px;'>";
					echo "<option value='0'>0%</option>";
					for($i=1;$i<=5;$i++){
						$a=$i."0";
						if($a==$proizvod['snizenje']){
							echo "<option value='".$i."0' selected>".$i."0%</option>";
						}else{
							echo "<option value='".$i."0'>".$i."0%</option>";
						}
					}
					echo "</select>";
					echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td colspan='2' style='text-align:center;'>
						<input type='hidden' name='proizvod' value='".$proizvod['id_proizvoda']."'/>
						<input type='hidden' name='stara_cena' value='".$proizvod['cena_proizvoda']."'/>
						<input type='submit' name='stavi' value='stavi' class='add_bt'/>
					</td>";
					echo "</tr>";
					echo "</form>";
					echo "</table>";
				}
			}
		?>
		<?php echo $ispis_akcija; ?>
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
