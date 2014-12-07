<?php session_start();
	  include("logovanje.inc");

	  $title = "Akcije | " . $site_name;
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
		<div class="center_title_bar">Proizvodi na akciji</div>
		<?php

			$condition = "WHERE akcija='da' AND status='1'";
			$paginator = pagination("proizvodi", "$condition", "", "akcije.php");
			$pagination = $paginator['pagination'];
			$rowsperpage = $paginator['rowsperpage'];
			$offset = $paginator['offset'];

			$upit_akcija="SELECT id_proizvoda, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition ORDER BY rand() LIMIT $rowsperpage OFFSET $offset";
			$rezultat_akcija=mysql_query($upit_akcija,$konekcija);
			$broj_akcija=mysql_num_rows($rezultat_akcija);
			$ispis_str = "";
			if($rezultat_akcija){
				if($broj_akcija>0){
					while($akcija=mysql_fetch_array($rezultat_akcija)){
						echo "<div class='prod_box'>";
						echo "<div class='top_prod_box'></div>";
						echo "<div class='center_prod_box'>";
						echo "<a href='proizvod.php?id_proizvoda=".$akcija['id_proizvoda']."' >";
						echo "<div class='product_title'>".$akcija['naziv_proizvoda']."</div>";
						echo "<div class='product_img'><img src='slike/proizvodi/" . $akcija['id_proizvoda'] . "/male/".$akcija['slika_proizvoda']."' alt='' title='' border='0' /></div>";
						echo "</a>";
						$stara=$akcija['cena_proizvoda'];
						$nova = $akcija['nova_cena'];
						if($nova<$stara){
							echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$stara." RSD</span></div>";
							echo "<div class='prod_price'><span class='price'>".$nova." RSD</span></div>";
						}else{
							echo "<div class='prod_price'><span class='price'style=''>".$akcija['nova_cena']." RSD</span></div>";
						}
						echo "</div>";
						echo "<div class='bottom_prod_box'></div>";
						echo "</div>";
					}
					echo $pagination;
				}else{
              echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Nema proizvoda na akciji</div>";
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
