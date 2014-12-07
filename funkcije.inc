<?php
	function ispisi_dan($dan = 0){
		for($i=1;$i<=31;$i++){
			if($dan == $i){
				if($i<10){
					$ispis.="<option value=0$i selected>$i</option>";
				}else{
					$ispis.="<option value='$i' selected>$i</option>";
				}
			}else{
				if($i<10){
					$ispis.="<option value=0$i>$i</option>";
				}else{
					$ispis.="<option value='$i'>$i</option>";
				}
			}
		}
		return $ispis;
	}

	function ispisi_mesec($mesec2 = 0){
		$meseci=array("01"=>"Januar",
					 "02"=>"Februar",
					 "03"=>"Mart",
					 "04"=>"April",
					 "05"=>"Maj",
					 "06"=>"Jun",
					 "07"=>"Jul",
					 "08"=>"Avgust",
					 "09"=>"Septembar",
					 "10"=>"Oktobar",
					 "11"=>"Novembar",
					 "12"=>"Decembar");
		foreach($meseci as $broj=>$mesec){
			if($mesec2 == $broj){
				$zapis.="<option value='$broj' selected>$mesec</option>";
			}else{
				$zapis.="<option value='$broj'>$mesec</option>";
			}
		}
		return $zapis;
	}
	function ispisi_godinu($godina = 0){
		for($i=date('Y') - 100;$i<=date('Y');$i++){
			if($godina == $i){
				$zapis.="<option value='$i' selected>$i</option>";
			}else{
				$zapis.="<option value='$i'>$i</option>";
			}
		}
		return $zapis;
	}
?>
