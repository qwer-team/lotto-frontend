$(document).ready(function(){
	if (window.PIE) {
        $('#flag_slider').each(function() {
            PIE.attach(this);
        });
        $('.slide-controll a').each(function() {
            PIE.attach(this);
        });
        $('.block-header').each(function() {
            PIE.attach(this);
        });
        $('.title').each(function() {
            PIE.attach(this);
        });
    }
	$('#tirazh-1').selectbox();
	$('.multiples-select td select').selectbox();
	var instanceOne = new ImageFlow();
	instanceOne.init({ ImageFlowID: 'flag_slider', reflectionPNG: true }); 
	if($('.ir-lotery').length){
		$('.ir-lotery .block').eq(1).addClass('dis');
		$('.ir-lotery .block .title').on('click', function(){
			var parent = $(this).parents('.block');
			if($(parent).hasClass('dis')){
				$('.ir-lotery .block').addClass('dis');
				$(parent).removeClass('dis');
			}
			return false;
		});
	}
	$('#tabs').tabs();
	$('.balls-g1').on('click', function(){
		if(!$(this).hasClass('active')){
			$('.balls-g1.active').removeClass('active');
			$(this).addClass('active');
		}
	});
	$('#clear-all').on('click', function(){
		$('.balls-g1.active').removeClass('active');
		return false;
	});
	$('.ball-item').on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}else{
			$(this).addClass('active');
		}
		return false;
	});
	$('.slide-controll a').on('click', function(){
		if(!$(this).hasClass('close')){
			$(this).prev('span').html('Развернуть');
			$(this).addClass('close');
			$(this).parents('.slide-block').animate({height: 40}, 600);
		}else{
			$(this).prev('span').html('Свернуть');
			$(this).removeClass('close');
			$(this).parents('.slide-block').animate({height: 382}, 600);
		}
		return false;
	});
	if($('.schedule').length){
		$('.schedule tbody:odd').addClass('odd');
	}
	$('li.sub > a').on('click',function(){
		$(this).toggleClass('opened');
		$(this).next('ul').slideToggle(500);
		return false;
	});
});