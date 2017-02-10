<?php

// holder for VOA meta tag values
$vmeta = array();

$vmeta['title_suffix'] = "Voice of America";
$vmeta['template_directory_uri'] = get_template_directory_uri();
$vmeta['image'] = 'http://www.voanews.com/Content/responsive/VOA/img/top_logo_news.png';
$vmeta['date_modified'] = ''; // $post->post_modified

if ( is_front_page() ) {

	$vmeta['canonical_url'] = home_url( '/' );
	$vmeta['title'] = "VOA Editor's Picks";
	$vmeta['description'] = get_bloginfo( 'description' );
	$vmeta['date_published'] = '2017-01-17';

} else {

	$vmeta['canonical_url'] = get_the_permalink();
	$vmeta['title'] = get_the_title();
	$vmeta['description'] = '';
	$vmeta['date_published'] = ''; // $post->post_date

	if ( is_single() && has_post_thumbnail( $post->ID ) ) {
		// get the featured image
		$vmeta['image'] = esc_attr( voa_top_content_get_image_url( get_post_thumbnail_id( $post->ID ), "full-width" ));
	}
}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />

	<link rel="canonical"            href="<?php echo $vmeta['canonical_url']; ?>" />
	<meta name="standout"            content="<?php echo $vmeta['canonical_url']; ?>" />
	<link rel="image_src"            href="<?php echo $vmeta['image']; ?>" />

	<meta name="title"               content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" />
	<meta name="description"         content="<?php echo $vmeta['description']; ?>" itemprop="description" />
	<meta name="keywords"            content="<?php echo $vmeta['keywords']; ?>" />
	<meta name="news_keywords"       content="<?php echo $vmeta['keywords']; ?>" />

	<?php if ( is_single() ) { ?>
	<meta name="author"              content="<?php echo get_post_meta( $post->ID, '_voa_byline', true ); ?>" />
	<?php } ?>

	<meta property="og:site_name"    content="VOA" />
	<meta property="og:type"         content="article" />
	<meta property="og:title"        content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" />
	<meta property="og:url"          content="<?php echo $vmeta['canonical_url']; ?>" />
	<meta property="og:image"        content="<?php echo $vmeta['image']; ?>" />
	<meta property="og:description"  content="<?php echo $vmeta['description']; ?>" />

	<meta name="twitter:card"        value="summary_large_image" />
	<meta name="twitter:site"        value="@VOANews" />
	<meta name="twitter:creator"     content="@VOANews" />
	<meta name="twitter:title"       content="<?php echo $vmeta['title']; ?> | <?php echo $vmeta['title_suffix']; ?>" />
	<meta name="twitter:description" content="<?php echo $vmeta['description']; ?>" />
	<meta name="twitter:url"         content="<?php echo $vmeta['canonical_url']; ?>" />
	<meta name="twitter:image"       content="<?php echo $vmeta['image']; ?>" />

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
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo $vmeta['template_directory_uri']; ?>/css/font-awesome.min.css" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header>
		<inner>
			<logo><a href="http://www.voanews.com/"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" border="0" alt="VOA" /></a></logo>
			<menu>
				<?php /* <a href="<?php echo home_url( '' ); ?>/about/">About</a> */ ?>
				<a href="<?php echo home_url( '' ); ?>/archives/">Archives</a>
				<?php /* <a href="#">Search</a> */ ?>
			</menu>
		</inner>
	</header>

	<rows>
		<row class="rows_1" style="margin-top: 0; padding-bottom: 0;">
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo("name"); ?></a></h1>
		</row>
	</rows>
