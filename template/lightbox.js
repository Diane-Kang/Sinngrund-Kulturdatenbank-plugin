console.log(jQuery('.gallery_wrapper'));    


jQuery( window ).on( "load", function() {
//jQuery(document).ready(function($){
    console.log(jQuery('.gallery_wrapper'));
   // console.log(jQuery('.gallery_wrapper'));
    // console.log(jQuery('<img>'));
   //  console.log(document.getElementsByClassName('lens'));
   setTimeout(function() {                                      //Not working without setTimeout Function, check later
     console.log(jQuery('.lens'));

        jQuery('.grid-item-wrap').click(function() {
            jQuery(this).addClass('img_fullscreen');
       //     alert('click');
        });
    
    
     jQuery('.close_icon').click(function() {
        event.preventDefault()
        jQuery('.grid-item-wrap').addClass('close_lightbox_fix'); // Remove Class not Working, check late
    });
}, 2000);



    //jQuery('img.lens').click(function(){alert('hello')})
    });