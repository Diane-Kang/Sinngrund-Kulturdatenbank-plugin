<!-- Search box reference https://stackoverflow.com/questions/15919227/get-latitude-longitude-as-per-address-given-for-leaflet -->

<meta charset="utf-8">
<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" /> --> 
<!-- <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script> --> 


<style type="text/css">
    /* html, body { width:100%;padding:0;margin:0; } */
    /* .container { width:95%;max-width:980px;padding:1% 2%;margin:0 auto } */
    #lat, #lon { text-align:right }
    #route_map {  width:100%;
            height:400px;
            padding:0;
            margin:0; }
    .address { cursor:pointer }
    .address:hover { color:#AA0000;text-decoration:underline }

</style>

<div class="container" style="clear:both;">
  <div id="route_map" style="width:50%; float:right;"></div>
  <form>
  <input  id="sad_route" type="text" name="route" size=12 value="123">
  
  <button type="button" onclick="save_route_geo_metadata();">use it</button>
  </form>
  <br>
  

</div>

<script type="text/javascript">

function save_route_geo_metadata(){
    document.getElementById("sad_route").value="hello??";
}


</script>



