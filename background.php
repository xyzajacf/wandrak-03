<?php require_once(TEMPLATEPATH.'/map.php'); ?>
<div id="background">
	  <div id="single-post-map" style="width:100%; height:100%;"></div>
</div>

<?php
/*
Plugin Name: RomeLuv Google Maps for WordPress
Plugin URI: http://www.romeluv.com/maps-plugin-testrun/
Description:  Tento plugin vie nacitat vsetky posty a zobrazit ich na globalnej mape v stranke.
Version: 1.3.3
Author: RomeLuv
Author URI: http://www.romeluv.com
License: GPL v2
 
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 
 
*/







//defaults
if (strlen (get_option ('global_romeluv_markers_url')) <8) update_option('global_romeluv_markers_url','http://maps.google.com/mapfiles/kml/pal2/icon2.png');
if (  (get_option ('global_romeluv_markerwidth')) <1) update_option('global_romeluv_markerwidth','30');
if (  (get_option ('global_romeluv_markerheight')) <1) update_option('global_romeluv_markerheight','30');
  


/* Define the custom box */

// WP 3.0+
// add_action( 'add_meta_boxes', 'romeluv_maps_add_custom_box' );

// backwards compatible
add_action( 'admin_init', 'romeluv_maps_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'romeluv_maps_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function romeluv_maps_add_custom_box() {
    add_meta_box( 
        'romeluv_maps_sectionid',
        __( 'Maps', 'romeluv_maps_textdomain' ),
        'romeluv_maps_inner_custom_box',
        'post' 
    );
  
}

/* Prints the box content */
function romeluv_maps_inner_custom_box() {
global $post;
  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'romeluv_maps_noncename' );

  // The actual fields for data entry
  echo '<label for="romeluv_maps_address_field">';
       _e("Address", 'romeluv_maps_textdomain' );
  echo '</label> ';
  echo '<input type="text" id="romeluv_maps_address_field" name="romeluv_maps_address_field" value="'. get_post_meta($post->ID,'address',true).'" size="35" /> example: Viale Kant 2, Roma';
}

