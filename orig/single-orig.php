<?php get_header(); ?>
	
		<div id="container">	
			<div id="content">
			
<?php the_post(); ?>

				<div id="nav-above" class="navigation">
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
				</div><!-- #nav-above -->

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>
					
					<div class="entry-meta">
						<span class="meta-prep meta-prep-author"><?php _e('', 'wandrak-02'); ?></span>
						<span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'wandrak-02' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
						<span class="meta-sep"></span>
						<span class="meta-prep meta-prep-entry-date"><?php _e('', 'wandrak-02'); ?></span>
						
						<div class="entry-date">
							<span class="post-day"><?php the_time('d') ?></span>
							<div class="month-year">
							<span><?php the_time('M') ?></span>. – <span><?php the_time('Y') ?></span>
							</div>          
						</div>
						
					</div><!-- .entry-meta -->
					
					<div class="entry-content">
<?php the_content(); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'wandrak-02' ) . '&after=</div>') ?>
					</div><!-- .entry-content -->
					
					<div class="entry-utility">
					<?php printf( __( 'This entry was posted in %1$s%2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>. Follow any comments here with the <a href="%5$s" title="Comments RSS to %4$s" rel="alternate" type="application/rss+xml">RSS feed for this post</a>.', 'wandrak-02' ),
						get_the_category_list(', '),
						get_the_tag_list( __( ' and tagged ', 'wandrak-02' ), ', ', '' ),
						get_permalink(),
						the_title_attribute('echo=0'),
						comments_rss() ) ?>

<?php if ( ('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Comments and trackbacks open ?>
						<?php printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'wandrak-02' ), get_trackback_url() ) ?>
<?php elseif ( !('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Only trackbacks open ?>
						<?php printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'wandrak-02' ), get_trackback_url() ) ?>
<?php elseif ( ('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Only comments open ?>
						<?php _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'wandrak-02' ) ?>
<?php elseif ( !('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Comments and trackbacks closed ?>
						<?php _e( 'Both comments and trackbacks are currently closed.', 'wandrak-02' ) ?>
<?php endif; ?>
<?php edit_post_link( __( 'Edit', 'wandrak-02' ), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>" ) ?>
					</div><!-- .entry-utility -->													
				</div><!-- #post-<?php the_ID(); ?> -->			
				
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
				</div><!-- #nav-below -->					

<?php comments_template('', true); ?>			
			
			</div><!-- #content -->		
		</div><!-- #container -->
		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>