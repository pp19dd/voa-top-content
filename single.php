<?php get_header(); ?>

<?php
the_post();
#$id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

?>
<rows>
	<row class="rows_1">
		<h1><?php bloginfo("name") ?></h1>
	</row>
</rows>
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_title(); ?></h1>
				</div>
			</header>
			<div class="undermedia"><img src="<?php echo $image ?>" /></div>
			<!-- <leading>
				<inner class="hovering">
					<anchor>
						<headline><span><a><?php the_title(); ?></a></span></headline>
						<excerpt>test</excerpt>
						<continue>Click Me</continue>
					</anchor>
					<a href="#"><img src="<?php echo $image ?>" /></a>
				</inner>
			</leading> -->
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		<article>
			<content>
				<section class="content-part article-author">
					
					<?php $voa_byline = get_post_meta( get_the_ID(), '_voa_byline', true ); ?>
					<div class="author-avatar"><?php if ( !$voa_byline ) { echo get_avatar( get_the_author_meta( 'ID' ), 67 ); } ?></div>

					<div class="author-pubdate">
						<address class="author"><?php if ( $voa_byline ) { echo $voa_byline; } else { the_author(); } ?></address>
						<time class="pubdate"><?php the_date(); ?></time>
					</div>

					<div class="author-social"></div>

				</section>

				
				<section class="content-part article-body"><?php the_content(); ?></section>

				
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
							<p>There are no previous posts.</p>
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
							<p>You're viewing the latest post.</p>
						<?php } ?>
						</div>
					</section>

				</section><!-- end .prevnext -->

				
				<section class="content-part comments">

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
					<?php dynamic_sidebar( 'sidebar_article_right' ); ?>
				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>
