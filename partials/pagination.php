<?php

// Previous/next page navigation.
the_posts_pagination( array(
	'mid_size'           => 1,
	'prev_text'          => 'Newer',
	'next_text'          => 'Older',
	'screen_reader_text' => '<span class="meta-nav screen-reader-text">Page</span>'
) );
