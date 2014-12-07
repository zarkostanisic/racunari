<?php
	include('konekcija.inc');

	@$uloga=$_SESSION['uloga'];
	@$username=$_SESSION['korisnicko_ime'];
	@$id_korisnika=$_SESSION['id_korisnika'];
	if(isset($_POST['loguj'])){
		$korisnicko_ime=$_POST['korisnicko_ime'];
		$sifra=$_POST['sifra'];

		$reg_korisnicko_ime="^[a-z]{5,20}$";
		$reg_sifra="^[a-z]{5,}$";

		$greske=array();

		if(@!ereg($reg_korisnicko_ime,$korisnicko_ime)){
			$greske[0]="*";
		}

		if(@!ereg($reg_sifra,$sifra)){
			$greske[1]="*";
		}

		$upit_l="SELECT * FROM korisnici k JOIN uloge u ON k.id_uloge=u.id_uloge WHERE korisnicko_ime='$korisnicko_ime' AND sifra='$sifra'";
		$rezultat_l=mysql_query($upit_l,$konekcija);
		$broj_l=mysql_num_rows($rezultat_l);

		if($rezultat_l){
			if($broj_l==1){
				$korisnik=mysql_fetch_array($rezultat_l);
				$_SESSION['id_korisnika']=$korisnik['id_korisnika'];
				$_SESSION['uloga']=$korisnik['naziv_uloge'];
				$_SESSION['korisnicko_ime']=$korisnik['korisnicko_ime'];
				header("Location:login.php");
			}
		}else{
			$greske[0]="*";
			$greske[1]="*";
		}
	}

	include('podesavanja.inc');
	include('paginacija.inc');
?>
