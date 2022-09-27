
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
          '<img src="' + source_url + '" alt="">' +
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
  url: '/wp-json/wp/v2/media?per_page=60' ,
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
