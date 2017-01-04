<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />

	<?php /* <title><?php //wp_title( '-', true, 'right' ); ?></title> */ ?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css?rand=<?php echo time(); ?>" />
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" />
	
	<link type="image/x-icon" rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/ico-144x144.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/ico-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/ico-72x72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/ico-57x57.png" />
	<meta name="msapplication-TileColor" content="#ffffff" />
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/ico-144x144.png" />
	
	<meta property="og:title"       content="<?php the_title(); ?>" />
	<meta property="og:type"        content="article" />
	<meta property="og:url"         content="<?php the_permalink(); ?>" />
	<meta property="og:site_name"   content="VOA News" />
	
	<?php if( !has_post_thumbnail( $post->ID )) { /* the post does not have featured image, use a default image */ ?>
	<meta property="og:image" content="http://example.com/image.jpg"/ >
	<?php } else { ?>
	<?php //$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' ); ?>
	<meta property="og:image" content="<?php echo esc_attr( voa_top_content_get_image_url( get_post_thumbnail_id( $post->ID ), "full-width" )); ?>" />
	<?php } ?>
	
	<?php if ( is_single() ) { ?>
	<meta property="og:description" content="" />
	<?php } ?>
	
	
	
	<?php /*
	<link rel="canonical" href="{$canonical_url}" />
	
	<link rel="image_src" href="{$thumbnail}" />

	<meta name="title" content="{$title} | {$title_suffix}" />
	<meta itemprop="description" name="description" content="{$description}" />
	<meta name="keywords" content="{$keywords}" />
	<meta name="news_keywords" content="{$keywords}" />

	<meta name="standout" content="{$canonical_url}" />
	<meta name="author" content="{$author}" />
	<meta property="og:type" content="article" />
	<meta property="og:site_name" content="{$site_name}" />
	<meta property="og:title" content="{$title} | {$title_suffix}" />
	<meta property="og:description" content="{$description}" />
	<meta property="og:url" content="{$canonical_url}" />
	<meta property="og:image" content="{$thumbnail}" />
	 
	<meta name="twitter:card" value="summary_large_image" />
	<meta name="twitter:site" value="{$twitter_user}" />
	<meta name="twitter:creator" content="{$twitter_user}" />
	<meta name="twitter:title" content="{$title} | {$title_suffix}" />
	<meta name="twitter:description" content="{$description}" />
	<meta name="twitter:url" content="{$canonical_url}" />
	<meta name="twitter:image" content="{$thumbnail}" />
	 
	<meta name="DISPLAYDATE" content="{$slides[0].display_date|strip_tags|trim}" />
	<meta itemprop="dateModified" content="{$slides[0].date_modified|strip_tags|trim}" />
	<meta itemprop="datePublished" content="{$slides[0].date_published|strip_tags|trim}" />
	*/ ?>
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<header>
		<inner>
			<logo><a href="http://www.voanews.com/"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" border="0" alt="VOA" /></a></logo>
			<menu>
				<a href="<?php echo home_url( '' ); ?>/about/">About</a>
				<a href="<?php echo home_url( '' ); ?>/archives/">Archives</a>
				<a href="#">Search</a>
			</menu>
		</inner>
	</header>

	<rows>
		<row class="rows_1">
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo("name"); ?></a></h1>
		</row>
	</rows>
