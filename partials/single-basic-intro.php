
<header class="single-basic-intro">
	<h1><?php the_title(); ?></h1>
	<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mi mi, suscipit et egestas et, condimentum vitae est. Fusce venenatis vestibulum nunc lobortis dignissim. Quisque.</h2>
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="featured-img"><?php the_post_thumbnail(); ?></div>
	<?php } ?> 
</header>
