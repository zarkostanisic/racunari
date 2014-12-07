<?php
	echo "<div id='left_menu'>";
	echo "<div class='korpa title_box'>Korpa</div>";
	echo "<ul class='left_menu'>";
	echo "<li class='odd'><a href='korpa.php'><span class='prikazi_broj'></span></a></li>";
	echo "</ul>";
	echo "<div class='title_bottom'></div>";
	echo "</div>";
	$upit_p="SELECT id_proizvoda, naziv_proizvoda FROM proizvodi WHERE status='1' ORDER BY cena_proizvoda LIMIT 5";
	$rezultat_p=mysql_query($upit_p,$konekcija);
	$broj_p=mysql_num_rows($rezultat_p);

	if($rezultat_p){
		if($broj_p>0){
			echo "<div id='left_menu'>";
			echo "<div class='title_box'>Najpovoljniji proizvodi</div>";
			echo "<ul class='left_menu'>";
			$i=0;
			while($proizvod=mysql_fetch_array($rezultat_p)){
						$i++;
						if($i%2==0){
							echo "<li class='odd'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
						}else{
							echo "<li class='even'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
						}
			}
			echo "</ul>";
			echo "<div class='title_bottom'></div>";
			echo "</div>";
		}
	}
	$upit_p="SELECT id_proizvoda, naziv_proizvoda FROM proizvodi WHERE status='1' ORDER BY cena_proizvoda LIMIT 5";
	$rezultat_p=mysql_query($upit_p,$konekcija);
	$broj_p=mysql_num_rows($rezultat_p);

	if($rezultat_p){
		if($broj_p>0){
			echo "<div id='left_menu'>";
			echo "<div class='title_box'>Najskuplji proizvodi</div>";
			echo "<ul class='left_menu'>";
			$i=0;
			while($proizvod=mysql_fetch_array($rezultat_p)){
						$i++;
						if($i%2==0){
							echo "<li class='odd'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
						}else{
							echo "<li class='even'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
						}
			}
			echo "</ul>";
			echo "<div class='title_bottom'></div>";
			echo "</div>";
		}
	}
	$upit_proizvodi="SELECT id_proizvoda, naziv_proizvoda FROM proizvodi WHERE status='1' ORDER BY cena_proizvoda LIMIT 5";
	$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
	$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

	if($rezultat_proizvodi){
		if($broj_proizvodi>0){
			echo "<div id='left_menu'>";
			echo "<div class='title_box'>Najnoviji proizvodi</div>";
			echo "<ul class='left_menu'>";
			while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
				$i++;
				if($i%2==0){
					echo "<li class='odd'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
				}else{
					echo "<li class='even'><a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a></li>";
				}
			}
			echo "</ul>";
			echo "<div class='title_bottom'></div>";
			echo "</div>";
		}
	}
?>
