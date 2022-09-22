jQuery(document).ready(function($){
    $('.popup.info .close').click(function() {
       $('body').addClass('hide_info');
    });
    
        $('.menu.top .info').click(function() {
       $('body').removeClass('hide_info');
    });
    
    console.log(document.cookie.indexOf('KDB_visitor_visit_time='));
    console.log(document.cookie)
    });
    

