<?php
require_once( dirname(__FILE__) . '/wp-load.php' );
global $wpdb;

$postarray=get_post($_REQUEST['id']);
$url = wp_get_attachment_url( get_post_thumbnail_id($_REQUEST['id']) );
//echo "<pre>";
//print_r($postarray);?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo site_url();?>/wp-content/themes/meris/style.css" />
<body bgcolor="#ffffff"><?php if($url!=""){?>


<img src="<?php echo $url;?>" >
<?php }
else
{
	?><img src="<?php echo site_url();?>/wp-content/themes/meris/images/no-thumbnail.png" ><?php 
	}?>
<div style="padding:10px;">


<h1 style="margin:12px 0 12px;"><?php echo $postarray->post_title;?></h1>
<div>
<?php echo $postarray->post_content;?>
</div>


</div>

</body>
