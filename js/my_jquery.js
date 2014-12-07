$(document).ready(function(){
		$('.center_menu>ul>li>ul').hide();
		$('.center_menu>ul>li').hover(function(){
			$(this).find('ul').stop(true,true).slideToggle();
		},function(){
			$(this).find('ul').stop(true,true).slideToggle();
	});

  $(document).on('click', 'input[type=reset]', function(){
    $('input[type=text]').val('');

    return false;
  });
});

$(function(){
  $('#slider').nivoSlider();
});

$(function(){
  $('#left_menu .title_box').click(function(){
    $(this).parent('#left_menu').find('.left_menu').slideToggle();
  });
});
