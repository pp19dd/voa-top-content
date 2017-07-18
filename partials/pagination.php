<?php

// Previous/next page navigation.
the_posts_pagination( array(
	'mid_size'           => 1,
	'prev_text'          => __( 'Newer', 'voa-top-content' ),
	'next_text'          => __( 'Older', 'voa-top-content' ),
	'screen_reader_text' => '<span class="meta-nav screen-reader-text">'.__( 'Page', 'voa-top-content' ).'</span>'
) );
