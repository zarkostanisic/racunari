<?php
	@session_start();
	include_once("konekcija.inc");
	include_once("paginacija.inc");

	/*Narucivanje*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'naruci'){
		if(isset($_SESSION['id_korisnika'])){
			$korisnik = $_SESSION['id_korisnika'];
			$total = $_POST['total'];

			$upit_naruci = "INSERT INTO narudzbine VALUES('','" . $korisnik . "','" . time() . "','0', '" . $total . "', '0')";
			$rez_naruci = mysql_query($upit_naruci, $konekcija);
			$id = mysql_insert_id();
			$i = FALSE;
			if($rez_naruci){
				$cart = $_SESSION['cart'];
				foreach($cart as $item){
					$upit_artikli = "INSERT INTO artikli VALUES('', '" . $id . "', '" . $item['id'] . "', '" . $item['kolicina'] . "')";
					$rez_art = mysql_query($upit_artikli, $konekcija);

					if($rez_art){
						$i = TRUE;
					}
				}
			}
			if($i == TRUE){
				unset($_SESSION['cart']);
				echo "Porudžbina je poslata";
			}
		}else{
			echo "Morate biti ulogovani";
		}
	}

	/*Isprazni korpu*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'isprazni'){
		unset($_SESSION['cart']);
	}

	/*Menja kolicinu za izabrani proizvod*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'potvrdi_kolicinu'){
		$kolicina = $_POST['kolicina'];
		$id = $_POST['id'];

		$_SESSION['cart'][$id]['kolicina'] = $kolicina;
	}

	/*Prikazuje kolocinu za izmenu*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'kolicina'){
		$kolicina = $_POST['kolicina'];
		echo "";
	}

	/*Uklanja stavke iz korpe*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'ukloni'){
	  $id = $_POST['id'];

	  unset($_SESSION['cart'][$id]);
	}

	/*Prikaz korpe*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'korpa_prikaz'){
		$ukorpi = array();
		if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
			$cart = $_SESSION['cart'];
			foreach($cart as $item){
				$ukorpi[] = $item['id'];
			}
			$ids = join(',',$ukorpi);
			$upit_proizvodi_korpa = "SELECT naziv_proizvoda, nova_cena, id_proizvoda FROM proizvodi WHERE id_proizvoda IN($ids)";
			$rezultat_pro_korpa = mysql_query($upit_proizvodi_korpa, $konekcija);
			echo '<table style="margin-left:100px;">
				<form>
				<tr>
					<td class="prva_kolona short">Naziv</td>
					<td class="druga_kolona short">Količina</td>
					<td class="prva_kolona short">Cena</td>
				</tr>';
			$total = 0;
			while($pro = mysql_fetch_array($rezultat_pro_korpa)){
				$kolicina = $cart[$pro['id_proizvoda']]['kolicina'];
				$cena = $pro['nova_cena'];
				$total += $kolicina * $cena;
				echo '<tr><td class="prva_kolona short">' . $pro['naziv_proizvoda'] . '</td>
					<td class="druga_kolona short" id="' . $cart[$pro['id_proizvoda']]['id'] . '"><input type="text" value="' . $kolicina . '" class="search_text kolicina"/></td>
					<td class="prva_kolona short">' . $cena . ' RSD</td>
					<td class="short"><a href="" id="' . $cart[$pro['id_proizvoda']]['id'] . '" class="ukloni search_bt">Ukloni</a></td></tr>';

			}
			echo  "<tr>
				<td class='short' colspan='4'>Ukupna cena:<span id='total'> " . $total . " RSD.</span></td>
				</tr>
				<tr>
					<td class='short' colspan='4'>
						<input id='naruci' type='button' value='Naruči' class='add_bt'/>
						<input id='isprazni' type='button' value='Isprazni' class='add_bt'/>
					</td>
				</tr>
			</form>";
			echo '</table>';
		}else{
			echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Vaša korpa je prazna, izaberite proizvode.</div>";
		}
	}

	/*Dodaje proizvod u korpu*/
	if(isset($_POST['uradi']) && $_POST['uradi'] == 'dodaj'){
		$id = $_POST['id'];
		$kolicina = $_POST['kolicina'];

	  $_SESSION['cart'][$id]['id'] = $id;
	  $_SESSION['cart'][$id]['kolicina'] = $kolicina;
	}

	if(isset($_POST['uradi']) && $_POST['uradi'] == 'prikazi'){
		$prebroj = 0;
		if(isset($_SESSION['cart'])){
			$prebroj = count($_SESSION['cart']);
		}

		if($prebroj == 0){
			echo "Izabrano 0 proizvoda.";
		}else if($prebroj == 1){
			echo "Izabran " . count($_SESSION['cart']) . " proizvod.";
		}else{
			echo "Izabrano " . count($_SESSION['cart']) . " proizvoda.";
		}
	}

	/*Dodavanje novog proizvoda*/
	if(isset($_GET['tip_proizvoda'])){
		$tip_proizvoda=$_GET['tip_proizvoda'];

		$upit="SELECT id_atributa, naziv_atributa FROM atributi WHERE id_podkategorije='$tip_proizvoda' ORDER BY id_atributa";
		$rezultat=mysql_query($upit,$konekcija);
		$broj=mysql_num_rows($rezultat);

		if($rezultat){
			if($broj>0){
				echo "<tr>";
				echo "<td class='prva_kolona'>Naziv proizvoda:</td>";
				echo "<td class='druga_kolona'><input type='text' name='naziv_proizvoda' class='text_input'/></td>";
				echo "<td></td>";
				echo "</tr>";
				while($atribut=mysql_fetch_array($rezultat)){
					echo "<tr>";
					echo "<td class='prva_kolona'>".$atribut['naziv_atributa'].":</td>";
					echo "<td class='druga_kolona'><input type='text' name='naziv_atributa[]' class='text_input'/></td>";
					echo "<td><input type='hidden' name='atribut[]' value='".$atribut['id_atributa']."'/></td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='prva_kolona'>Slika proizvoda</td>";
				echo "<td class='druga_kolona'><input type='file' name='slika_proizvoda'/></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='prva_kolona'>Cena proizvoda</td>";
				echo "<td class='druga_kolona'><input type='text' name='cena_proizvoda' class='text_input'/></td>";
				echo "</tr>";
				echo "<tr><td></td><td><input type='submit' name='dodaj_proizvod' value='Dodaj' class='add_bt'/><input type='reset' class='add_bt'/><td></tr>";
			}
		}
	}

	/*Izmena proizvoda*/
	if(isset($_GET['id_podkategorije'])){
		$id_podkategorije=$_GET['id_podkategorije'];

		$naziv_proizvoda = $_GET['naziv'];

		$condition = "WHERE id_podkategorije='$id_podkategorije' AND naziv_proizvoda LIKE '%$naziv_proizvoda%' AND status='1'";
		$paginator = pagination("proizvodi", "$condition", "&id_podkategorije=$id_podkategorije&naziv=$naziv_proizvoda", "izmeni_proizvode.php");
		$pagination = $paginator['pagination'];
		$rowsperpage = $paginator['rowsperpage'];
		$offset = $paginator['offset'];

		$upit_proizvodi="SELECT id_proizvoda, id_podkategorije, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition LIMIT $rowsperpage OFFSET $offset";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<div class='prod_box'>";
					echo "<div class='top_prod_box'></div>";
					echo "<div class='center_prod_box'>";
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' target='_BLANK'>";
					echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
					echo "</a>";
					$stara=$proizvod['cena_proizvoda'];
					$nova = $proizvod['nova_cena'];
					if($nova<$stara){
						echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
						echo "<div class='prod_price'><span class='price'>".$nova." din</span></div>";
					}else{
						echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
					}
					echo "</div>";
					echo "<div class='prod_details_tab' style='padding:0;padding-top:5px;padding-bottom:5px;'>";
					echo "<a href='izmeni_proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' class='prod_details' target='_BLANK'>Izmeni</a>";
					echo "</div>";
					echo "<div class='bottom_prod_box'></div>";
					echo "</div>";
				}
				echo $pagination;
			}else{
				echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema proizvoda</div>";
			}
		}
	}

	/*Brisanje proizvoda*/
	if(isset($_GET['prikaz_brisanje'])){
		$id_podkategorije=$_GET['prikaz_brisanje'];
		$naziv_proizvoda = $_GET['naziv'];

		$condition = "WHERE id_podkategorije='$id_podkategorije' AND naziv_proizvoda LIKE '%$naziv_proizvoda%' AND status='1'";
		$paginator = pagination("proizvodi", "$condition", "&id_podkategorije=$id_podkategorije&naziv=$naziv_proizvoda", "izbrisi_proizvode.php");
		$pagination = $paginator['pagination'];
		$rowsperpage = $paginator['rowsperpage'];
		$offset = $paginator['offset'];
		$page = $paginator['page'];

		$upit_proizvodi="SELECT id_proizvoda, id_podkategorije, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition LIMIT $rowsperpage OFFSET $offset";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<div class='prod_box'>";
					echo "<div class='top_prod_box'></div>";
					echo "<div class='center_prod_box'>";
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' target='_BLANK'>";
					echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
					echo "</a>";
					$stara=$proizvod['cena_proizvoda'];
					$nova = $proizvod['nova_cena'];
					if($nova<$stara){
						echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
						echo "<div class='prod_price'><span class='price'>".$nova." din</span></div>";
					}else{
						echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
					}
					echo "</div>";
					echo "<div class='prod_details_tab' style='padding:0;padding-top:5px;padding-bottom:5px;'>";
					echo "<a href='izbrisi_proizvode.php?page=" . $page . "&id=".$proizvod['id_proizvoda']."&id_podkategorije=".$proizvod['id_podkategorije']."&naziv=" . $naziv_proizvoda . "' class=''>Izbriši</a>";
					echo "</div>";
					echo "<div class='bottom_prod_box'></div>";
					echo "</div>";
				}
				echo $pagination;
			}else{
				echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema proizvoda</div>";
			}
		}
	}

	/*Izbrisani proizvodi*/
	if(isset($_GET['izbrisani'])){
		$id_podkategorije=$_GET['izbrisani'];
		$naziv_proizvoda = $_GET['naziv'];

		$condition = "WHERE id_podkategorije='$id_podkategorije' AND naziv_proizvoda LIKE '%$naziv_proizvoda%' AND status='0'";
		$paginator = pagination("proizvodi", "$condition", "&id_podkategorije=$id_podkategorije&naziv=$naziv_proizvoda", "izbrisani_proizvodi.php");
		$pagination = $paginator['pagination'];
		$rowsperpage = $paginator['rowsperpage'];
		$offset = $paginator['offset'];
		$page = $paginator['page'];

		$upit_proizvodi="SELECT id_proizvoda, id_podkategorije, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition LIMIT $rowsperpage OFFSET $offset";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<div class='prod_box'>";
					echo "<div class='top_prod_box'></div>";
					echo "<div class='center_prod_box'>";
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' target='_BLANK'>";
					echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
					echo "</a>";
					$stara=$proizvod['cena_proizvoda'];
					$nova = $proizvod['nova_cena'];
					if($nova<$stara){
						echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
						echo "<div class='prod_price'><span class='price'>".$nova." din</span></div>";
					}else{
						echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
					}
					echo "</div>";
					echo "<div class='prod_details_tab' style='padding:0;padding-top:5px;padding-bottom:5px;'>";
					echo "<a href='izbrisani_proizvodi.php?page=" . $page . "&id=".$proizvod['id_proizvoda']."&id_podkategorije=".$proizvod['id_podkategorije']."&naziv=" . $naziv_proizvoda . "' class=''>Poništi brisanje</a>";
					echo "</div>";
					echo "<div class='bottom_prod_box'></div>";
					echo "</div>";
				}
				echo $pagination;
			}else{
				echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema proizvoda</div>";
			}
		}
	}

	/*Izmena i brisanje korisnika*/
	if(isset($_GET['korisnicko_ime'])&&$_GET['korisnicko_ime']!=""){
		$korisnicko_ime=$_GET['korisnicko_ime'];

		$upit_korisnik="SELECT k.id_korisnika, k.korisnicko_ime, u.naziv_uloge FROM korisnici k JOIN uloge u ON k.id_uloge=u.id_uloge WHERE k.korisnicko_ime LIKE '$korisnicko_ime%' LIMIT 6";
		$rezultat_korisnik=mysql_query($upit_korisnik,$konekcija);
		$broj_korisnik=mysql_num_rows($rezultat_korisnik);
		if($rezultat_korisnik){
			if($broj_korisnik>0){
				while($korisnik=mysql_fetch_array($rezultat_korisnik)){
					echo "<div class='korisnik'>";
					echo "<div class='korisnicko_ime'><a href='profil.php?id=".$korisnik['id_korisnika']."'>".$korisnik['korisnicko_ime']."</a></div>";
					echo "<div class='opcije'><a href='izmeni_profil.php?id=".$korisnik['id_korisnika']."' target='_BLANK'>Izmeni</a></div>";
					if($korisnik['naziv_uloge']!="administrator"){
						echo "<div class='opcije'><a href='administracija.php?id=".$korisnik['id_korisnika']."'>Izbriši</a></div>";
					}
					echo "</div>";
				}
			}else{
				echo "<div class='korisnik'>";
				echo "<div class='korisnicko_ime'>Nema takvog korisnika</div>";
				echo "<div class='opcije'></div>";
				echo "<div class='opcije'></div>";
				echo "</div>";
			}
		}
	}

	/*Pretraga proizvoda*/
	if(isset($_GET['pretrazi'])&&$_GET['pretrazi']!=""){
		$pretrazi=$_GET['pretrazi'];
		$upit_proizvodi="SELECT naziv_proizvoda, id_proizvoda FROM proizvodi WHERE naziv_proizvoda LIKE '$pretrazi%' LIMIT 20";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);
		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."'>".$proizvod['naziv_proizvoda']."</a>"."<br/>";
				}
			}else{
				echo "Nema takvog proizvoda";
			}
		}
	}

	/*Izmena komentara*/
	if(isset($_GET['izmena_komentara'])){
		$id_podkategorije=$_GET['izmena_komentara'];

		$naziv_proizvoda = $_GET['naziv'];

		$condition = "WHERE id_podkategorije='$id_podkategorije' AND naziv_proizvoda LIKE '%$naziv_proizvoda%' AND status='1'";
		$paginator = pagination("proizvodi", "$condition", "&id_podkategorije=$id_podkategorije&naziv=$naziv_proizvoda", "izmeni_komentare.php");
		$pagination = $paginator['pagination'];
		$rowsperpage = $paginator['rowsperpage'];
		$offset = $paginator['offset'];

		$upit_proizvodi="SELECT id_proizvoda, id_podkategorije, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition LIMIT $rowsperpage OFFSET $offset";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);

		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<div class='prod_box'>";
					echo "<div class='top_prod_box'></div>";
					echo "<div class='center_prod_box'>";
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' target='_BLANK'>";
					echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
					echo "</a>";
					$stara=$proizvod['cena_proizvoda'];
					$nova = $proizvod['nova_cena'];
					if($nova<$stara){
						echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
						echo "<div class='prod_price'><span class='price'>".$nova." din</span></div>";
					}else{
						echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
					}
					echo "</div>";
					echo "<div class='prod_details_tab' style='padding:0;padding-top:5px;padding-bottom:5px;'>";
					echo "<a href='prikazi_komentare.php?id_proizvoda=".$proizvod['id_proizvoda']."' class='prod_details' target='_BLANK'>Prikaži komentare</a>";
					echo "</div>";
					echo "<div class='bottom_prod_box'></div>";
					echo "</div>";
				}
				echo $pagination;
			}else{
				echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema proizvoda</div>";
			}
		}
	}

	/*Stavi na akciju*/
	if(isset($_GET['akcija'])){
		$id_podkategorije=$_GET['akcija'];
		$naziv_proizvoda = $_GET['naziv'];

		$condition = "WHERE id_podkategorije='$id_podkategorije' AND naziv_proizvoda LIKE '%$naziv_proizvoda%' AND akcija='ne' AND status='1'";
		$paginator = pagination("proizvodi", "$condition", "&id_podkategorije=$id_podkategorije&naziv=$naziv_proizvoda", "proizvodi_akcija.php");
		$pagination = $paginator['pagination'];
		$rowsperpage = $paginator['rowsperpage'];
		$offset = $paginator['offset'];

		$upit_proizvodi="SELECT id_proizvoda, id_podkategorije, naziv_proizvoda, cena_proizvoda, slika_proizvoda, nova_cena FROM proizvodi $condition LIMIT $rowsperpage OFFSET $offset";
		$rezultat_proizvodi=mysql_query($upit_proizvodi,$konekcija);
		$broj_proizvodi=mysql_num_rows($rezultat_proizvodi);
		$ispis_str = "";
		if($rezultat_proizvodi){
			if($broj_proizvodi>0){
				while($proizvod=mysql_fetch_array($rezultat_proizvodi)){
					echo "<div class='prod_box'>";
					echo "<div class='top_prod_box'></div>";
					echo "<div class='center_prod_box'>";
					echo "<a href='proizvod.php?id_proizvoda=".$proizvod['id_proizvoda']."' target='_BLANK'>";
					echo "<div class='product_title'>".$proizvod['naziv_proizvoda']."</div>";
					echo "<div class='product_img'><img src='slike/proizvodi/" . $proizvod['id_proizvoda'] . "/male/".$proizvod['slika_proizvoda']."' alt='' title='' border='0' /></div>";
					echo "</a>";
					$stara=$proizvod['cena_proizvoda'];
					$nova = $proizvod['nova_cena'];
					if($nova<$stara){
						echo "<div class='prod_price'><span class='price'style='text-decoration:line-through;color:red;'>".$proizvod['cena_proizvoda']." din</span></div>";
						echo "<div class='prod_price'><span class='price'>".$nova." din</span></div>";
					}else{
						echo "<div class='prod_price'><span class='price'style=''>".$proizvod['nova_cena']." din</span></div>";
					}
					echo "</div>";
					echo "<div class='prod_details_tab' style='padding:0;padding-top:5px;padding-bottom:5px;'>";
					echo "<a href='stavi_na_akciju.php?id_proizvoda=".$proizvod['id_proizvoda']."' class='prod_details' target='_BLANK'>Stavi na akciju</a>";
					echo "</div>";
					echo "<div class='bottom_prod_box'></div>";
					echo "</div>";
				}
				echo $pagination;
			}else{
				echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Trenutno nema proizvoda</div>";
			}
		}
	}
	mysql_close($konekcija);
?>
