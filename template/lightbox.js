jQuery(document).ready(function($){

 //Not working without setTimeout Function, check later
   setTimeout(function() {    

        $('.grid-item-wrap').click(function() {
            $(this).addClass('fullscreen');
        });
        
        $('.close_icon').click(function() {
            event.stopPropagation();
            $('.grid-item-wrap').removeClass('fullscreen');       
        });    
    }, 20);
    });