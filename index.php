<?php

get_header();

while ( have_posts() ) : the_post();
    get_template_part( 'partials/content', get_post_format() );
endwhile; // end of The Loop
get_footer();
