function createMediaList({
  source_url,
  caption_rendered,
  orte, //list of orte tags
  fotograf,
  date,
  author_id,

}) {
  let htmltext =
    '<div class="grid-item">'+
      '<div class="grid-main-wrap-image">'+
        '<div class="grid-item-wrap">' +
          '<img class="image" src="' + source_url + '" alt=""/>' +
          '<div class="lens" aria-label="vergrößern"/>' +
          '<svg  viewBox="0 0 25 25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' +
          '<title>Search Icon</title>' +
          '<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">' +
          '<g id="01-willkommen" transform="translate(-1356.000000, -445.000000)" stroke="#009CDE" stroke-width="3">' +
          '<g id="Group" transform="translate(1357.000000, 446.000000)">' +
              '<circle id="Oval" cx="9.5" cy="9.5" r="9"></circle>' +
              '<line x1="17.5" y1="17.5" x2="22.4497475" y2="22.4497475" id="Line-2" stroke-linecap="square"></line>' +
          '</g>' +
          '</g>' +
          '</g>' +
          '</svg>' +
          '</div>' +
          '<div class="close close_icon" aria-label="schließen"></div>'+
        '</div>'+
      '</div>' +
      '<div class="media_beschriftung">' +
      '<h2>' + caption_rendered + '</h2>' + 
      '</div>' +      
      '<div class="orte_tags">' + orte + 
      '<div class="fotograf">Fotograf: ' +fotograf+'</div>' +
      '</div>' +
      '<div class="media_upload_date">Hochgeladen am ' + date +  '</div>'+
      '<div class="media_author"> von ' + author_id + '</div>' +
    '</div>'
  return htmltext;
}

console.time("this");
jQuery.ajax({
  type: "GET",
  url: '/wp-json/Sinngrund-Kulturdatenbank-plugin/galleryjson' ,
  data: '',
  datatype: "html",
  success: function(results) {
     let datenbank_list_result = document.querySelector("#media-list");
     datenbank_list_result.innerHTML = "";
     results.forEach((feature)=>{  
       datenbank_list_result.insertAdjacentHTML(
         "beforeend",
         createMediaList({
          source_url: feature.source_url,
          caption_rendered : feature.title,
          orte : feature.orte_tags_text, //list of orte tags
          fotograf : feature.fotograf_tags_text,
          date : feature.date,
          author_id : feature.author,
         })
       );
     });
     lightbox_galley();
  }
});
console.timeEnd("this");

function lightbox_galley(){
  jQuery('.grid-item-wrap').click(function() {
    jQuery(this).addClass('fullscreen');
  });
  jQuery('.close_icon').click(function() {
    event.stopPropagation();
    jQuery('.grid-item-wrap').removeClass('fullscreen');       
  });   
}

jQuery(document).ready(function($){
  var GetSearch = document.getElementById('search');
  GetSearch.addEventListener("keyup", function(){
      //InfoData = {slug:GetSearch.value}
      jQuery.ajax({
        type: "GET",
        // url: 'wp-json/wp/v2/posts?search=' + GetSearch.value ,
        // Json search through only file title
        url: '/wp-json/Sinngrund-Kulturdatenbank-plugin/galleryjson?search='  + GetSearch.value ,
        data: '',
        datatype: "html",
        success: function(results) {
          let datenbank_list_result = document.querySelector("#media-list");
          datenbank_list_result.innerHTML = "";
          results.forEach((feature)=>{  
            datenbank_list_result.insertAdjacentHTML(
              "beforeend",
              createMediaList({
                source_url: feature.source_url,
                caption_rendered : feature.title,
                orte : feature.orte_tags_text, //list of orte tags
                fotograf : feature.fotograf_tags_text,
                date : feature.date,
                author_id : feature.author,
              })
            );
          });
          lightbox_galley();
        }
    });
  });

});


