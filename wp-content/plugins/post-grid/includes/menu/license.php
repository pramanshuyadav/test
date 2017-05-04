<?php	


/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



if(empty($_POST['post_grid_hidden']))
	{



	}
else
	{
		if($_POST['post_grid_hidden'] == 'Y') {

	

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', 'post_grid' ); ?></strong></p></div>
	
			<?php
			} 
	}
	
	
	
?>





<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__(post_grid_plugin_name.' License', 'post_grid')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="post_grid_hidden" value="Y">
        <?php settings_fields( 'post_grid_plugin_options' );
				do_settings_sections( 'post_grid_plugin_options' );
			
		?>

    <div class="para-settings post_grid-settings">
    
        <ul class="tab-nav"> 
            <li nav="1" class="nav1 active">Activation</li>       
  
        </ul> <!-- tab-nav end --> 
		<ul class="box">
       		<li style="display: block;" class="box1 tab-box active">
            
				<div class="option-box">
                    <p class="option-title">Activate license</p>

                	<?php

    /*** License activate button was clicked ***/
    if (isset($_REQUEST['activate_license'])) {
        $license_key = $_REQUEST['post_grid_license_key'];

        // API query parameters
        $api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => POST_GRID_SPECIAL_SECRET_KEY,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(POST_GRID_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $response = wp_remote_get(add_query_arg($api_params, POST_GRID_LICENSE_SERVER_URL), array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){//Success was returned for the license activation
            
            //Uncomment the followng line to see the message that returned from the license server
            echo '<br />The following message was returned from the server: <strong class="option-info">'.$license_data->message.'</strong>';
            
            //Save the license key in the options table
            update_option('post_grid_license_key', $license_key); 
        }
        else{
            //Show error to the user. Probably entered incorrect license key.
            
            //Uncomment the followng line to see the message that returned from the license server
            echo '<br />The following message was returned from the server: <strong class="option-info">'.$license_data->message.'</strong>';
        }

    }
    /*** End of license activation ***/
    
    /*** License activate button was clicked ***/
    if (isset($_REQUEST['deactivate_license'])) {
        $license_key = $_REQUEST['post_grid_license_key'];

        // API query parameters
        $api_params = array(
            'slm_action' => 'slm_deactivate',
            'secret_key' => POST_GRID_SPECIAL_SECRET_KEY,
            'license_key' => $license_key,
            'registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' => urlencode(POST_GRID_ITEM_REFERENCE),
        );

        // Send query to the license manager server
        $response = wp_remote_get(add_query_arg($api_params, POST_GRID_LICENSE_SERVER_URL), array('timeout' => 20, 'sslverify' => false));

        // Check for error in the response
        if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
        }

        //var_dump($response);//uncomment it if you want to look at the full response
        
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        // TODO - Do something with it.
        //var_dump($license_data);//uncomment it to look at the data
        
        if($license_data->result == 'success'){//Success was returned for the license activation
            
            //Uncomment the followng line to see the message that returned from the license server
            echo '<br />The following message was returned from the server: <strong class="option-info">'.$license_data->message.'</strong>';
            
            //Remove the licensse key from the options table. It will need to be activated again.
            update_option('post_grid_license_key', '');
        }
        else{
            //Show error to the user. Probably entered incorrect license key.
            
            //Uncomment the followng line to see the message that returned from the license server
            echo '<br />The following message was returned from the server: <strong class="option-info">'.$license_data->message.'</strong>';
        }
        
    }
    /*** End of sample license deactivation ***/
    
    ?>
    
    
                    
	<?php
    
        $post_grid_license_key = get_option('post_grid_license_key');
        
        if(empty($post_grid_license_key))
            {
                $post_grid_license_status = '<span style="color:#f00;">Not Active</span>';
            }
        else
            {
                $post_grid_license_status = 'Active';
            }
        
    ?>
    
    
    <p class="option-info">Status: <b><?php echo $post_grid_license_status; ?></b></p>
    
    
    
    
    <p>Enter the license key for this product to activate it. You were given a license key when you purchased this item. please visit <a href="<?php echo POST_GRID_LICENSE_KEYS_PAGE; ?>"><?php echo POST_GRID_LICENSE_KEYS_PAGE; ?></a> after logged-in you will see license key for your purchased product. </p>
    
    <p>If you have any problem regarding license activatin please contact for support <a href="<?php echo post_grid_conatct_url; ?>"><?php echo post_grid_conatct_url; ?></a></p>    
    

        <table class="form-table">
            <tr>
                <th style="width:100px;"><label for="post_grid_license_key">License Key</label></th>
                <td >
                <input class="regular-text" type="text" id="post_grid_license_key" name="post_grid_license_key"  value="<?php echo get_option('post_grid_license_key'); ?>" >

                
                </td>
            </tr>
        </table>



                </div>
            
            </li>
           
        </ul>
    
    
		

        
    </div>






        <p class="submit">
            <input type="submit" name="activate_license" value="Activate" class="button-primary" />
            <input type="submit" name="deactivate_license" value="Deactivate" class="button" />
        </p>
		</form>


</div>
