<?php
$byline = get_post_meta($post["id"], "_voa_byline", true);
if( strlen($byline) > 0 ) {
?>
<byline>By <?php echo $byline ?></byline>
<?php } ?>
