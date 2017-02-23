<?php 

function voa_redirect() {
	global $post;
	
	$redirect_url = get_post_meta( $post->ID, "_voa_redirect_url", true );
	
	if( strlen(trim( $redirect_url )) > 0 ) {
		wp_redirect( $redirect_url.$voa_metrics_tracking_parameter, 302 );
		exit;
	}
}

// should we redirect or just show the post?
voa_redirect();

get_header();

the_post();

$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

// direct FB API call; TODO needs caching and better error checking
//$comment_count = voa_fb_comment_count( get_the_permalink() );


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
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_title(); ?></h1>
				</div>
			</header>
			<div class="undermedia"><img src="<?php echo $image; ?>" /></div>
			<!-- <div class="undermedia-caption">Here's some text.</div> -->
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		<article>
			<content>
				<section class="content-part article-author">
					
					<?php $voa_byline = get_post_meta( get_the_ID(), '_voa_byline', true ); ?>
					
					<?php if ( !$voa_byline ) { ?>
					<div class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 67 ); ?></div>
					<?php } ?>

					<div class="author-pubdate">
						<address class="author"><?php if ( $voa_byline ) { echo $voa_byline; } else { the_author(); } ?></address>
						<time class="pubdate"><?php the_date(); ?></time>
					</div>

					<div class="author-social"></div>

				</section>
				
				
				<?php get_template_part( "partials/share-buttons" ); ?>
				
				
				<section class="content-part article-body"><?php the_content(); ?></section>
				
				
				<?php get_template_part( "partials/share-buttons" ); ?>

				
				<section class="content-part article-categories">
					<header><i class="fa fa-tag fa-lg" aria-hidden="true"></i><span class="header-text">Categories</span></header>
					<?php the_category( ', '); ?>
				</section>

				
				<section class="content-part article-tags">
					<header><i class="fa fa-tags fa-lg" aria-hidden="true"></i><span class="header-text">Tags</span></header>
					<?php the_tags( '', ', ', '' ); ?>
				</section>


				<section class="prevnext">
					
					<section class="content-part adjacent-post previous-post">
						<div class="adjacent-post-inner">
						<?php if ( $prev_post = voa_top_content_adjacent_post('previous') ) { ?>
						
						<div class="adjacent-post-image"><a href="<?php echo $prev_post['permalink']; ?>"><img src="<?php echo $prev_post['image_url']; ?>" /></a></div>
						
						<div class="adjacent-post-text">
							<p><a href="<?php echo $prev_post['permalink']; ?>">Previous Post</a></p>
							<h4><a href="<?php echo $prev_post['permalink']; ?>"><?php echo $prev_post['post_title']; ?></a></h4>
						</div>

						<?php } else { ?>
							<div class="adjacent-post-text no-previous-post">
								<p>There are no previous posts.</p>
							</div>
						<?php } ?>
						</div>
					</section>

					<section class="content-part adjacent-post next-post">
						<div class="adjacent-post-inner">
						<?php if ( $next_post = voa_top_content_adjacent_post('next') ) { ?>
						
						<div class="adjacent-post-text">
							<p><a href="<?php echo $next_post['permalink']; ?>">Next Post</a></p>
							<h4><a href="<?php echo $next_post['permalink']; ?>"><?php echo $next_post['post_title']; ?></a></h4>
						</div>

						<div class="adjacent-post-image"><a href="<?php echo $next_post['permalink']; ?>"><img src="<?php echo $next_post['image_url']; ?>" /></a></div>

						<?php } else { ?>
							<div class="adjacent-post-text no-next-post">
								<p>You're viewing the latest post.</p>
							</div>
						<?php } ?>
						</div>
					</section>

				</section><!-- end .prevnext -->

				
				<section id="comment-section" class="content-part comments">

					<header>Comments</header>

					<div class="comments-inner">

						<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="5"></div>

						<?php /*
						
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=<?php echo FB_APP_ID; ?>";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
						
						*/ ?>

					</div>


				</section><!-- end .comments -->
			</content>
			<sidebar>
				<sidebar-inner>
					
					<?php /*<div id="comment-shortcut"><a href="#comment-section"><span class="comment-icon"><i class="fa fa-comment fa-2x" aria-hidden="true"></i></span><span class="comment-text"><span class="comment-count"><?php echo $comment_count . ' Comment' . ($comment_count != 1 ? 's' : ''); ?></span> <span class="comment-cta">Join the Discussion</span></span></a></div>*/ ?>
					
					<?php dynamic_sidebar( 'sidebar_article_right' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>
