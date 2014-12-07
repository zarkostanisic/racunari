$(document).ready(function(){
	$('#dodaj_u_korpu').click(function(){
		var id = $('#id_jedan_proizvod').val();
		var kolicina = $('#proizvod_kolicina').val();
		$.ajax({
			url:'ajax.php',
			type:"post",
			data:{id:id, kolicina:kolicina, uradi:'dodaj'},
			success:function(data){
				alert('Proizvod uspešno dodat u korpu.');
				prikazi_broj_proizvoda()
			}
		});
	});

	$(document).on('click', '.ukloni', function(){
		var id = $(this).attr('id');

		$.ajax({
			url:'./ajax.php',
			type:"post",
			data:{uradi:'ukloni', id:id},
			success:function(data){
				prikazi_broj_proizvoda();
				korpa_prikaz();
			}

		});

		return false;
	});


	$(document).on('blur', '.kolicina', function(){
		var id = $(this).parent().attr('id');
		var kolicina = $(this).val();

		$.ajax({
			url:'./ajax.php',
			type:"post",
			data:{kolicina:kolicina, id:id, uradi:'potvrdi_kolicinu'},
			success:function(data){
				korpa_prikaz();
			}
		});
	});

	$(document).on('click', '#naruci', function(){
		var total = $('#total').html();
		$.ajax({
			url:'./ajax.php',
			type:"post",
			data:{total:total, uradi:'naruci'},
			success:function(data){
				alert(data);
				korpa_prikaz();
				prikazi_broj_proizvoda();
			}
		});
	});

	$(document).on('click', '#isprazni', function(){
		$.ajax({
			url:'./ajax.php',
			type:"post",
			data:{uradi:'isprazni'},
			success:function(data){
				korpa_prikaz();
				prikazi_broj_proizvoda();
			}
		});
	});
	prikazi_broj_proizvoda();
	korpa_prikaz();
});

function prikazi_broj_proizvoda(){
	$.ajax({
		url:'./ajax.php',
		type:"post",
		data:{uradi:'prikazi'},
		success:function(data){
			$('.prikazi_broj').html(data);
		}

	});
}

function korpa_prikaz(){
	$.ajax({
		url:'./ajax.php',
		type:"post",
		data:{uradi:'korpa_prikaz'},
		success:function(data){
			$('#korpa_prikaz').html(data);
		}

	});
}
