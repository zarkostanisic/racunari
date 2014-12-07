<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga==""){
		header("Location:index.php");
	  }

	  $title = "Login | " . $site_name;
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
		<div class="center_title_bar">Izdvajamo iz ponude</div>
		<?php
			$upit_proizvodi="SELECT pr.id_proizvoda, pr.naziv_proizvoda, pr.cena_proizvoda, pr.nova_cena, pr.slika_proizvoda FROM proizvodi pr JOIN podkategorije po ON pr.id_podkategorije=po.id_podkategorije WHERE pr.akcija='da' ORDER BY RAND() LIMIT 9";
			$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
			$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

			if($rezultat_proizvodi){
				if($broj_proizvodi>0){
					while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
						echo "<div class='prod_box'>";
						echo "<div class='top_prod_box'></div>";
						echo "<div class='center_prod_box'>";
						echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>";
						echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
						echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
						echo "</a>";
						$stara=$proizvod['cena_proizvoda'];
						$nova = $proizvod['nova_cena'];
						if($nova<$stara){
							echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
							echo "<div class='prod_price'><span class='price'>".$nova_cena." din</span></div>";
						}else{
							echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
						}
						echo "</div>";
						echo "<div class='bottom_prod_box'></div>";
						echo "</div>";
					}
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
