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
	
	// print the keyword meta tags
	voa_head_meta_keywords();

	// print publication and modification dates
	voa_head_meta_pub_dates();
	
	// find and print share image meta tags
	voa_head_meta_share_images();
	
	// set title for social (<title> set by WordPress)
	voa_head_meta_share_title();
	
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
	
	$current_url = trailingslashit( home_url( '/' ).$wp->request );
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
		} elseif ( has_excerpt() ) {
			$m_description = strip_shortcodes( wp_strip_all_tags( get_the_excerpt(), true ) );
		} else {
			$m_description = wp_trim_words( strip_shortcodes( wp_strip_all_tags( $post->post_content, true ) ), 20, '' );
		}
	}
	
	if ( !is_single() || $m_description == '' ) {
		
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
	<meta property="og:description"  content="<?php echo esc_attr( text_shortenerer( $m_description, 157, '...' ) ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( text_shortenerer( $m_description, 97, '...' ) ); ?>" />
	<?php
}



function voa_head_meta_keywords() {
	$o = get_option( 'voa_opt_seo' );
	
	$o['voa_seo_keywords'] = trim( $o['voa_seo_keywords'] );
		
	if ( isset( $o['voa_seo_keywords'] ) && $o['voa_seo_keywords'] != '' ) {
		$m_keywords = $o['voa_seo_keywords'];
	} else {
		$m_keywords = '';
	}
	?>
	<meta name="keywords" content="<?php echo $m_keywords; ?>" />
	<meta name="news_keywords" content="<?php echo $m_keywords; ?>" />
	<?php
}



function voa_head_meta_pub_dates() {
	global $post;

	if ( is_front_page() ) {
		$pub_date = voa_top_content_get_most_recently_published_day(); // Y-m-d format
		$mod_date = $pub_date;

	} elseif ( is_single() ) {
		$pub_date = $post->post_date;
		$mod_date = $post->post_modified;

	} else {
		$pub_date = $post->post_date;
		$mod_date = $post->post_modified;

	}
	?>
	<meta name="DISPLAYDATE"       content="<?php echo date( 'F j, Y', strtotime( $pub_date )); ?>" />
	<meta itemprop="dateModified"  content="<?php echo date( 'Y-m-d',  strtotime( $mod_date )); ?>" />
	<meta itemprop="datePublished" content="<?php echo date( 'Y-m-d',  strtotime( $pub_date )); ?>" />
	<?php
}



function voa_head_meta_share_images() {
	global $post;
	
	if ( is_single() && has_post_thumbnail( $post->ID ) ) {
		// get share image for post
		$m_share_img = voa_wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'fb-share-image' );
		$twitter_card_type = 'summary_large_image';
		$img_size_exists = $m_share_img[3];

	} else {
		// use default voanews.com share image
		$m_share_img = array( get_template_directory_uri().'/img/top_logo_news.png', 600, 600 );
		$twitter_card_type = 'summary';
		$img_size_exists = (bool) true;
	}
	?>
	<meta name="twitter:card"          value="<?php echo $twitter_card_type; ?>" />
	
	<link rel="image_src"               href="<?php echo esc_attr( $m_share_img[0] ); ?>" />
	<meta name="twitter:image"       content="<?php echo esc_attr( $m_share_img[0] ); ?>" />
	<meta property="og:image"        content="<?php echo esc_attr( $m_share_img[0] ); ?>" />

	<?php if ( $img_size_exists ) { ?>
	<meta property="og:image:width"  content="<?php echo esc_attr( $m_share_img[1] ); ?>" />
	<meta property="og:image:height" content="<?php echo esc_attr( $m_share_img[2] ); ?>" />
	<?php
	}
}

function voa_head_meta_share_title() {
	global $post;
	
	$share_title = esc_attr( wp_get_document_title() );
	
	?>
	<meta property="og:title" content="<?php echo $share_title; ?>" />
	<meta name="twitter:title" content="<?php echo $share_title; ?>" />
	<?php
}
