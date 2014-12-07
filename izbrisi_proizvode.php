<?php session_start();
	  include("logovanje.inc");
	  $uloga=$_SESSION['uloga'];
	  if($uloga!="administrator"){
		header("Location:index.php");
	  }

	  $title = "Izbriši proizvode | Administracija | " . $site_name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php
	include_once('head.inc');
 ?>
<script type="text/JavaScript">
	function getHTTPObject(){
		if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
		else if (window.XMLHttpRequest) return new XMLHttpRequest();
		else {
			alert("Your browser does not support AJAX.");
			return null;
		}
	}

	function prikazi_p(){
		if(httpObject.readyState == 4){
			document.getElementById('ispisi_proizvode').innerHTML = httpObject.responseText;
		}
	}

	function prikazi_proizvode(){
		httpObject = getHTTPObject();
		if(document.getElementById('tip_proizvoda').value == 0){
			alert('Izaberite kategoriju');
			return false;
		}
		if (httpObject != null) {
			httpObject.open("GET", "ajax.php?prikaz_brisanje=" +document.getElementById('tip_proizvoda').value+ "&naziv=" +document.getElementById('naziv_proizvoda').value, true);
			httpObject.send(null);
			httpObject.onreadystatechange = prikazi_p;
		}
	}

	var httpObject = null;
</script>
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
		<div class="center_title_bar">Izbriši proizvode</div>
		<table style="margin-top:5px;margin-bottom:5px;" class="trazi_po_nazivu">
		<form name="izmeni_p" action="izmeni_proizvode.php" method="post">
		<tr>
			<td class='druga_kolona'>
				<span>Naziv proizvoda:</span>
				<input type="text" id="naziv_proizvoda" class="text" value="<?php if(isset($_GET['naziv'])){echo $_GET['naziv']; } ?>"/>
			</td>
			<td class='prva_kolona'>
				<span>Tip proizvoda:</span>
				<select  name="tip_proizvoda" id="tip_proizvoda" class='select'>
					<option value="0">Izaberi</option>
					<?php
						$id_podkategorije=$_GET['id_podkategorije'];
						$upit_tip="SELECT naziv_podkategorije, id_podkategorije FROM podkategorije WHERE status='1' ORDER BY naziv_podkategorije";
						$rezultat_tip=mysql_query($upit_tip,$konekcija);
						$broj_tip=mysql_num_rows($rezultat_tip);

						if($rezultat_tip){
							if($broj_tip>0){
								while($podkategorija=mysql_fetch_array($rezultat_tip)){
									if($id_podkategorije==$podkategorija['id_podkategorije']){
									echo "<option value='".$podkategorija['id_podkategorije']."' selected>".$podkategorija['naziv_podkategorije']."</option>";
									}else{
									echo "<option value='".$podkategorija['id_podkategorije']."'>".$podkategorija['naziv_podkategorije']."</option>";
									}
								}
							}
						}
					?>
				</select>
			</td>
			<td class='druga_kolona short' style="border:none;">
				<br/>
				<input type="button" id="trazi_po_nazivu" value="Traži" class="add_bt" onClick="prikazi_proizvode();"/>
			</td>
		</tr>
		</form>
		</table>
		<?php
			if(isset($_GET['id'])){//brisanje proizvoda
				$id=$_GET['id'];

				$upit_brisanje="UPDATE proizvodi SET status='0' WHERE id_proizvoda='$id'";
				$rezultat_brisanje=mysql_query($upit_brisanje,$konekcija);

				if($rezultat_brisanje){
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Proizvod je uspešno obrisan</div>";
				}else{
					echo "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška pri brisanju</div>";
				}
			}
		?>
		<div id="ispisi_proizvode">
			<?php


			if(isset($id_podkategorije)){

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
			?>
		</div>
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
