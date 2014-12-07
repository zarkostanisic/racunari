<div class="top_search">
	<?php if($uloga==""){ ?>
	<form name="logovanje" action="index.php" method="post">
		<div class="search_text">Korisnik:</div>
		<input type="text" class="search_input" name="korisnicko_ime" />
		<div class="search_text" style="color:red;width:3px;margin-left:-2px;margin-right:-3px;"><?php if(isset($greske[0])){ echo $greske[0]; } ?></div>
		<div class="search_text">Sifra:</div>
		<input type="password" class="search_input" name="sifra" />
		<div class="search_text" style="color:red;width:3px;margin-left:-2px;margin-right:-3px;"><?php if(isset($greske[1])){ echo $greske[1]; } ?></div>
		<input type="submit" class="search_bt" value="Loguj se" name="loguj"/>
	</form>
	<?php }else{
		echo "<div class='search_text' style='margin-left:80px;font-size:14px;font-weight:normal;'>Ulogovani ste kao: <a href='profil.php?id=".$id_korisnika."'>".$username."</a><a href='izadji.php' style='float:right;margin-left:40px;color:white;'>Izlogujte se</a></div>";
	} ?>
</div>
