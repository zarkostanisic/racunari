<script type="text/JavaScript">
	function getHTTPObject(){
		if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
		else if (window.XMLHttpRequest) return new XMLHttpRequest();
		else {
			alert("Your browser does not support AJAX.");
			return null;
		}
	}
	
	function prikazi_pro(){
		if(httpObject.readyState == 4){
			document.getElementById('prikazi_pretrazeno').innerHTML = httpObject.responseText;
		}
	}
	
	function pretraga_proizvoda(){
		httpObject = getHTTPObject();
		if (httpObject != null) {
			httpObject.open("GET", "ajax.php?pretrazi=" +document.getElementById('pretrazi').value, true);
			httpObject.send(null);
			httpObject.onreadystatechange = prikazi_pro;
		}	
	}
	
	function izbrisi_p(){
		setTimeout("izbrisi_pretragu()",1000);
	}
	
	function izbrisi_pretragu(){
		document.getElementById("prikazi_pretrazeno").innerHTML="";
	}
	
	var httpObject = null;
</script>
<?php 
    $uloga = "";
	
	if(isset($_SESSION['uloga'])){
		$uloga=$_SESSION['uloga'];
	}
?>
<div class="left_menu_corner"></div>
	<div class="center_menu">
		<ul>
			<li><a href="index.php">Početna</a></li>
			<div class="divider"></div>
			<?php 
				if($uloga=="administrator"){
					echo "<li><a href='administracija.php'>Administracija</a></li>";
					echo "<div class='divider'></div>";
				}
				
				if($uloga==""){
					echo "<li><a href='registracija.php'>Registracija</a></li>";
					echo "<div class='divider'></div>";
				}
			?>
			<li><a href="akcije.php">Akcije</a></li>
			<div class="divider"></div>
			<?php if(isset($_SESSION['id_korisnika'])){?>
			<li><a href="moj_meni.php">Moj meni</a></li>
			<div class="divider"></div>
			<?php } ?>
			<li><a href="kontakt.php">Kontakt</a></li>
		</ul>
	</div>
	<div id="pretraga">
		<form name="pretraga" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<p>Pretraga</p>
		<input type="text" name="pretrazi" class="text_input" onKeyUp="pretraga_proizvoda();" id="pretrazi" onBlur="izbrisi_p();"/>
		</form>
		<div id="prikazi_pretrazeno"></div>
	</div>
<div class="right_menu_corner"></div>