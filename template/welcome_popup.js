function getCookie(name) {
   var dc = document.cookie;
   var prefix = name + "=";
   var begin = dc.indexOf("; " + prefix);
   if (begin == -1) {
       begin = dc.indexOf(prefix);
       if (begin != 0) return null;
   }
   else
   {
       begin += 2;
       var end = document.cookie.indexOf(";", begin);
       if (end == -1) {
       end = dc.length;
       }
   }
   // because unescape has been deprecated, replaced with decodeURI
   //return unescape(dc.substring(begin + prefix.length, end));
   return decodeURI(dc.substring(begin + prefix.length, end));
} 

function doSomething() {
   var myCookie = getCookie("KDB_visitor_visit_time");
   alert(myCookie);

   if (myCookie == null) {
       // do cookie doesn't exist stuff;
   }
   else {
       // do cookie exists stuff
   }
}

jQuery(document).ready(function($){
    $('.popup.info .close').click(function() {
       $('body').addClass('hide_info');
    });
    
        $('.menu.top .info').click(function() {
       $('body').removeClass('hide_info');
    });
    //var myCookie = getCookie("KDB_visitor_visit_time");
    //alert(document.cookie);
    //doSomething();
    console.log(document.cookie.indexOf('KDB_visitor_visit_time='));
    console.log(document.cookie)
    //.hide_info
    });
    

