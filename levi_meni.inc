<?php
	$upit_k="SELECT * FROM kategorije WHERE status='1'";
	$rezultat_k=mysql_query($upit_k,$konekcija);
	$broj_k=mysql_num_rows($rezultat_k);

	$upit_p="SELECT * FROM podkategorije WHERE status='1'";
	$rezultat_p=mysql_query($upit_p,$konekcija);

	$podkategorije = array();

	while($podkategorija = mysql_fetch_array($rezultat_p)){
		$podkategorije[] = array(
			'id_podkategorije' => $podkategorija['id_podkategorije'],
			'naziv_podkategorije' => $podkategorija['naziv_podkategorije'],
			'id_kategorije' => $podkategorija['id_kategorije'],
		);
	}

	if($rezultat_k){
		if($broj_k>0){
			while($kategorija=mysql_fetch_array($rezultat_k)){
					echo "<div id='left_menu'>";
					echo "<div class='title_box'>".$kategorija['naziv_kategorije']."</div>";
					echo "<ul class='left_menu'>";
					$i=0;
					foreach($podkategorije as $podkategorija){
						if($kategorija['id_kategorije'] == $podkategorija['id_kategorije']){
							if($i%2==0){
								echo "<li class='odd'><a href='proizvodi.php?grupa=".$podkategorija['id_podkategorije']."'>".$podkategorija['naziv_podkategorije']."</a></li>";
							}else{
								echo "<li class='even'><a href='proizvodi.php?grupa=".$podkategorija['id_podkategorije']."'>".$podkategorija['naziv_podkategorije']."</a></li>";
							}
							$i++;
						}
					}
					echo "</ul>";
					echo "<div class='title_bottom'></div>";
					echo "</div>";
			}
		}
	}
?>
