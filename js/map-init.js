/*my original script 
var geocoder;
var map;
var marker;
   
function initialize(){
//MAP
  var latlng = new google.maps.LatLng(41.659,-4.714); //tuto hodnotu zadat cez custom fields
  var options = {
    zoom: 16, //tuto hodnotu zadat cez custom fields
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
       
  map = new google.maps.Map(document.getElementById("map_canvas"), options);
       
  //GEOCODER
  geocoder = new google.maps.Geocoder();
       
  marker = new google.maps.Marker({
    map: map,
    draggable: true
  });
               
} */


/* kml script <script type="text/javascript">
<?php $kml=get_post_meta($post->ID,'kml',true); ?> 
function initialize() {
  var europe = new google.maps.LatLng(46.980252,16.54541);
  var myOptions = {
    zoom: 1,
    center: europe,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  var ctaLayer = new google.maps.KmlLayer('<?php echo $kml; ?>');
  ctaLayer.setMap(map);
} */
/*initialize map without using >>body onload="initialize()"<< 
    window.onload = function () { 
        initialize();
    }*/
/*</script> kml script*/