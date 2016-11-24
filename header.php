<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />

    <title><?php the_title(); ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/style.css?rand=<?php echo time() ?>" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/font-awesome.min.css" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <header>
        <inner>
            <logo><a href="http://www.voanews.com/"><img src="<?php echo get_template_directory_uri() ?>/img/voa-logo_142x60_2x_f8f8f8.png" width="71" height="30" alt="VOA" /></a></logo>
            <menu>
                <a href="#">About</a>
                <a href="#">Archives</a>
                <a href="#">Search</a>
            </menu>
        </inner>
    </header>

    <rows>
        <row class="rows_1">
            <h1><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo("name"); ?></a></h1>
        </row>
    </rows>
