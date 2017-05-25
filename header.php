<?php

// metrics tracking parameter to add to URLs (also on single.php for redirects)
$voa_metrics_tracking_parameter = '?src=voa-editor-picks';

// holder for VOA meta tag values
$vmeta = array();

$vmeta['title_suffix'] = "Voice of America";
$vmeta['template_directory_uri'] = get_template_directory_uri();
$vmeta['date_modified'] = ''; // $post->post_modified

if ( is_front_page() ) {

	$vmeta['title'] = get_bloginfo( 'name' );
	$vmeta['date_published'] = '2017-01-17';

} else {

	$vmeta['title'] = get_the_title();
	$vmeta['date_published'] = ''; // $post->post_date

}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />
	
	<!-- <meta name="title"               content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" /> -->
	<meta name="keywords"            content="<?php echo $vmeta['keywords']; ?>" />
	<meta name="news_keywords"       content="<?php echo $vmeta['keywords']; ?>" />

	<meta property="og:site_name"    content="VOA" />
	<meta property="og:type"         content="article" />
	<meta property="og:title"        content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" />
	
	<meta name="twitter:site"        value="@VOANews" />
	<meta name="twitter:creator"     content="@VOANews" />
	<meta name="twitter:title"       content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" />
	

<?php
// Y-m-d format
$most_recent = voa_top_content_get_most_recently_published_day();
?>

	<meta name="DISPLAYDATE"         content="<?php echo date( 'F j, Y', strtotime($most_recent) ); ?>" />
	<meta itemprop="dateModified"    content="<?php echo $most_recent; ?>" />
	<meta itemprop="datePublished"   content="<?php echo $most_recent; ?>" />

	<link type="image/x-icon" rel="icon" href="<?php echo $vmeta['template_directory_uri']; ?>/img/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo $vmeta['template_directory_uri']; ?>/img/favicon.ico" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $vmeta['template_directory_uri']; ?>/img/ico-144x144.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $vmeta['template_directory_uri']; ?>/img/ico-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $vmeta['template_directory_uri']; ?>/img/ico-72x72.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo $vmeta['template_directory_uri']; ?>/img/ico-57x57.png" />
	<meta name="msapplication-TileColor" content="#ffffff" />
	<meta name="msapplication-TileImage" content="<?php echo $vmeta['template_directory_uri']; ?>/img/ico-144x144.png" />

	<link rel="stylesheet" type="text/css" href="<?php echo $vmeta['template_directory_uri']; ?>/style.css?rand=<?php echo time(); ?>" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:300,400,700" />
	<link rel="stylesheet" href="<?php echo $vmeta['template_directory_uri']; ?>/css/font-awesome.min.css" />
	
	<?php // Polyfill.io reads the User-Agent header of each request and returns polyfills that are suitable for the requesting browser. Documentation: https://polyfill.io/v2/docs/ ?>
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php get_template_part( "partials/metrics" ); ?>

	<header class="voa-masthead">
		<div class="voa-masthead-inner">
			<a class="voa-logo" href="http://www.voanews.com/<?php echo $voa_metrics_tracking_parameter; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" border="0" alt="VOA" /></a>
			
			<nav class="site-nav">
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
<?php if( isset( $_GET['preview_layout']) ) { ?>
			
			<h1 class="site-title" style="color:crimson">(Layout Preview)</h1>
			
<?php } elseif ( get_header_image() != '' ) { ?>
			
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo("name"); ?>" /></a></h1>
			
<?php } else { ?>
			
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo("name"); ?></a></h1>
			
<?php } ?>
		</row>
	</rows>
