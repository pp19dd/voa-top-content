<?php

function voa_redirect() {
	global $post;

	$redirect_url = trim(get_post_meta( $post->ID, "_voa_redirect_url", true ));

	if( strlen( $redirect_url ) > 0 ) {
		wp_redirect( $redirect_url.VOA_METRICS_TRACKING_PARAMETER, 302 );
		exit;
	}
}

// should we redirect or just show the post?
voa_redirect();

get_header();

the_post();

// get the post style; determine if it's a basic or hero layout (default to basic)
$intro_style = get_post_meta( $post->ID, "_voa_post_intro_style", true );
$hero = ( substr( $intro_style, 0, 4 ) == 'hero' ? (bool) true : (bool) false );


if( isset( $o['voa_commenting'] ) && $o['voa_commenting'] == 'facebook' ) {
	// direct FB API call; TODO needs caching and better error checking
	// $comment_count = voa_fb_comment_count( get_the_permalink() );
} elseif ( !isset( $o['voa_commenting'] ) || (isset( $o['voa_commenting'] ) && $o['voa_commenting'] == 'traditional' ) ) {
	$comment_count = get_comments_number();
} else {
	$comment_count = 0;
}


if ( !function_exists( 'voa_the_content') || !is_single() ) {
	function voa_the_content( $content ) {
		global $post;

		$dateline = get_post_meta( $post->ID, "_voa_dateline", true );

		if ( $dateline != '' ) {
			$voa_dateline = '<span class="voa-dateline">'. $dateline .' &mdash; </span>';
			$content  = $voa_dateline . $content;
		}

		return $content;
	}

	add_filter( 'the_content', 'voa_the_content', 0 );
}

?>

<?php if ($hero) {
	get_template_part( 'partials/single-hero-intro' );
} ?>

<rows>
	<row class="rows_1">
		<article>
			<content>
				<?php if (!$hero) { get_template_part( 'partials/single-basic-intro' ); } ?>
				
				<?php
				$o = get_option("voa_opt_comm");
				if( isset( $o['show_author'] ) ) {
				?>
				<section class="content-part article-author">

					<?php $voa_byline = get_post_meta( get_the_ID(), '_voa_byline', true ); ?>

					<?php if ( !$voa_byline ) { ?>
					<div class="author-avatar"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 67 ); ?></a></div>
					<?php } ?>

					<div class="author-pubdate">
						<address class="author">
							<?php if ( $voa_byline ) { echo $voa_byline; } else { ?>
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a>
							<?php } ?>
						</address>
						<?php get_template_part( 'partials/article-date' ); ?>
					</div>

					<div class="author-social"></div>

				</section>
				
				<?php } else { ?>
				
				<section class="content-part pubdate-no-author">
					<div class="author-pubdate">
						<?php get_template_part( 'partials/article-date' ); ?>
					</div>
				</section>
				
				<?php } // end show_author ?>


				<?php get_template_part( "partials/share-buttons" ); ?>


				<section class="content-part article-body"><?php the_content(); ?></section>

				
				<?php get_template_part( "partials/share-buttons" ); ?>


				<section class="content-part article-categories">
					<header><i class="fa fa-tag fa-lg" aria-hidden="true"></i><span class="header-text"><?php _e( 'Categories', 'voa-top-content' ); ?></span></header>
					<?php the_category( ', '); ?>
				</section>


				<section class="content-part article-tags">
					<header><i class="fa fa-tags fa-lg" aria-hidden="true"></i><span class="header-text"><?php _e( 'Tags', 'voa-top-content' ); ?></span></header>
					<?php the_tags( '', ', ', '' ); ?>
				</section>


				<section class="prevnext">

					<section class="content-part adjacent-post previous-post">
						<div class="adjacent-post-inner">
						<?php if ( $prev_post = voa_top_content_adjacent_post('previous') ) { ?>

						<div class="adjacent-post-image"><a href="<?php echo $prev_post['permalink']; ?>"><img src="<?php echo $prev_post['image_url']; ?>" /></a></div>

						<div class="adjacent-post-text">
							<p><a href="<?php echo $prev_post['permalink']; ?>"><?php _e( 'Previous Post', 'voa-top-content' ); ?></a></p>
							<h4><a href="<?php echo $prev_post['permalink']; ?>"><?php echo $prev_post['post_title']; ?></a></h4>
						</div>

						<?php } else { ?>
							<div class="adjacent-post-text no-previous-post">
								<p><?php _e( 'There are no previous posts.', 'voa-top-content' ); ?></p>
							</div>
						<?php } ?>
						</div>
					</section>

					<section class="content-part adjacent-post next-post">
						<div class="adjacent-post-inner">
						<?php if ( $next_post = voa_top_content_adjacent_post('next') ) { ?>

						<div class="adjacent-post-text">
							<p><a href="<?php echo $next_post['permalink']; ?>"><?php _e( 'Next Post', 'voa-top-content' ); ?></a></p>
							<h4><a href="<?php echo $next_post['permalink']; ?>"><?php echo $next_post['post_title']; ?></a></h4>
						</div>

						<div class="adjacent-post-image"><a href="<?php echo $next_post['permalink']; ?>"><img src="<?php echo $next_post['image_url']; ?>" /></a></div>

						<?php } else { ?>
							<div class="adjacent-post-text no-next-post">
								<p><?php _e( "You're viewing the latest post.", 'voa-top-content' ); ?></p>
							</div>
						<?php } ?>
						</div>
					</section>

				</section><!-- end .prevnext -->


				<section id="comment-section" class="content-part comments">
					<?php comments_template(); ?>
				</section><!-- end .comments -->


			</content>
			<sidebar>
				<sidebar-inner>

					<?php if ( comments_open() || have_comments() ) { ?>
					<div id="comment-shortcut"><a href="#comment-section"><span class="comment-icon"><i class="fa fa-comment fa-2x" aria-hidden="true"></i></span><span class="comment-text"><span class="comment-count"><?php 
						printf(
							_n( '%s Comment', '%s Comments', $comment_count, 'voa-top-content' ), 
							number_format_i18n( $comment_count )
						);
						?></span>
						<span class="comment-cta"><?php _e( 'Join the Discussion', 'voa-top-content' ); ?></span></span></a></div>
					<?php } ?>

					<?php dynamic_sidebar( 'sidebar_article_right' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>
