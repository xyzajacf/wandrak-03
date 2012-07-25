<?php

if (is_home()) {

	$BG_type = get_option('home_BG_type');
	$BG_value = get_option('home_BG_value');
	
} else {

	$BG_type = get_post_meta($post->ID, 'BG_type', $single = true);
	$BG_value = get_post_meta($post->ID, 'BG_value', $single = true);
}

?>


<!-- background start -->
<div id="background">


<!--loading background-->
	<?php if ($BG == 'color') { ?>
<!--loading background end-->
	
    
<!--loading google maps-->
	<?php } elseif ($MAP == 'google') {?>
	<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo $MAP_scr; ?>"></iframe>
<!--loading google maps end-->

<!--loading scrible maps-->
<!--functions: mt:type control; d=drag; z=zoom; p=narows; l=???-->
	<?php } elseif ($MAP == 'scrible') {?>
	<script type="text/javascript" src="http://widgets.scribblemaps.com/js/map/?height=100%&width=100%&d=true&z=false&p=false&mt=false&id=<?php echo $MAP_scr; ?>&l=false"></script>
    </script>	    
	<?php } ?>    
 <!--loading scrible maps end-->   
</div>
<!-- background end -->