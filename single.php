<?php get_header(); ?>

<?php
the_post();
#$id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

?>
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_title(); ?></h1>
				</div>
			</header>
			<div class="undermedia"><img src="<?php echo $image ?>" /></div>
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

						<div class="fb-comments" data-href="http://blogs.voanews.com/REPLACE_THIS_WITH_REAL_URL" data-width="100%" data-numposts="5"></div>

						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=490219017808002";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>

					</div>


				</section><!-- end .comments -->
			</content>
			<sidebar>
				<sidebar-inner>
					
					<div id="comment-shortcut"><a href="#comment-section"><span class="comment-icon"><i class="fa fa-comment fa-2x" aria-hidden="true"></i></span><span class="comment-text"><span class="comment-count">17 Comments</span> <span class="comment-cta">Join the Discussion</span></span></a></div>

					<?php dynamic_sidebar( 'sidebar_article_right' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>
