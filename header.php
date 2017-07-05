<?php

// metrics tracking parameter to add to URLs (also on single.php for redirects)
$voa_metrics_tracking_parameter = '?src=voa-editor-picks';

// simplify template directory calls
$top_content_template_directory_uri = get_template_directory_uri();

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />

	<meta property="og:site_name"    content="VOA" />
	<meta property="og:type"         content="article" />
	
	<meta name="twitter:site"        value="@VOANews" />
	<meta name="twitter:creator"     content="@VOANews" />
	
	<link type="image/x-icon" rel="icon" href="<?php echo $top_content_template_directory_uri; ?>/img/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo $top_content_template_directory_uri; ?>/img/favicon.ico" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $top_content_template_directory_uri; ?>/img/ico-144x144.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $top_content_template_directory_uri; ?>/img/ico-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $top_content_template_directory_uri; ?>/img/ico-72x72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo $top_content_template_directory_uri; ?>/img/ico-57x57.png" />
	<meta name="msapplication-TileColor" content="#ffffff" />
	<meta name="msapplication-TileImage" content="<?php echo $top_content_template_directory_uri; ?>/img/ico-144x144.png" />

	<link rel="stylesheet" type="text/css" href="<?php echo $top_content_template_directory_uri; ?>/style.css?rand=<?php echo time(); ?>" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700" />
	<link rel="stylesheet" href="<?php echo $top_content_template_directory_uri; ?>/css/font-awesome.min.css" />
	
	<!-- documentation at https://polyfill.io/v2/docs/ -->
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php get_template_part( "partials/metrics" ); ?>

	<header class="voa-masthead">
		<div class="voa-masthead-inner">
			<a class="voa-logo" href="https://www.voanews.com/<?php echo $voa_metrics_tracking_parameter; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" border="0" alt="VOA" /></a>
			
			<nav class="site-nav">
				
				<div class="header-nav-newsletter-link">
					<a href="https://www.voanews.com/subscribe.html<?php echo $voa_metrics_tracking_parameter; ?>">Subscribe<span class="big-screen"> to the Newsletter</span></a>
				</div>
				
				<div class="search-nav">
					<a class="nav-trigger search-nav-trigger" href="#"><i class="fa fa-lg fa-search" aria-hidden="true"></i><span class="sr-only">Search</span></a>
					<div class="search-form-container"><?php get_search_form('true'); ?></div>
				</div>
				
				<div class="blog-nav">
					<a class="nav-trigger blog-nav-trigger" href="#"><i class="fa fa-lg fa-bars" aria-hidden="true"></i><span class="sr-only">Menu</span></a>
					<?php wp_nav_menu( array( 'theme_location' => 'Header Navigation Menu' ) ); ?>
				</div>
				
				<?php /* <div class="lang-nav">
					<a class="nav-trigger lang-nav-trigger" href="#"><i class="fa fa-lg fa-language" aria-hidden="true"></i><span class="sr-only">Languages</span></a>
				</div> */ ?>
			</nav>
		</div>
	</header>
	
	<rows>
		<row class="rows_1" style="margin-top: 0; padding-bottom: 0;">
			
			<?php $header_tag = ( is_front_page() ? 'h1' : 'div' ); ?>
			
			<?php if( isset( $_GET['preview_layout']) ) { ?>
				
				<<?php echo $header_tag; ?> class="site-title" style="color:crimson">(Layout Preview)</<?php echo $header_tag; ?>>
			
			<?php } elseif ( get_header_image() != '' ) { ?>
				
				<<?php echo $header_tag; ?> class="site-title"><a href="<?php echo home_url( '/' ); ?>"><img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo("name"); ?>" /></a></<?php echo $header_tag; ?>>
				
			<?php } else { ?>
				
				<<?php echo $header_tag; ?> class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo("name"); ?></a></<?php echo $header_tag; ?>>
				
			<?php } ?>
		</row>
	</rows>
