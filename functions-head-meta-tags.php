<?php

// add special meta tags to the <head>
add_action( 'wp_head', 'voa_head_meta_tags' );

function voa_head_meta_tags() {
	
	// print the author meta tag?
	voa_head_meta_author();
	
	// print the canonical links
	voa_head_meta_canonical_links();
	
	// print the description meta tags
	voa_head_meta_description();
	
	// find and print share image meta tags
	voa_head_meta_share_images();
	
}



function voa_head_meta_author() {
	global $post;
	
	$o = get_option( 'voa_opt_comm' );
	
	if ( is_single() && isset( $o['show_author'] ) ) {
		
		$voa_byline = trim( get_post_meta( $post->ID, '_voa_byline', true ) );
			
		if ( $voa_byline != '' ) {
			$m_author = $voa_byline;
		} else {
			$m_author = get_the_author_meta( 'user_nicename', $post->post_author );
		}
		?>
		<meta name="author" content="<?php echo esc_attr( $m_author ); ?>" />
		<?php
	}
}



function voa_head_meta_canonical_links() {
	global $wp;
	
	if ( is_front_page() ) {
		$current_url = home_url( '/' );
	} else {
		$current_url = home_url( '/' ).$wp->request.'/';
	}
	?>
	<link rel="canonical"       href="<?php echo esc_attr( $current_url ); ?>" />
	<meta name="standout"    content="<?php echo esc_attr( $current_url ); ?>" />
	<meta property="og:url"  content="<?php echo esc_attr( $current_url ); ?>" />
	<meta name="twitter:url" content="<?php echo esc_attr( $current_url ); ?>" />
	<?php
}



function voa_head_meta_description() {
	global $post;
	
	if ( is_single() ) {
		
		$subhed = trim( get_post_meta( $post->ID, '_voa_post_subhed', true ));
		
		if ( $subhed != '' ) {
			$m_description = $subhed;
		} else {
			$m_description = get_the_excerpt();
		}
		
	} else {
		
		$o = get_option( 'voa_opt_seo' );
		$o['voa_seo_description'] = trim( $o['voa_seo_description'] );
		
		if ( isset( $o['voa_seo_description'] ) && $o['voa_seo_description'] != '' ) {
			$m_description = $o['voa_seo_description'];
			
		} else {
			$m_description = get_bloginfo( 'description' );
		}
	}
	?>
	<meta name="description" itemprop="description" content="<?php echo esc_attr( text_shortenerer( $m_description, 157, '...' ) ); ?>" />
	<meta property="og:description"  content="<?php echo esc_attr( text_shortenerer( $m_description, 197, '...' ) ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( text_shortenerer( $m_description, 197, '...' ) ); ?>" />
	<?php
}



function voa_head_meta_share_images() {
	global $post;
	
	if ( is_single() && has_post_thumbnail( $post->ID ) ) {
		// get share image for post
		$m_share_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'fb-share-image' );
		$twitter_card_type = 'summary_large_image';
	} else {
		// use default voanews.com share image
		$m_share_img = array( get_template_directory_uri().'/img/top_logo_news.png', 600, 600 );
		$twitter_card_type = 'summary';
	}
	?>
	<meta name="twitter:card"          value="<?php echo $twitter_card_type; ?>" />
	
	<link rel="image_src"               href="<?php echo esc_attr( $m_share_img[0] ); ?>" />
	<meta name="twitter:image"       content="<?php echo esc_attr( $m_share_img[0] ); ?>" />
	<meta property="og:image"        content="<?php echo esc_attr( $m_share_img[0] ); ?>" />
	<meta property="og:image:width"  content="<?php echo esc_attr( $m_share_img[1] ); ?>" />
	<meta property="og:image:height" content="<?php echo esc_attr( $m_share_img[2] ); ?>" />
	<?php
}
