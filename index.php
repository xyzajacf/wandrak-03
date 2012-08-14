<?php get_header(); ?>

<script type="text/javascript">
	var scrollingIndex = 0;
	var bottomBarApi;
	var scrolledToLast = false;

    $(function() {
		$("#locations_left_area").click(function () {
			if (0 <= scrollingIndex - 1 && !scrolledToLast) {
				scrollingIndex--;
			}
			scrolledToLast = false;
			bottomBarApi.scrollToElement($(".bottom_bar_item")[scrollingIndex], true, true);
		});
		$("#locations_right_area").click(function () {
			var currentItem = $($(".bottom_bar_item")[scrollingIndex]);
			var lastItemRightX = $(".bottom_bar_item").last().position().left + $(".bottom_bar_item").last().width();
			if (lastItemRightX
					< bottomBarApi.getContentPositionX() + currentItem.width() + $("#bottom_bar").width()) {
				scrolledToLast = true;
				bottomBarApi.scrollToX(lastItemRightX - $("#bottom_bar").width(), true);
			} else {
				if (scrollingIndex + 1 <= $(".bottom_bar_item").size()) {
					scrollingIndex++;
					currentItem = $($(".bottom_bar_item")[scrollingIndex]);
				}
				scrolledToLast = false;
				bottomBarApi.scrollToElement(currentItem, true, true);
			}
		});
		$("#bottom_bar").jScrollPane({
			showArrows: false,
			animateScroll: true
		});
		bottomBarApi = $("#bottom_bar").data('jsp');
		$("div.jspPane").css("margin-left", "0px");

		// index - mouse hovers
		$(".bottom_bar_item").mouseenter(function() {
	          $el = $(this);
	          if (wnd_ctaLayer) {
	             wnd_ctaLayer.setMap(null);
	          }
	          if ($el.data('kml')) {
	             wnd_ctaLayer = new google.maps.KmlLayer($el.data('kml'));
	             wnd_ctaLayer.setMap(map);
	          }
	
		    $("#more-info")
		      .find("h2")
		        .html($el.find("h2").html())
		        .end()
		      .find(".excerpt")
		        .html($el.find(".longdesc").html());
		});

		$("#bottom_content .bottom_bar_item:first").trigger("mouseenter");

		$(".bottom_bar_item").click(function () {
			if ($(this).hasClass('bottom_bar_item')) {
        		window.location.href = $(this).data('articleurl');
			} else {
        		window.location.href = $(this).parents('.bottom_bar_item').data('articleurl');
			}
        	window.location.reload();
		});
    });
</script>

<?php
	query_posts("post_type=page&post_parent=0");
?>

	<div id="container">
		<div id="content">
			<div id="locations_left_area"></div>
			<div id="locations_right_area"></div>
			<div id="bottom_bar">
				<div id="bottom_content">
					<?php while ( have_posts() ) : the_post() ?>
						<article class="bottom_bar_item" data-kml="<?php if (get_post_custom_values('kml')) { $myKml = get_post_custom_values('kml'); echo $myKml[0]; } ?>"
								data-articleurl="<?php the_permalink(); ?>">
							<div class="bottom_bar_item_inner" >
								<div class="post-day"><?php the_time('d') ?></div>
								<div class="post-month"><?php the_time('M') ?> – <?php the_time('Y') ?></div>
								<div class="entry-meta">
									<h2 class="entry-title"><?php the_title() ?></h2>
									<div class="entry-date">
									</div>
									<div class="entry-foto"></div>
									<div class="longdesc"><?php the_content( __('Continue Reading &rarr;','wandrak-02' ) ); ?>
									<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'wandrak-02' ) . '&after=</div>') ?></div>
								</div><!-- .entry-meta -->
							</div>
						</article>
					<?php endwhile; ?>
				</div>
			</div>

			<div id="more-info">
				<h2 class="preview"></h2>
				<div class="excerpt"></div>
			</div>			
		</div><!-- #content -->		
	</div><!-- #container -->

		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>
