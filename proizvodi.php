<?php session_start();
	  include("logovanje.inc");

	  if(!isset($_GET['grupa'])){
	  	header('Location:index.php');
	  }
	  $grupa=$_GET['grupa'];

		$upit_podkategorija="SELECT id_podkategorije, naziv_podkategorije FROM podkategorije WHERE id_podkategorije='$grupa'";
		$rezultat_podkategorije=mysql_query($upit_podkategorija,$konekcija);
		$podkategorija=mysql_fetch_array($rezultat_podkategorije);

		$naziv_podkategorije = $podkategorija['naziv_podkategorije'];

		$title = $naziv_podkategorije . " | " . $site_name;
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

			$condition = "WHERE id_podkategorije='$grupa' AND status='1'";
			$paginator = pagination("proizvodi", "$condition", "&grupa=$grupa", "proizvodi.php");
			$pagination = $paginator['pagination'];
			$rowsperpage = $paginator['rowsperpage'];
			$offset = $paginator['offset'];

			$upit_racunari="SELECT id_proizvoda, naziv_proizvoda, cena_proizvoda, nova_cena, slika_proizvoda FROM proizvodi $condition ORDER BY cena_proizvoda LIMIT $rowsperpage OFFSET $offset";
			$rezultat_racunari=mysql_query($upit_racunari,$konekcija);
			$broj_racunari=mysql_num_rows($rezultat_racunari);
			echo "<div class='center_title_bar'>".$naziv_podkategorije."</div>";

			if($rezultat_racunari){
				if($broj_racunari>0){
					while($racunar=mysql_fetch_array($rezultat_racunari)){
						echo "<div class='prod_box'>";
						echo "<div class='top_prod_box'></div>";
						echo "<div class='center_prod_box'>";
						echo "<a href='proizvod.php?id_proizvoda=".$racunar['id_proizvoda']."'>";
						echo "<div class='product_title'>".$racunar['naziv_proizvoda']."</div>";
						echo "<div class='product_img'><img src='slike/proizvodi/" . $racunar['id_proizvoda'] . "/male/".$racunar['slika_proizvoda']."' alt='' title='' border='0' /></div>";
						echo "</a>";
						$stara=$racunar['cena_proizvoda'];
						$nova = $racunar['nova_cena'];
						if($nova<$stara){
							echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>Cena: ".$stara." RSD</span></div>";
							echo "<div class='prod_price'><span class='price'>Cena: ".$nova." RSD</span></div>";
						}else{
							echo "<div class='prod_price'><span class='price'style=''>Cena: <b>".$racunar['nova_cena']."</b> RSD</span></div>";
						}
						echo "</div>";

						$upit_detalji="SELECT * FROM detalji_proizvoda d JOIN atributi a ON d.id_atributa=a.id_atributa WHERE d.id_proizvoda='".$racunar['id_proizvoda']."' ORDER BY a.id_atributa LIMIT 4";
						$rezultat_detalji=mysql_query($upit_detalji,$konekcija);
						echo "<div class='prod_details_tab'>";

						while($detalj=mysql_fetch_array($rezultat_detalji)){
							echo "<p style='color:#000;'>".$detalj['naziv_atributa'].":</p><br/><p style='color:#555;'>".$detalj['vrednost']."</p><br/>";
						}
						echo "</div>";
						echo "<div class='bottom_prod_box'></div>";
						echo "</div>";
					}
					echo $pagination;
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nemamo u ponudi ni jedan proizvod</div>";
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
