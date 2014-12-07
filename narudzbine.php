<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Narudžbine | Administracija | " . $site_name;
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
		<div class="center_title_bar">Moje porudžbine</div>
		<?php
			$paginator = pagination("narudzbine", "", "", "narudzbine.php");
			$pagination = $paginator['pagination'];
			$rowsperpage = $paginator['rowsperpage'];
			$offset = $paginator['offset'];

			$upit_narudzbine="SELECT n.datum, n.status, n.id_narudzbine, k.korisnicko_ime FROM narudzbine n JOIN korisnici k ON n.id_korisnika=k.id_korisnika ORDER BY n.status, n.datum  DESC LIMIT $rowsperpage OFFSET $offset";
			$rezultat_narudzbine=mysql_query($upit_narudzbine,$konekcija);
			$broj_narudzbine=mysql_num_rows($rezultat_narudzbine);

			if($rezultat_narudzbine){
				if($broj_narudzbine>0){
					echo '<table style="margin-left:100px;">
						<tr>
							<td class="prva_kolona short">ID</td>
							<td class="druga_kolona short">Datum</td>
							<td class="prva_kolona short">Korisnik</td>
							<td class="prva_kolona short">Status</td>
						</tr>';

					while($narudzbina=mysql_fetch_array($rezultat_narudzbine)){
						if($narudzbina['status'] == 0){
							$status = "Nerealizovana";
						}else if($narudzbina['status'] == 1){
							$status = "Realizovana";
						}else if($narudzbina['status'] == 2){
							$status = "Poništena";
						}

						echo '<tr><td class="prva_kolona short">' . $narudzbina['id_narudzbine'] . '</td>
						<td class="druga_kolona short">' . date('d.m.Y H:i:s', $narudzbina['datum']) . '</td>
						<td class="prva_kolona short">' . $narudzbina['korisnicko_ime'] . '</td>
						<td class="druga_kolona short">' . $status . '</td>
						<td class="short"><a href="pregled_narudzbine.php?id=' . $narudzbina['id_narudzbine'] . '" class="search_bt pregled">Pregled</a></td></tr>';
					}
					echo '</table>';
					echo $pagination;
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nemate narudžbina</div>";
				}
			}
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
