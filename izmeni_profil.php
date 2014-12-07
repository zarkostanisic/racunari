<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  $id_korisnika=$_SESSION['id_korisnika'];
	  $id = "";
	  if(isset($_GET['id'])){
		$id=$_GET['id'];
	  }
	  $id_k = "";
	  $izmena_korisnika = "";
	  if(isset($_POST['izmena_korisnika'])){
		$id_k=$_POST['izmena_korisnika'];
	  }
	  if($uloga!="administrator"&&$id_korisnika!=$id&&$id_korisnika!=$id_k){
		header("Location:index.php");
	  }

	  $title = "Izmeni korisnički profil | " . $site_name;
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
		<div class="center_title_bar">Izmeni korisnički profil</div>
		<?php
			include("funkcije.inc");

			$upit_korisnik="SELECT * FROM korisnici k JOIN uloge u ON k.id_uloge=u.id_uloge WHERE id_korisnika='$id' OR id_korisnika='$id_k'";//prikaz podataka o korisniku
			$rezultat_korisnik=mysql_query($upit_korisnik,$konekcija);
			$broj_korisnik=mysql_num_rows($rezultat_korisnik);

			if($broj_korisnik==1){
				$korisnik=mysql_fetch_array($rezultat_korisnik);
			}

			if(isset($_POST['registruj'])){
				$ime=$_POST['ime'];
				$prezime=$_POST['prezime'];
				$sifra=strtolower($_POST['sifra']);
				$sifra2=strtolower($_POST['sifra2']);
				$email=strtolower($_POST['email']);
				$telefon=$_POST['telefon'];
				$dan=$_POST['dan'];
				$mesec=$_POST['mesec'];
				$godina=$_POST['godina'];
				$datum_rodjenja=mktime(0, 0, 0, $mesec, $dan, $godina);
				$pol=strtolower($_POST['pol']);
				$grad=strtolower($_POST['grad']);
				$izmena_korisnika=$_POST['izmena_korisnika'];
				$reg_ime="^[A-ZŽĆČĐŠ]{1,2}[a-zžćčđš]{2,19}$";
				$reg_prezime="^[A-ZŽĆČĐŠ]{1,2}[a-zžćčđš]{2,19}$";
				$reg_sifra="^[a-z0-9]{5,}$";
				$reg_sifra2="^[a-z0-9]{5,}$";
				$reg_email="^[a-z]{1,}[\.\-]{0,1}[a-z]{0,}@[a-z]{2,}[\.]{1}[a-z]{2,3}$";
				$reg_telefon="^[0-9]{8,10}$";
				$reg_grad="^[a-z]{2,20}$";
				if(isset($_POST['uloga'])){
					$id_uloge=$_POST['uloga'];
				}

				$greske=array();

				if(@!ereg($reg_ime,$ime)){
					$greske[0]="*";
				}

				if(@!ereg($reg_prezime,$prezime)){
				$greske[1]="*";
				}

				if(@!ereg($reg_sifra,$sifra)){
					$greske[3]="*";
				}

				if(@!ereg($reg_sifra2,$sifra2)){
					$greske[4]="*";
				}

				if($sifra!=$sifra2){
					$greske[3]="*";
					$greske[4]="*";
				}

				if(@!ereg($reg_email,$email)){
					$greske[5]="*";
				}

				if(@!ereg($reg_telefon,$telefon)){
					$greske[6]="*";
				}

				if($dan=="0"||$mesec=="0"||$godina=="0"){
					$greske[7]="*";
				}
				if($pol==""){
					$greske[8]="*";
				}
				if(@!ereg($reg_grad,$grad)){
					$greske[9]="*";
				}
				if(count($greske)==0){ /*ako nema gresaka*/
					if($uloga=="administrator"){
						$upit_izmena="UPDATE korisnici SET ime='$ime',prezime='$prezime',sifra='$sifra',email='$email',telefon='$telefon',id_uloge='$id_uloge',datum_rodjenja='$datum_rodjenja',pol='$pol',grad='$grad' WHERE id_korisnika='$izmena_korisnika'";
					}else{
						$upit_izmena="UPDATE korisnici SET ime='$ime',prezime='$prezime',sifra='$sifra',email='$email',telefon='$telefon',datum_rodjenja='$datum_rodjenja',pol='$pol',grad='$grad' WHERE id_korisnika='$izmena_korisnika'";
					}
					$rezultat_izmena=mysql_query($upit_izmena,$konekcija);
					if($rezultat_izmena){
						$ispis_izmena="<div class='komentar' style='margin-left:70px;text-align:center;'>Uspešno ste izmenili profil</div>";
					}
				}
			}
			$upit_korisnik="SELECT * FROM korisnici k JOIN uloge u ON k.id_uloge=u.id_uloge WHERE id_korisnika='$izmena_korisnika'";//prikaz informacija izmenjenog korisnika
			$rezultat_korisnik=mysql_query($upit_korisnik,$konekcija);
			$broj_korisnik=mysql_num_rows($rezultat_korisnik);

			if($broj_korisnik==1){
				$korisnik=mysql_fetch_array($rezultat_korisnik);
			}

			$dan = date('d', $korisnik['datum_rodjenja']);
			$mesec = date('m', $korisnik['datum_rodjenja']);
			$godina = date('Y', $korisnik['datum_rodjenja']);
		?>
		<table style="margin-left:140px;">
		<form name="izmena_profila" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
		<tr>
			<td class="prva_kolona">Ime:</td>
			<td class="druga_kolona"><input type="text" name="ime" class="text_input" value="<?php echo $korisnik['ime']; ?>"/></td><td><?php if(isset($greske[0])){echo "<span style='color:red;'>$greske[0]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Prezime:</td>
			<td class="druga_kolona"><input type="text" name="prezime" class="text_input" value="<?php echo $korisnik['prezime']; ?>"/></td><td><?php if(isset($greske[1])){echo "<span style='color:red;'>$greske[1]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Šifra:</td>
			<td class="druga_kolona"><input type="password" name="sifra" class="text_input" value="<?php echo $korisnik['sifra']; ?>"/></td><td><?php if(isset($greske[3])){echo "<span style='color:red;'>$greske[3]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Ponovi šifru:</td>
			<td class="druga_kolona"><input type="password" name="sifra2" class="text_input" value="<?php echo $korisnik['sifra']; ?>"/></td><td><?php if(isset($greske[4])){echo "<span style='color:red;'>$greske[4]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Email:</td>
			<td class="druga_kolona"><input type="text" name="email" class="text_input" value="<?php echo $korisnik['email']; ?>"/></td><td><?php if(isset($greske[5])){echo "<span style='color:red;'>$greske[5]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Telefon:</td>
			<td class="druga_kolona"><input type="text" name="telefon" class="text_input" value="<?php echo $korisnik['telefon']; ?>"/></td><td><?php if(isset($greske[6])){echo "<span style='color:red;'>$greske[6]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Datum rođenja:</td>
			<td class="druga_kolona">
				<select name="dan" style="width:46px;">
					<option value="0">Dan</option>
					<?php echo ispisi_dan($dan); ?>
				</select>
				<select name="mesec" style="width:46px;">
					<option value="0">Mesec</option>
					<?php echo ispisi_mesec($mesec); ?>
				</select>
				<select name="godina" style="width:46px;">
					<option value="0">Godina</option>
					<?php echo ispisi_godinu($godina); ?>
				</select>
			</td>
			<td><?php if(isset($greske[7])){echo "<span style='color:red;'>$greske[7]</span>";} ?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Pol:</td>
			<td class="druga_kolona">
				<input type="radio" name="pol" value="m" <?php if($korisnik['pol']=="m"){echo "checked";}?>/>M
				<input type="radio" name="pol" value="z" <?php if($korisnik['pol']=="z"){echo "checked";} ?>/>Z
			</td>
			<td><?php if(isset($greske[8])){echo "<span style='color:red;'>$greske[8]</span>"; }?></td>
		</tr>
		<tr>
			<td class="prva_kolona">Grad:</td>
			<td class="druga_kolona"><input type="text" name="grad" class="text_input" value="<?php echo $korisnik['grad']; ?>"/></td><td><?php if(isset($greske[9])){echo "<span style='color:red;'>$greske[9]</span>"; }?></td>
		</tr>
		<?php if($uloga=="administrator"){?>
		<tr>
			<td class="prva_kolona">Uloga:</td>
			<td class="druga_kolona">
				<select name="uloga" class="select">
					<?php
						$upit_uloga="SELECT * FROM uloge";
						$rezultat_uloga=mysql_query($upit_uloga,$konekcija);
						$broj_uloga=mysql_num_rows($rezultat_uloga);
						if($broj_uloga>0){
							while($uloga=mysql_fetch_array($rezultat_uloga)){
								if($korisnik['id_uloge']==$uloga['id_uloge']){
									echo "<option value='".$uloga['id_uloge']."' selected>".$uloga['naziv_uloge']."</option>";
								}else{
									echo "<option value='".$uloga['id_uloge']."'>".$uloga['naziv_uloge']."</option>";
								}
							}
						}
					?>
				</select>
			</td><td></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2">
				<input type="hidden" name="izmena_korisnika" value="<?php echo $korisnik['id_korisnika']; ?>"/>
				<input  type="submit" name="registruj" value="Izmeni" class="add_bt"/>
			</td><td></td>
		</tr>
		</form>
		</table>
		<?php if(isset($ispis_izmena)){ echo $ispis_izmena; } ?>
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
