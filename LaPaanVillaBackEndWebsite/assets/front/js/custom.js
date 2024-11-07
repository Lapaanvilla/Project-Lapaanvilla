$ = jQuery;

$(document).ready(function() {
    $('.minus').on("click", function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        if(count < 1 || isNaN(parseInt(count)) ){
        count = 1;
        }      
        $input.val(count);
        $input.change();
        $('#peepid').html('<strong>'+count+' People</strong>');
        return false;
    });

    $('.plus').on("click", function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) + 1;
        if(count < 1 || isNaN(parseInt(count)) ){
        count = 1;
        }
        if(count > 9999){
        count = 9999;
        }
        $input.val(count);
        $input.change();
        $('#peepid').html('<strong>'+count+' People</strong>');
        return false;
    });

    Modernizr.on('webp', function(result) {
        if (result) {

        } else {
            $(this).addClass('no-webp');
        }
    });

	/*--- Svg Inline ---*/
    setInterval(function(){ 
        $('.icon img').inlineSvg();
    }, 1000);

    /*--- Browse Menu ---*/
    $(".item-browse, .slider-overlay").click(function () {
        $("body").toggleClass("overflow-hidden");
        $(".slider-tag").toggleClass("open");
    });

    $(".slider-tag .btn").click(function () {
        if ($(window).outerWidth() <= 1199) {
            $("body").toggleClass("overflow-hidden");
            $(".slider-tag").toggleClass("open");

        }
    });
	

    /*--- Sidebar Nav ---*/
    $(".nav-toggle, .nav-backdrop").click(function() {
        $(".navigation").toggleClass('open');
    });

	$('.btn-scroll').click(function(){ 
	    $("html, body").animate({ scrollTop: 0 }, 1000); 
	    return false; 
	});

    $(".icon-eye").click(function() {
        $(this).toggleClass('active');

        if ($(this).hasClass('active')) {
            $(this).siblings('.form-control').attr('type', 'text');
        } else {
            $(this).siblings('.form-control').attr('type', 'password');
        }
    }); 

    $(document).click(function(){
        $(".dropdown-content.open").slideUp();
        $(".dropdown-content.open").removeClass("open");
    });

    $(".sorting-dropdown-btn").click(function(){
      $(".dropdown-content.open").slideUp();
      $(".dropdown-content.open").removeClass("open");
    }); 

    $(".dropdown-custom").click(function(e) {
        e.stopPropagation(); 
        $($(this).attr('data-target')).slideToggle();
        $($(this).attr('data-target')).toggleClass("open");
    });

    $("#dropdown-sort").click(function(e) {
        e.stopPropagation(); 
    });

    $(".title-dashboard h5 .icon, .aside-backdrop, .section-dashboard aside .btn").click(function(event) {
        $(".section-dashboard aside").toggleClass("open");
    });

	var footer_height = $("footer").outerHeight();
    $("main").css("margin-bottom", footer_height);
});

$(window).scroll(function(){ 
    if ($(this).scrollTop() > 100) { 
        $('.btn-scroll').addClass("active"); 
    } else { 
        $('.btn-scroll').removeClass("active"); 
    } 
}); 

$(window).resize(function () {

    var footer_height = $("footer").outerHeight();
    $("main").css("margin-bottom", footer_height);
});
