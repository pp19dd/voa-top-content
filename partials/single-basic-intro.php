
<header class="single-basic-intro">
	<h1><?php the_title(); ?></h1>
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="featured-img"><?php the_post_thumbnail(); ?></div>
	<?php } ?> 
</header>
