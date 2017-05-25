<?php

// add special meta tags to the <head>
add_action( 'wp_head', 'voa_head_meta_tags' );

function voa_head_meta_tags() {
	
	global $post;
	
	if ( is_single() ) {
		?>
		<meta name="author" content="<?php echo esc_attr( voa_head_meta_author() ); ?>" />
		<?php
	}
	
	if ( is_single() && has_post_thumbnail( $post->ID ) ) {
		// get share image for post
		$voa_share_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'fb-share-image' );
	} else {
		// use default voanews.com share image
		$voa_share_image = array( get_template_directory_uri().'/img/top_logo_news.png', 600, 600 );
	}
	?>
	
	<link rel="image_src"               href="<?php echo esc_attr( $voa_share_image[0] ); ?>" />
	<meta name="twitter:image"       content="<?php echo esc_attr( $voa_share_image[0] ); ?>" />
	<meta property="og:image"        content="<?php echo esc_attr( $voa_share_image[0] ); ?>" />
	<meta property="og:image:width"  content="<?php echo esc_attr( $voa_share_image[1] ); ?>" />
	<meta property="og:image:height" content="<?php echo esc_attr( $voa_share_image[2] ); ?>" />
	
	<?php
	
}



function voa_head_meta_author() {
	global $post;
	
	$o = get_option("voa_opt_comm");
	
	if( isset( $o['show_author'] ) ) {
		
		$voa_byline = trim( get_post_meta( $post->ID, '_voa_byline', true ) );
			
		if ( $voa_byline != '' ) {
			$m_author = $voa_byline;
		} else {
			$m_author = get_the_author_meta( 'user_nicename', $post->post_author );
		}
	}
	
	return $m_author;
}
