jQuery(document).ready(function($){
    $('.popup.info .close').click(function() {
       $('body').addClass('hide_info');
    });
    
        $('.menu.top .info').click(function() {
       $('body').removeClass('hide_info');
    });
    });
    