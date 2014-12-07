<?php session_start();
	  include("logovanje.inc");

    if($_SESSION['uloga'] == ""){
      header("Location:index.php");
    }

    $title = "Profil | " . $site_name;
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
		<div class="center_title_bar">Laptopovi</div>
		<?php

			$id=$_GET['id'];

			$upit_korisnik="SELECT * FROM korisnici k JOIN uloge u ON k.id_uloge=u.id_uloge WHERE id_korisnika='".$id."'";
			$rezultat_korisnik=mysql_query($upit_korisnik,$konekcija);
			$broj_korisnik=mysql_num_rows($rezultat_korisnik);

			if($rezultat_korisnik){
				if($broj_korisnik==1){
					$korisnik=mysql_fetch_array($rezultat_korisnik);
					$datum_rodjenja=date('d-m-Y', $korisnik['datum_rodjenja']);

					$datum_registracije=date('d-m-Y', $korisnik['datum_registracije']);
				}
			}
		?>
		<table style="margin-left:100px;">
				<tr>
                    <td class="prva_kolona">Ime:</td>
                    <td class="druga_kolona"><?php echo ucfirst($korisnik['ime']); ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Prezime:</td>
                    <td class="druga_kolona"><?php echo ucfirst($korisnik['prezime']); ?></td>
                </tr>
                <tr>
                    <td class="prva_kolona">Korisničko ime:</td>
                    <td class="druga_kolona"><?php echo ucfirst($korisnik['korisnicko_ime']); ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Email:</td>
                    <td class="druga_kolona"><?php echo strtolower($korisnik['email']); ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Telefon:</td>
                    <td class="druga_kolona"><?php echo $korisnik['telefon']; ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Datum rođenja:</td>
                    <td class="druga_kolona"><?php echo $datum_rodjenja; ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Pol:</td>
                    <td class="druga_kolona">
					<?php if($korisnik['pol']=='m'){
							$pol='Muški';
					}else{
						$pol='Ženski';
					}
					echo $pol;
					?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Grad:</td>
                    <td class="druga_kolona"><?php echo ucfirst($korisnik['grad']); ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Uloga:</td>
                    <td class="druga_kolona"><?php echo ucfirst($korisnik['naziv_uloge']); ?></td>
                </tr>
				<tr>
                    <td class="prva_kolona">Član od:</td>
                    <td class="druga_kolona">
					<?php echo $datum_registracije; ?></td>
                </tr>
				<?php
					if($uloga=="administrator"||$id_korisnika==$korisnik['id_korisnika']){
				?>
				<tr>
					<td colspan="2" class="prva_kolona" style="text-align:center;"><a href="izmeni_profil.php?id=<?php echo $id; ?>">Izmeni korisnički profil</a></td>
				</tr>
				<?php
					}
				?>
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
