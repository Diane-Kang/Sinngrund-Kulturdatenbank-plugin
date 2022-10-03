async function main() {
function createMediaList({
  source_url,
  caption_rendered,
  orte, //list of orte tags
  date,
  author_id,

}) {
  let htmltext =
    '<div class="grid-item">'+
      '<div class="grid-main-wrap-image">'+
        '<div class="grid-item-wrap">' +
          '<img class="image" src="' + source_url + '" alt=""/>' +
          '<img class="lens" src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/Icon-search.svg" alt="Lupe" aria-label="vergrößern"/>' +
          '<img class="close close_icon" src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/close-svgrepo-com.svg" alt="Schließen" aria-label="schließen"/>'
        '</div>'+
      '</div>' +
      '<div class="media_beschriftung">' +
      '<h5>' + caption_rendered + '</h5>' + 
      '</div>' +
      '<div class="orte_tags">' + orte[0] + 
      '</div>' +
      '<div class="media_upload_date">Hochgeladen am' + date + 
      '</div>'+
      '<div class="media_author">von' + author_id + 
      '</div>' +
    '</div>'
  return htmltext;
}


jQuery.ajax({
  type: "GET",
  url: '/wp-json/wp/v2/media' ,
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
          caption_rendered : feature.caption.rendered,
          orte : feature.orte, //list of orte tags
          date : feature.date,
          author_id : feature.author,
         })
       );
     });
  }
});



var GetSearch = document.getElementById('search');
GetSearch.addEventListener("keyup", function(){
    //InfoData = {slug:GetSearch.value}
    jQuery.ajax({
      type: "GET",
      // url: 'wp-json/wp/v2/posts?search=' + GetSearch.value ,
      url: '/wp-json/wp/v2/media?search='  + GetSearch.value ,
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
              caption_rendered : feature.caption.rendered,
              orte : feature.orte, //list of orte tags
              date : feature.date,
              author_id : feature.author,
            })
          );
        });
      }
   });
});





}
main();
