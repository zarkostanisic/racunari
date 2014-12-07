<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Dodaj slajder | Administracija | " . $site_name;
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
		<div class="center_title_bar">Dodaj slajder</div>
		<form name="dodaj_slajder" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
		<table style="margin-left:80px;">
		<tr>
			<td class="prva_kolona">Naziv:</td>
			<td class="druga_kolona"><input type="text" name="naziv" class="text_input"/></td>
		</tr>
		<tr>
			<td class="prva_kolona">Opis:</td>
			<td class="druga_kolona"><input type="text" name="opis" class="text_input"/></td>
		</tr>
		<tr>
			<td class="prva_kolona">Slika:</td>
			<td class="druga_kolona"><input type="file" name="slika"/></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;"><input type="submit" name="dodaj" value="Dodaj" class="add_bt"/><input type='reset' class='add_bt'/></td>
		</tr>
			<?php
				include("funkcije_slike.inc");
					$ispis = "";
					if(isset($_POST['dodaj'])){
						$naziv=$_POST['naziv'];
						$opis=$_POST['opis'];
						$slika=$_FILES['slika'];
						list($width, $height, $type, $attr) = @getimagesize($slika['tmp_name']);
						$reg_naziv="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš ]{2,29}$";
						$reg_opis="^[A-ZŽĆČĐŠ]{1}[A-Za-zŽĆČĐŠžćčđš\.\, ]{2,49}$";
						$datum=@date("dmYHis");
						$greske=array();

						if(@!ereg($reg_naziv,$naziv)){
							$greske[]="Greška naziv";
						}

						if(@!ereg($reg_opis,$opis)){
							$greske[]="Greška opis";
						}

						if($slika['type']!="image/jpg"&&$slika['type']!="image/jpeg"){
							$greske[]="Greška tip slike";
						}

						if($width!="1000"||$height!="200px"){
							$greske[]="Dimenzije slike moraju biti 1000 X 200";
						}

						if(count($greske)==0){
							if(move_uploaded_file($slika['tmp_name'],"slike/slajder_velike/".$datum."_".$slika['name'])){
								malaslika("slike/slajder_velike/".$datum."_".$slika['name'],"slike/slajder_male/".$datum."_".$slika['name'],150,150);
                            }

							$upit_unos_slajdera="INSERT INTO slajder VALUES('','".$naziv."','".$opis."','da','".$datum."_".$slika['name']."')";
							$rezultat_unos_slajdera=mysql_query($upit_unos_slajdera,$konekcija);
							if($rezultat_unos_slajdera){
								$ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Slika uspešno dodata</div>";
							}else{
								$ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri dodavanju</div>";
							}
						}else{
							foreach($greske as $greska){
								$ispis .= "<div class='komentar' style='margin-left:70px;text-align:center;'>".$greska."</div>";
							}
						}

					}
			?>
		</table>
		<?php echo $ispis; ?>
		</form>
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
