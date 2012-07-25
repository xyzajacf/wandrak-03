<?php get_header(); ?>
	
		<div id="container">	
			<div id="content" class="wnd_post_data" data-kml="<?php if (get_post_custom_values('kml')) { $myKml = get_post_custom_values('kml'); echo $myKml[0]; } ?>">
			
<?php the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</div><!-- #post-<?php the_ID(); ?> -->			
			
			</div><!-- #content -->		
		</div><!-- #container -->
		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>