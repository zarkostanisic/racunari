<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izmeni slajder | Administracija | " . $site_name;
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
	<div class="center_title_bar">Izmeni slajder</div>
		<?php
				if(isset($_POST['izmeni'])){
					$statusi=$_POST['statusi'];
					$upit="UPDATE slajder SET status='ne'";
					$rezultat=mysql_query($upit,$konekcija);
					if($rezultat){
						if(count($statusi)>0){
							for($i=0;$i<count($statusi);$i++){
								$upit_izmena="UPDATE slajder SET status='da' WHERE id_slajdera='".$statusi[$i]."'";
								$rezultat_izmena=mysql_query($upit_izmena,$konekcija);
							}
							if($rezultat_izmena){
								$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Slajder uspešno izmenjen</div>";
							}else{
								$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri izmeni</div>";
							}
						}else{
							$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Slajder uspešno izmenjen</div>";
						}
					}
				}

				if(isset($_GET['id'])){
					$id=$_GET['id'];

					$upit_slajder="SELECT * FROM slajder WHERE id_slajdera='$id'";
					$rezultat_slajder=mysql_query($upit_slajder,$konekcija);
					$broj_slajder=mysql_num_rows($rezultat_slajder);
					if($rezultat_slajder){
						if($broj_slajder==1){
							$slajder=mysql_fetch_array($rezultat_slajder);
						}
					}
					$upit_brisanje="DELETE FROM slajder WHERE id_slajdera='$id'";
					$rezultat_brisanje=mysql_query($upit_brisanje,$konekcija);
					@unlink("slike/".$slajder['slika']);
					@unlink("slike/slajder_male/".$slajder['slika']);
					@unlink("slike/slajder_velike/".$slajder['slika']);
					if($rezultat_brisanje){
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center;'>Slika uspešno izbrisana</div>";
					}else{
						$ispis="<div class='komentar' style='margin-left:70px;text-align:center'>Greška pri brisanju</div>";
					}
				}

				$upit_prikaz="SELECT * FROM slajder";
				$rezultat_prikaz=mysql_query($upit_prikaz,$konekcija);
				$broj_prikaz=mysql_num_rows($rezultat_prikaz);
				if($rezultat_prikaz){
					if($broj_prikaz>0){
						echo "<form name='izmeni_slajder' action='".$_SERVER['PHP_SELF']."' method='post'>";
						echo "<table style='margin-left:130px;'>";
						echo "<tr>";
						echo "<th class='naslov_tabele' style='width:50px;'>Status</th>";
						echo "<th class='naslov_tabele'>Slika</th>";
						echo "<th class='naslov_tabele' style='width:70px;'>Opcija</th>";
						echo "</tr>";
						while($slajd=mysql_fetch_array($rezultat_prikaz)){
							echo "<tr>";
							echo "<td class='prva_kolona' style='width:50px;'><input type='checkbox' name='statusi[]' value='".$slajd['id_slajdera']."' style='margin-left:12px;'";
							if($slajd['status']=="da"){
								echo "checked";
							}
							echo "/></td>";
							echo "<td class='druga_kolona'><img src='slike/slajder_male/".$slajd['slika']."'/></td>";
							echo "<td class='prva_kolona' style='width:70px;'><a href='izmeni_slajder.php?id=".$slajd['id_slajdera']."'>Izbriši</a></td>";
							echo "</tr>";
						}
						echo "<tr>";
						echo "<td colspan='3'><input type='submit' name='izmeni' value='Izmeni' class='add_bt'/></td>";
						echo "</tr>";
						echo "</table>";
						echo "</form>";
					}else{
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema slika, dodajte sliku</div>";
					}
				}
		?>
		<?php
			if(isset($ispis)) { echo $ispis; };
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
