<div class="slider-wrapper theme-default">
  <div id="slider" class="nivoSlider">
  	<?php
			$upit_slajder="SELECT * FROM slajder WHERE status='da'";
			$rezultat_slajder=mysql_query($upit_slajder,$konekcija);
			$broj_slajder=mysql_num_rows($rezultat_slajder);
			if($rezultat_slajder){
				if($broj_slajder>0){
					while($slajd=mysql_fetch_array($rezultat_slajder)){
						echo "<img src='slike/slajder_velike/".$slajd['slika']."' alt='".$slajd['opis']."' data-thumb='slike/slajder_velike/".$slajd['slika']."' data-transition='fade'/>";
					}
				}
			}
		?>
	</div>
</div>
