<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Dodaj proizvode | Administracija | " . $site_name;
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

	function prikazi_f(){
		if(httpObject.readyState == 4){
			document.getElementById('ispisi_formu').innerHTML = httpObject.responseText;
		}
	}

	function prikazi_formu(){
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "ajax.php?tip_proizvoda=" +document.getElementById('tip_proizvoda').value, true);
			httpObject.send(null);
			httpObject.onreadystatechange = prikazi_f;
		}
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
			<?php include("funkcije_slike.inc"); ?>
			<div class="center_title_bar">Dodaj proizvod</div>
			<form name="dodaj_proizvod" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<table style="margin-left:130px;">
				<tr>
					<td class="prva_kolona">Tip proizvoda:</td>
					<td class="druga_kolona">
						<select name="tip_proizvoda" onChange="prikazi_formu();" id="tip_proizvoda" class="select"><!--izvlaci atribute za proizvod-->
							<option value="0">Izaberi</option>
							<?php
								$upit="SELECT id_podkategorije, naziv_podkategorije FROM podkategorije WHERE status='1'";//prikaz podkategorije
								$rezultat=mysql_query($upit,$konekcija);
								$broj=mysql_num_rows($rezultat);
								$tip_proizvoda=$_POST['tip_proizvoda'];
								if($rezultat){
									if($broj>0){
										while($podkategorija=mysql_fetch_array($rezultat)){
											if($tip_proizvoda==$podkategorija['id_podkategorije']){
												echo "<option value='".$podkategorija['id_podkategorije']."' selected>".$podkategorija['naziv_podkategorije']."</option>";
											}else{
												echo "<option value='".$podkategorija['id_podkategorije']."'>".$podkategorija['naziv_podkategorije']."</option>";
											}
										}
									}
								}
							?>
						</select>
					</td>
					<td></td>
				</tr>
			</table>
			<table id="ispisi_formu" style="margin-left:100px;">
			<?php
				if(isset($_POST['dodaj_proizvod'])){//dodavanje proizvoda
					$naziv_atributa=$_POST['naziv_atributa'];
					$atribut=$_POST['atribut'];
					$naziv_proizvoda=$_POST['naziv_proizvoda'];
					$slika_proizvoda=$_FILES['slika_proizvoda'];
					$cena_proizvoda=$_POST['cena_proizvoda'];

					$reg_cena_proizvoda="^[0-9]{1,10}\.[0-9]{2}$";

					$greske=array();

					if($naziv_proizvoda == ""){
						$greske[]="Greska naziv proizvoda";
					}

					if($slika_proizvoda['name']!=""){
						if($slika_proizvoda['type']!="image/jpg"&&$slika_proizvoda['type']!="image/jpeg"&&$slika_proizvoda['type']!="image/png"){
							$greske[]="Greska tip slike";
						}
					}else{
						$greske[]="Morate izabrati sliku";
					}

					if(@!ereg($reg_cena_proizvoda,$cena_proizvoda)){
						$greske[]="Greska cena";
					}

					if(isset($tip_proizvoda)){//ispisivanje forme za unos
						$upit="SELECT id_atributa, naziv_atributa FROM atributi WHERE id_podkategorije='$tip_proizvoda' ORDER BY id_atributa";
						$rezultat=mysql_query($upit,$konekcija);
						$broj=mysql_num_rows($rezultat);

						if($rezultat){
							if($broj>0){
								echo "<tr>";
								echo "<td class='prva_kolona'>Naziv proizvoda:</td>";
								echo "<td class='druga_kolona'><input type='text' name='naziv_proizvoda' class='text_input' value='$naziv_proizvoda'/></td>";
								echo "<td></td>";
								echo "</tr>";
								$i=0;
								while($atributi=mysql_fetch_array($rezultat)){
									echo "<tr>";
									echo "<td class='prva_kolona'>".$atributi['naziv_atributa'].":</td>";
									echo "<td class='druga_kolona'><input type='text' name='naziv_atributa[]' class='text_input' value='$naziv_atributa[$i]'/></td>";
									echo "<td><input type='hidden' name='atribut[]' value='".$atributi['id_atributa']."'/></td>";
									echo "</tr>";
								$i++;
								}
								echo "<tr>";
								echo "<td class='prva_kolona'>Slika proizvoda</td>";
								echo "<td class='druga_kolona'><input type='file' name='slika_proizvoda'/></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td class='prva_kolona'>Cena proizvoda</td>";
								echo "<td class='druga_kolona'><input type='text' name='cena_proizvoda' class='text_input' value='$cena_proizvoda'/></td>";
								echo "</tr>";
								echo "<tr><td></td><td><input type='submit' name='dodaj_proizvod' value='Dodaj' class='add_bt'/><input type='reset' class='add_bt'/><td></tr>";
							}
						}
					}

					if(count($greske)==0){
						$novi_naziv_proizvoda = strtolower(str_replace(' ', '', $naziv_proizvoda));

						$f = explode('.', $slika_proizvoda['name']);
  					$ext = $f[count($f)-1];
  					$novi_naziv =  $novi_naziv_proizvoda.'_'.time().'.'.$ext;

						$upit_i_p="INSERT INTO proizvodi VALUES('','".$tip_proizvoda."','".$naziv_proizvoda."','ne','0','".$novi_naziv."','".$cena_proizvoda."','".$cena_proizvoda."','1')";//dodavanje u tabelu proizvodi
						$rezultat_i_p=mysql_query($upit_i_p,$konekcija);

						if($rezultat_i_p){
							$last_id = mysql_insert_id();

							$folder_proizvodi = 'slike/proizvodi/' . $last_id . '/';
							$folder_male = 'slike/proizvodi/' . $last_id . '/male/';
							$folder_velike = 'slike/proizvodi/' . $last_id . '/velike/';

							if(!file_exists($folder_proizvodi) && !file_exists($folder_male) && !file_exists($folder_velike)){
								@mkdir($folder_proizvodi);
								@mkdir($folder_male);
								@mkdir($folder_velike);
							}

							if(@move_uploaded_file($slika_proizvoda['tmp_name'],$folder_proizvodi.$novi_naziv)){
								@malaslika($folder_proizvodi.$novi_naziv,$folder_male.$novi_naziv,120,120);
								@malaslika($folder_proizvodi.$novi_naziv,$folder_velike.$novi_naziv,160,160);
							}

							if(isset($last_id)){
								for($i=0;$i<count($atribut);$i++){//dodavanje u tabelu detalji proizvoda na osnovu zadnjeg zapisa u tabeli proizvodi
									if($naziv_atributa[$i] == ""){
										$naziv_atributa[$i] = "-";
									}
									$upit_i_d="INSERT INTO detalji_proizvoda VALUES('','". $last_id ."','".$atribut[$i]."','".$naziv_atributa[$i]."')";
									$rezultat_i_d=mysql_query($upit_i_d,$konekcija);
								}
								if($rezultat_i_d){
									$greske[] = "Proizvod je uspešno dodat";
								}else{
									$greske[] =  "Greška pri dodavanju";
								}
							}
						}
					}
				}
			?>
			</table>
			<?php
				if(isset($greske)){
					foreach($greske as $greska){
						echo "<div class='komentar' style='margin-left:70px;text-align:center;'>$greska</div>";
					}
				}
			?>
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
