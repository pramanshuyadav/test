<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- TODO: Provide markup for your options page here. -->

	    <div class="wrap">  
        <div id="icon-tools" class="icon32"><br /></div>  
        <h2>Import</h2>  
        <?php  
            if (isset($_FILES['import'])) {  
                // Do something if a file was uploaded  
            }  
        ?>  
        <p>Click Browse button and choose a json file that you backup before.</p>  
        <p>Press Restore button, WordPress do the rest for you.</p>  
        <form method='post' enctype='multipart/form-data'>  
            <p class="submit">  
                <?php wp_nonce_field('ie-import'); ?>  
                <input type='file' name='import' />  
                <input type='submit' name='submit' value='Restore'/>  
            </p>  
        </form>  
    </div> 
</div>
