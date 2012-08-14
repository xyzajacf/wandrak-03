<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <title><?php
        if ( is_single() ) { single_post_title(); }
        elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
        elseif ( is_page() ) { single_post_title(''); }
        elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
        elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
        else { bloginfo('name'); wp_title('|'); get_page_number(); }
    ?></title>
	
	<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/styles/jquery.jscrollpane.css" />
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php wp_head(); ?>
	
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'wandrak-02' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'wandrak-02' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />	

<!-- JS Google Maps API -->
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<!--script src="<?php echo get_template_directory_uri(); ?>/js/map-init.js" type="text/javascript"></script-->
<!--script src="<?php echo get_template_directory_uri(); ?>/js/jcarousel.js" type="text/javascript"></script-->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/underscore-min.js"></script>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.jscrollpane.min.js"></script>

<script type="text/javascript">
  var wnd_ctaLayer = null;
  var defaultLocation = new google.maps.LatLng(46.980252, 16.54541);

  $(function() {

	var europe = new google.maps.LatLng(46.980252, 16.54541),
	    pointToMoveTo, 
	    first = true,
	    curMarker = new google.maps.Marker({}),
	    $el;
	
	var myOptions = {
	    zoom: 8,
	    center: europe,
	    disableDefaultUI: true,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  };

	map = new google.maps.Map($("#map_canvas")[0], myOptions);

	if (typeof onReadyLocal == 'function') {
		onReadyLocal();
	}
  });
</script>


<!-- Google Webfonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:800|Neuton:400,400italic&subset=latin-ext' rel='stylesheet' type='text/css'>
</head>
<div id="background">
	  <div id="map_canvas" style="width:100%; height:100%;"></div>
</div>
<body <?php body_class(); ?>>	
<div id="wrapper" class="hfeed">
		<div id="header">
		<div id="masthead">
		
			<div id="branding">
				<div id="blog-title"><span><a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home"><?php bloginfo( 'name' ) ?></a></span></div>
<?php if ( is_home() || is_front_page() ) { ?>
		    		<h1 id="blog-description"><?php bloginfo( 'description' ) ?></h1>
<?php } else { ?>	
		    		<div id="blog-description"><?php bloginfo( 'description' ) ?></div>
<?php } ?>
			</div><!-- #branding -->
			
			<div id="access">
				<div class="skip-link"><a href="#content" title="<?php _e( 'Skip to content', 'wandrak-02' ) ?>"><?php _e( 'Skip to content', 'wandrak-02' ) ?></a></div>
				<?php wp_page_menu( 'sort_column=menu_order' ); ?>			
			</div><!-- #access -->

			<div id="editorTools">
				<input type="button" value="Add poi" class="add_poi_button"/>
			</div>
			
		</div><!-- #masthead -->	
	</div><!-- #header -->

	<div id="main">
