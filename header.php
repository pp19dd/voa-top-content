<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />

	<meta property="og:site_name"    content="<?php echo esc_attr_x( 'VOA', 'Facebook og:sitename', 'voa-top-content' ); ?>" />
	<meta property="og:type"         content="article" />
	
	<meta name="twitter:site"        value="@<?php echo esc_attr_x( 'VOANews', 'Service Twitter Username', 'voa-top-content' ); ?>" />
	<meta name="twitter:creator"     content="@<?php echo esc_attr_x( 'VOANews', 'Service Twitter Username', 'voa-top-content' ); ?>" />
	
	<link type="image/x-icon" rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/ico-144x144.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/ico-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/ico-72x72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/ico-57x57.png" />
	<meta name="msapplication-TileColor" content="#ffffff" />
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/ico-144x144.png" />

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css?rand=<?php echo time(); ?>" />
	<?php locate_template( 'languages/'.get_bloginfo('language').'.php', true, true ); ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" />
	
	<!-- documentation at https://polyfill.io/v2/docs/ -->
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php get_template_part( "partials/metrics" ); ?>

	<header class="voa-masthead">
		<div class="voa-masthead-inner">
			<a class="voa-logo" title="<?php esc_attr_e( 'Return to VOA', 'voa-top-content' ); ?>" href="<?php echo esc_attr_x( 'https://www.voanews.com/', 'VOA Homepage URL', 'voa-top-content' ); ?><?php echo VOA_METRICS_TRACKING_PARAMETER; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" border="0" alt="VOA" /></a>
			
			<nav class="site-nav">
				
				<?php if ( VOA_EDITORS_PICKS ) { ?>
				<div class="header-nav-newsletter-link">
					<a href="https://www.voanews.com/subscribe.html<?php echo VOA_METRICS_TRACKING_PARAMETER; ?>">Subscribe<span class="big-screen"> to the Newsletter</span></a>
				</div>
				<?php } // if VOA_EDITORS_PICKS ?>
				
				<div class="search-nav">
					<a class="nav-trigger search-nav-trigger" href="#" title="<?php echo esc_attr_x( 'Search', 'button', 'voa-top-content' ); ?>"><i class="fa fa-lg fa-search" aria-hidden="true"></i><span class="sr-only"><?php _ex( 'Search', 'button', 'voa-top-content' ); ?></span></a>
					<div class="search-form-container"><?php get_search_form('true'); ?></div>
				</div>
				
				<div class="blog-nav">
					<a class="nav-trigger blog-nav-trigger" href="#" title="<?php echo esc_attr_x( 'Menu', 'button', 'voa-top-content' ); ?>"><i class="fa fa-lg fa-bars" aria-hidden="true"></i><span class="sr-only"><?php _ex( 'Menu', 'button', 'voa-top-content' ); ?></span></a>
					<?php 
						wp_nav_menu( array( 
							'theme_location'  => 'blog_header_menu',
							'container'       => 'div',
							'container_class' => 'menu',
							'menu_class'      => '',
							'depth'           => 1
						));
					?>
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
