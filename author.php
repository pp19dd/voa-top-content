<?php

/*
Template Name: Archives
*/

get_header();

the_post();

// get author data for this page
$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

?>
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_archive_title(); ?></h1>
				</div>
			</header>
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<section class="content-part">
					<h1><?php the_archive_title; ?></h1>
				</section>

				<section class="content-part">
					
    <h2>Posts by <?php echo $curauth->nickname; ?>:</h2>

    <ul>
<!-- The Loop -->

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <li>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>">
            <?php the_title(); ?></a>,
            <?php the_time('d M Y'); ?> in <?php the_category('&');?>
        </li>

    <?php endwhile; else: ?>
        <p><?php _e('No posts by this author.'); ?></p>

    <?php endif; ?>

<!-- End Loop -->

    </ul>
				</section>
				
			</content>
			
			
			
			<sidebar>
				<sidebar-inner>
					
					<?php dynamic_sidebar( 'sidebar_archive_page' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>