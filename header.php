<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php the_title(); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/style.css?rand=<?php echo time() ?>" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" />
    <?php wp_head(); ?>
</head>
<body>

    <header>
        <inner>
            <logo></logo>
            <menu>
                <a href="about">About</a>
                <a href="about">Archives</a>
                <a href="about">Search</a>
            </menu>
        </inner>
    </header>
