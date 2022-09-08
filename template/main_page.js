async function get_geojson($endpoint) {
  let url = $endpoint;
  let response = await fetch(url);
  let json = await response.json();
  return json;
}

async function main() {

  //--- prepare Json data to use---//
  
  const geojson_endpoint = "/wp-json/Sinngrund-Kulturdatenbank-plugin/geojson";
  const json_w_geocode = await get_geojson(geojson_endpoint);

  const info_json_endpoint = "/wp-json/Sinngrund-Kulturdatenbank-plugin/infojson";
  const info_json = await get_geojson(info_json_endpoint);

  //------------------------Map initialized --------------------------------------------//

  var main_map_options = {
    center: info_json.map_center,
    zoomSnap: 0.1,
    zoom: 12.5,
    zoomControl: false, // deactivate default topleft zoom .. but there is,, why? 
  };

  const map = L.map("main_page_map", main_map_options);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    minZoom: 5,
    attribution:
      'Map data and Imagery &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);

  //Definde a cluster Radius : smaller number only take close markers to make a cluster. 
  //// lager number: take more markers around in the long range of radius 
  var mcgLayerSupportGroup_auto = L.markerClusterGroup.layerSupport({
    maxClusterRadius: function (mapZoom) {
      if (mapZoom > 13) {
        return 5;
      } else {
        return 20;
      }
    },
  });
  mcgLayerSupportGroup_auto.addTo(map);



  //------------------------ Array of Icon & Layergroup by category initialized --------------------------------------------//
  //php directory url, start with ".", javascript start with nothing
  var icons_loc = info_json.icons_directory.replace(".", "");
  var category_icon_array = {};
  var category_layergroup_array = {};
  
  for (let [category_name, shortname] of Object.entries(info_json.icons)) {
    let icon_file = shortname + ".svg";
    let option_array = {
      iconUrl: icons_loc + "/" + icon_file,
      iconSize: [20, 20],
    };
    category_icon_array[category_name] = L.icon(option_array);
    category_layergroup_array[category_name]=L.layerGroup();
  }
  
  var group_all = L.layerGroup();


   //------------------------ Determine markers and generate entry List of main page based on the geojson--------------------------------------------//
  
  json_w_geocode.features.forEach((feature) => {
  
    let category = feature.taxonomy.category.name;
    let category_shortname = feature.taxonomy.category.shortname;
    // Entry list
    const datenbank_list = document.querySelector("#datenbank_list");
    datenbank_list.insertAdjacentHTML(
      "beforeend",
      createListItem({
        post_id: feature.id,
        title: feature.properties.name,
        category_name: category,
        category_shortname: category_shortname,
        url: feature.properties.url,
        date: feature.properties.date,
        author: feature.properties.author,
      })
    );

    // Entry Format for List
    function createListItem({
      post_id,
      title,
      category_name,
      category_shortname,
      url,
      date,
      author,
    }) {
      let htmltext =
        '<div class="datenbank_single_entry map_link_point category_'+category_shortname + 
              '" id="map_id_'+ post_id + 
              '" category="' + category_shortname +
              '" date="' + date +
              '" author="' + author +
        '">' +
          '<div class="entry_title">' + title + "</div>" +
          '<div class="entry_date" style="">' + date + "</div>" +
          '<div class="entry_author" style="">' + author + "</div>" +
          '<div class="entry_category">'+
            '<img style="height: 20px; width: 20px; margin-right: 2px;" src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/' + category_shortname + '.svg"/>' +
            category_name +
          "</div>" +
          '<a href="' + url + '">' +
            '<button class="dn">Eintrag ansehen</button>' +
          "</a>" +
          "</div>";
      return htmltext;
    }
    
    // Marker setting
    let Icon_name = category_icon_array[category];
    let popuptext =
      '<div class="popup_title"><strong>'+ feature.properties.name + "</strong></div>" +
      "<p>" + feature.taxonomy.category.name + "</p>" +
      '<a href="' +  feature.properties.url + '">' +
        '<button class="popup_button">Eintrag ansehen</button>' +
      "</a>";

    let marker_option = {
      icon: Icon_name,
      name: feature.properties.name,
      post_id: feature.properties.post_id,
    };

    let marker = L.marker(
                          [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
                          marker_option
                        ).bindPopup(popuptext);

    // Add marker to related category map_layergroup and group_all 
    marker.addTo(category_layergroup_array[category]);
    marker.addTo(group_all);
  });

  //apply the cluster Properties to the markers in group_all 
  mcgLayerSupportGroup_auto.checkIn(group_all);
  // all markers on Map, this one need to be after the checkIn
  group_all.addTo(map);
  

  
  // Make connection between list and marker 
  save_layerId_in_html(group_all); // marker/layerId saved in html 
  build_link(map, group_all); // click event 

  function save_layerId_in_html(markers, option_name = "post_id") {
    markers.eachLayer((marker) => {
      var post_id = marker["options"][option_name];
      var map_id = markers.getLayerId(marker);
      document
        .getElementById("map_id_" + post_id)
        .setAttribute("value", map_id);
    });
  }

  function build_link(map, markers) {
    const divs = document.querySelectorAll(".map_link_point");

    divs.forEach((el) =>
      el.addEventListener("click", (event) => {
        let map_id = parseInt(event.target.parentNode.getAttribute("value"));
        console.log(map_id);
        var marker = markers.getLayer(map_id);
        map.setZoom(14);
        map.flyTo(marker.getLatLng(), 14);
        map.on("zoomend", () => {
          marker.openPopup();
        });

        map.flyTo(marker.getLatLng(), 14);
        // //hide all the buttons
        divs.forEach(function (posted) {
          var postedBtn = posted.querySelector("button");
          postedBtn.classList.remove("db");
        });

        // show the button in the clicked DIV
        event.target.parentNode.querySelector("button").classList.add("db");
      })
    );
  }

  // fire Sorting Event 
  jQuery("#main_page_list_sort_options").change(function () {
    sortList(jQuery(this).val());
  });

  //List sorting function 
  function sortList(option) {
    var list, i, switching, b, shouldSwitch;
    list = document.getElementById("datenbank_list");
    switching = true;
    /* Make a loop that will continue until
        no switching has been done: */
    while (switching) {
      // start by saying: no switching is done:
      switching = false;
      //b = list.getElementsByTagName("LI");
      b = document.getElementsByClassName("datenbank_single_entry");
      // Loop through all list-items:
      for (i = 0; i < b.length - 1; i++) {
        // start by saying there should be no switching:
        shouldSwitch = false;
        /* check if the next item should
            switch place with the current item: */
        var check;
        if (option == 0) {
          let x = new Date(b[i].getAttribute("date"));
          let y = new Date(b[i + 1].getAttribute("date"));
          check = x < y;
        } else if (option == 1) {
          check =
            b[i].innerHTML.toLowerCase() > b[i + 1].innerHTML.toLowerCase();
        }
        if (option == 2) {
          check =
            b[i].getAttribute("author").toLowerCase() >
            b[i + 1].getAttribute("author").toLowerCase();
        }
        if (check) {
          /* if next item is alphabetically
              lower than current item, mark as a switch
              and break the loop: */
          shouldSwitch = true;
          break;
        }
      }
      if (shouldSwitch) {
        /* If a switch has been marked, make the switch
            and mark the switch as done: */
        b[i].parentNode.insertBefore(b[i + 1], b[i]);
        switching = true;
      }
    }
  }

  // Category filter Event and Function

  jQuery(":checkbox").change(function () {
    var index = 0;
    var checkedArray = [];
    var checkboxes = document.getElementsByName("kategory_filter");
    mcgLayerSupportGroup_auto["removeLayer"]([group_all]);

    while (index < checkboxes.length) {
      let target_class = "category_" + checkboxes[index].value;
      let current_category = document.getElementsByClassName(target_class);
      if (checkboxes[index].checked) {
        for (i = 0; i < current_category.length; i++) current_category[i].style.display = "block";
        checkedArray.push(checkboxes[index].value);
        let category_name = checkboxes[index].getAttribute("category_name");
        console.log(category_name);
        let group = category_layergroup_array[category_name];
        mcgLayerSupportGroup_auto["addLayer"](group);
      } else {
        for (i = 0; i < current_category.length; i++)
          current_category[i].style.display = "none";
      }
      ++index;
    }
    console.log(checkedArray);
  });

  function createlivesearchreasult({      title,    }) {
    let htmltext =
        '<div class="entry_title">' + title + "</div>";
    return htmltext;
  }

  var GetSearch = document.getElementById('search');
  GetSearch.addEventListener("keyup", function(){
      //InfoData = {slug:GetSearch.value}
      jQuery.ajax({
         type: "GET",
         url: 'wp-json/wp/v2/posts?search=' + GetSearch.value ,
         data: '',
         datatype: "html",
         success: function(results) {
            let livesearch = document.querySelector("#livesearch");
            livesearch.innerHTML = "";
            results.forEach((result)=>{  
              livesearch.insertAdjacentHTML("beforeend", createlivesearchreasult({ title: result.title.rendered, }) );
            });
            console.log(results);

            if(GetSearch.value == '') livesearch.innerHTML = "";
         }
     });
  });

}
main();
