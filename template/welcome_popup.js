jQuery(document).ready(function($){
    $('.popup.info .close').click(function() {
       $('body').addClass('hide_info');
    });
    
        $('.menu.top .info').click(function() {
       $('body').removeClass('hide_info');
    });

    $('.popup.info .next').click(function() {
      $('.slide.show').removeClass('show').next().addClass('show');
   });

   $('.popup.info .prev').click(function() {
      $('.slide.show').removeClass('show').prev().addClass('show');
   });
    
    console.log(document.cookie.indexOf('KDB_visitor_visit_time='));
    console.log(document.cookie)
    });
    

