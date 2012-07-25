<?php get_header(); ?>

<script type="text/javascript">
    $(function() {
    	var controlsWidthAdd = 82;
    	var itemsPerPage = Math.floor(($("#carousel_holder").width() - controlsWidthAdd) / $("#carousel li").outerWidth());
    	$("<style type='text/css'> .carousel-wrap { width: " + (itemsPerPage * $("#carousel li").outerWidth()) + "px; } </style>").appendTo("head");
        $("#carousel").carousel( { dispItems: itemsPerPage } );
    	$("#carousel").width(
    		$("#carousel .carousel-wrap").outerWidth() + controlsWidthAdd);
    });
</script>
	
		<div id="container">	
			<div id="content">
		
		<div id="carousel_holder">
			<div id="carousel">
<ul id="locations">

<?php while ( have_posts() ) : the_post() ?>


<li data-kml="<?php if (get_post_custom_values('kml')) { $myKml = get_post_custom_values('kml'); echo $myKml[0]; } ?>">
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'wandrak-02'), the_title_attribute('echo=0') ); ?>">
				<?php printf( __('Permalink to %s', 'wandrak-02'), the_title_attribute('echo=0') ); ?>
			</a>
		</h2>
		<div class="entry-meta">
				<span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'wandrak-02' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
				<span class="meta-sep"></span>
				<span class="meta-prep meta-prep-entry-date"><?php _e('', 'wandrak-02'); ?></span>
				<div class="longdesc"><?php the_content( __('Continue Reading &rarr;','wandrak-02' ) ); ?>
				<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'wandrak-02' ) . '&after=</div>') ?></div>
		</div><!-- .entry-meta -->
</li>

<!-- #entry-content -->


				
<?php comments_template(); ?>
	
<?php endwhile; ?>		

</ul>
</div>
</div>
<div id="more-info">
					<h2></h2>
					<div class="excerpt"></div>
</div>			
			</div><!-- #content -->		
		</div><!-- #container -->

		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>