/* When the post is saved, saves our custom data */
function romeluv_maps_save_postdata( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['romeluv_maps_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data

 $data=$_POST['romeluv_maps_address_field'];
 
	update_post_meta($_POST['post_ID'], 'address', $data);
  // Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)

   return $mydata;
}





 
if (get_option('single_romeluv_mapposition') !=2) add_filter('the_content', 'romeluv_single_map'); ///adds a map in single posts right before the content
 








add_action('admin_menu', 'rl_google_maps_api_menu');




function rl_google_maps_api_menu() {
add_options_page('Google Maps API Key', 'RomeLuv Google Maps API Key & Map settings', 'manage_options', 'romeluv-maps-identifier', 'gma_romeluv_plugin_options');
}





function gma_romeluv_plugin_options() {
?>
<div style='margin:15px;'>
<h2>RomeLuv Google Maps</h2>
<?php
if (array_key_exists('maps_key',$_POST)) {

update_option('maps_key',$_POST['maps_key']);
update_option('global_romeluv_markers',$_POST['global_romeluv_markers']);
update_option('single_romeluv_mapposition',$_POST['single_romeluv_mapposition']);
update_option('global_romeluv_markers_url',$_POST['global_romeluv_markers_url']);

update_option('global_romeluv_markerwidth',$_POST['global_romeluv_markerwidth']);
update_option('global_romeluv_markerheight',$_POST['global_romeluv_markerheight']);
 
 
if (strlen (get_option ('global_romeluv_markers_url')) <8) update_option('global_romeluv_markers_url','http://maps.google.com/mapfiles/kml/pal2/icon2.png');
if (  (get_option ('global_romeluv_markerwidth')) <1) update_option('global_romeluv_markerwidth','30');
if (  (get_option ('global_romeluv_markerheight')) <1) update_option('global_romeluv_markerheight','30');
  
}
?>
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<?php
settings_fields('maps_key');
?>

<style>
#rlmoptions {}
#rlmoptions tr {height:35px}

#rlmoptions td {text-align:left}
#rlmoptions th {text-align:left;width:250px;}
</style>
<p><small>Consider viewing a quick <a target="new" href='http://screencast.com/t/pmbe87rbbF34'>introduction video</a> for getting started. </small></p>
<p><i>Only the first setting is vital for getting started: if you need one,  <a target="new" href="http://code.google.com/apis/maps/signup.html">Get a free Google Maps API Key from Google!</a>
 </i></p>
<table id='rlmoptions'>
<tr valign="top">
<th scope="row">Google Maps API Key</th>
<td > <input type="text" size="40" name="maps_key" value="<?php echo get_option('maps_key'); ?>" /> <b>Mandatory.</b><br /><br /><br /><br />
</td>
</tr>   
<tr valign="top">
    
    
    
    
    
    
    
    
<th scope="row">Global Map Markers:  </th>
<td>
    

    
    <select name="global_romeluv_markers">
  <option  value="<?php echo get_option('global_romeluv_markers'); ?>" >  <?php $global_romeluv_markers= get_option('global_romeluv_markers');
  if ($global_romeluv_markers==0) echo "Your custom image URL  ";
   if ($global_romeluv_markers==1) echo "Use post thumbnail if available";
 
  ?>  </option>
  
  
  <option value="0">Your custom image URL</option>
  <option value="1">Use post thumbnail if available</option>
 
</select>
    
    
      </td>
</tr>

<tr valign="top">
<th scope="row">Your  image URL for the map markers </th>
<td><input type="text" size="40" name="global_romeluv_markers_url" value="<?php   echo get_option('global_romeluv_markers_url');  ?>" /> (example:  http://maps.google.com/mapfiles/kml/pal2/icon2.png </td>
</tr>

<tr valign="top">
<th scope="row">Global Map: Marker width</th>
<td><input type="text" size="3" name="global_romeluv_markerwidth" value="<?php echo get_option('global_romeluv_markerwidth'); ?>" /> px  </td>
</tr>
<tr valign="top">
<th scope="row">Global Map: Marker height</th>
<td><input type="text" size="3" name="global_romeluv_markerheight" value="<?php echo get_option('global_romeluv_markerheight'); ?>" /> px  </td>
</tr>

</table>
<input type="hidden" name="page_options" value="maps_key" />
<p>
<input type="submit" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>
<?
}









































function romeluv_maps_handle_savepost() {
	global  $post_ID;
	 
	 
	 //geocode with service the address
	 $address=get_post_meta($post_ID,'address',true);
	if (strlen($address)<8) {
				update_post_meta($post_ID, 'longitude', 0);
				update_post_meta($post_ID, 'latitude', 0);
				return ('');
				
				}
	 $address=str_replace(' ','+',$address);
	 $address1 ="http://maps.google.com/maps/geo?q=1020+".$address."&output=xml&key=".get_option('maps_key');
	$page = file_get_contents($address1);
	$xml = new SimpleXMLElement($page);
	list($longitude, $latitude, $altitude) = explode(",",
	$xml->Response->Placemark->Point->coordinates);


	update_post_meta($post_ID, 'longitude', $longitude);
	update_post_meta($post_ID, 'latitude', $latitude);
	 
}

add_action('save_post', 'romeluv_maps_handle_savepost');

















function romeluv_single_map($post_content_html)


{       global $romeluv_single_map_done;
        if ($romeluv_single_map_done) return $post_content_html; else $romeluv_single_map_done=TRUE;
    
    
	if (!is_single()) return $post_content_html;
        //return "".$post_content_html;
	global $wpdb,$post,$mapheight;
	$savepost=$post;
        $mapwidth=get_option('single_romeluv_mapwidth');
        $mapheight=get_option('single_romeluv_mapheight');
	if ($mapwidth<40) $mapwidth="100%"; else $mapwidth.='px';
	if ($mapheight<40) $mapheight="320px"; else $mapheight.='px';
        
       

	
	       
	   ///get values from post custom fields
	   
	  $address=get_post_meta($post->ID,'address',true);
	  $latitude=get_post_meta($post->ID, 'latitude');
	  $longitude=get_post_meta($post->ID, 'longitude');
	  $latitude=$latitude[0]; $longitude=$longitude[0];
	   
	   
	 	  
	  if ($latitude!=0 OR $longitude!=0) {
		  
		  
		  //initialize the map
		  
		  $out=' <!-- single post map created by RomeLuv Google Maps http://www.romeluv.com/maps-plugin-testrun/ -->
			    
			    
			     <script src="http://maps.google.com/maps/api/js?sensor=false" 
				     type="text/javascript"></script>
			   
			   

			   
			     <script type="text/javascript">
			    
			       var map = new google.maps.Map(document.getElementById("single-post-map"), {
				 zoom: 17,
				 center: new google.maps.LatLng('.$latitude.', '.$longitude.'),
				 mapTypeId: google.maps.MapTypeId.ROADMAP
			       });
			   
			       var infowindow = new google.maps.InfoWindow();
			   
			       var marker, i;
	       ';
	      
	      
	      //add marker to the map and the popup
		$out.= '
		   
			 
		    marker = new google.maps.Marker({
		      position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
		      map: map,
                       animation: google.maps.Animation.DROP,
		    

		    });
	      
		    google.maps.event.addListener(marker, "click", (function(marker, i) {
		      return function() {
			infowindow.setContent("<p><span class=\'romeluv-google-map-link\'  >'.get_the_title().'</span>  ';
                 				
	      $out.='<br />'."Address: <b>".get_post_meta($post->ID,'address',true).' </b></p>  ';
	      
	       
	      
		$out.='");
		infowindow.open(map, marker);
	      }
	    })(marker, i));
	    
	      
	     ';
	
	     
      //close the map
	$out.='   </script> ';
	
      }  //end if
			   
					      
			      
				      
  
	 $post=$savepost;

    ///    $out now represents the HTML / JS code of the Google map
	if (get_option('single_romeluv_mapposition') ==1) return  $post_content_html.$out; //places the single map BEFORE the content. INVERT that for having the map after the content if you need so

 

 	if (get_option('single_romeluv_mapposition') ==2) return  $out; //places the single map only when called by the php code:
                                                                    /// <?php global $romeluv_single_map_done;$romeluv_single_map_done=FALSE; if (function_exists(romeluv_single_map)) echo romeluv_single_map('');  
        
        
        //default
         return $out.$post_content_html; //places the single map BEFORE the content.


	} //end function single map














































add_shortcode('GLOBALMAP', 'romeluv_global_map');


 



function romeluv_global_map($atts)
{
    
    if (is_single()) return('');  // avoid making trouble if looking at a single post. The  GLOBALMAP shorttag is meant to be used in pages only.
    
    //global $romeluv_global_map_done;
  //  if ($romeluv_global_map_done) return ''; else $romeluv_global_map_done=TRUE; //allow only one map per page
    
    global $wpdb,$post;
    $savepost=$post;
    
    ////query all the posts to display on the global map
     $querystr = "
       SELECT wposts.* 
       FROM $wpdb->posts wposts 
       WHERE  wposts.post_status = 'publish' 
       AND wposts.post_type = 'post' ". $whereadditional ."
       ORDER BY wposts.post_date DESC
    ";
    //echo $querystr; //useful for debugging your custom query
    
    
    $result_posts = $wpdb->get_results($querystr, OBJECT);
    
    
    
    
    if ($result_posts):
    
    if (isset($_GET[cat]))  echo '<h3 id="map-category-heading">'.get_cat_name($_GET[cat]).'</h3>';
    
    
    
    $mapwidth=get_option('global_romeluv_mapwidth');
    $mapheight=get_option('global_romeluv_mapheight');
    if ($mapwidth<40) $mapwidth="100%"; else $mapwidth.='px';
     if ($mapheight<40) $mapheight="320px"; else $mapheight.='px';
    
    
    $out='<!-- global post map created by RomeLuv Google Maps for WordPress http://www.romeluv.com/maps-plugin-testrun/ -->
           <script src="http://maps.google.com/maps?file=api&amp;v=3&amp;sensor=false&amp;key='. get_option('maps_key').'" type="text/javascript"></script>
           
            <script src="http://maps.google.com/maps/api/js?sensor=false"  type="text/javascript"></script>
          
          
          <div id="romeluv-global-map" style="width: '.$mapwidth.'; height:'. $mapheight.'; '.get_option('single_romeluv_mapstyle').' "></div>
          
            <script type="text/javascript">
           
              var map = new google.maps.Map(document.getElementById("romeluv-global-map"), {
                zoom: 17,
                
                mapTypeId: google.maps.MapTypeId.ROADMAP
              });
          
              var infowindow = new google.maps.InfoWindow();
            var bounds = new google.maps.LatLngBounds();
              var marker, i;
           ';
          
          
          
     if(is_category()  ){
                        $cat_ID = get_query_var('cat');
            }
            
            
            
    foreach ($result_posts as $post): 
                           
                             $count++;  
                           
                           if (isset($_GET[cat])) if (!in_category($_GET[cat],$post->ID)) continue; //this allows category filtering adding the $_GET parameter ?cat=xx
                           
                           if (is_category()) { if (!in_category($cat_ID,$post->ID)) continue;  }   //skip posts if viewing a category page, if those do not match the current category
                           
                           
                          $address=get_post_meta($post->ID,'address',true);
                          $latitude=get_post_meta($post->ID, 'latitude');
                          $longitude=get_post_meta($post->ID, 'longitude');
                          $latitude=$latitude[0]; $longitude=$longitude[0];
                           
                           
                           if (!$latitude>0 or !$longitude>0) { //no data set: update the post custom fields according to the address
                                       
                                         // $post_ID=$post->ID;
                                        
                                        
                                        
                                        
                                        
                                         if ($_GET[geocode_all_posts]==1){
                                                                                
                                                                                romeluv_maps_handle_savepost();   // if you want to re-geocode all posts -
                                                                             //i.e. you started with an old blog with a custom field name 'address' and you want to geocode all that in a snap,
                                                                             /// call the url of a map page with the ?geocode_all_posts=1 parameter
                                                                             
                                                                              $latitude=get_post_meta($post->ID, 'latitude');
                                                                              $longitude=get_post_meta($post->ID, 'longitude');
                                                                               $latitude=$latitude[0]; $longitude=$longitude[0];
                                                                           }
                                                                           
                                                                           
                                                                           
                                          }
                                          
                                          
                          if ($latitude!=0 OR $longitude!=0) {
					   
					   
					    $image_url=FALSE;$std_thumb=FALSE;
                                             $image_url= wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
					      if ( !$image_url or get_option('global_romeluv_markers')==0 ) {$image_url[0]=get_option('global_romeluv_markers_url'); $std_thumb=TRUE;}
					     if ($image_url)  $out.='  var myIcon = new google.maps.MarkerImage("'.$image_url[0].'", null, null, null, new google.maps.Size('.get_option('global_romeluv_markerwidth').','.get_option('global_romeluv_markerheight').'));';
					    
					     
                                          $out.= '
                                   
                                        
                                        marker = new google.maps.Marker({
                                          position: new google.maps.LatLng('.$latitude.', '.$longitude.'),
                                          map: map,
					   icon:  myIcon

                                        });
                                  bounds.extend(marker.position);
                                        google.maps.event.addListener(marker, "mousedown", (function(marker, i) {
                                          return function() {
                                            infowindow.setContent("<p style=\'width:95%\'><a class=\'romeluv-google-map-link\' href=\''.get_permalink($post->ID).'\'><b>'.get_the_title().'</b></a><br /> ';
                                            
                                            
                                            
                                            ///if thumbnail image is defined in the theme, show it in the map popups.
                                          
                                            if (!$std_thumb)  $out.="<a href='".get_permalink($post->ID)."'><img  src='".$image_url[0]."' ></a><br />";
                                            
                                            
                                            ///list post categories
                                            foreach((get_the_category()) as $category) { 
                                                          $out.= '<i>'.$category->cat_name . '</i>  '; 
                                                      }
                                                      
                                                      
                                            //add the address
                                          $out.='<br />'."Address: <b>".get_post_meta($post->ID,'address',true).' </b><br /><br /></p>';
                                          
                                           
                                          
                                            $out.='");
                                            infowindow.open(map, marker);
                                          }
                                        })(marker, i));
                                        
                                          
                                         ';
                                  }
                                           
                                          
                          
                                  
    
    endforeach;  
    
    
    $out.=' 
       //  Fit these bounds to the map
    map.fitBounds(bounds);
     </script>
    <div style="font-size:11px; text-align:right;width:99%;height:20px"> Map By <a href="http://www.romeluv.com/maps-plugin-testrun/">RomeLuv</a> </div>
     ';
     
    
    else : 
    
    $out.='No elements to show on the map.';
     
    endif; ?>
    
    <?php    
          
    
    
     $post=$savepost;
    
    return $out;

  
 
	}







?>
