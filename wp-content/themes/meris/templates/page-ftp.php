<?php
/**
 * Template Name: FTP Page
 *
 */
get_header(); 
$pageColor = $cfs->get('page_main_color');
$colorStyle = $pageColor == ''?"":"style='color:$pageColor'";
if($pageColor != ''){
    $submenuColor = substr($pageColor,1);
    $submenuColorClass = "class-$submenuColor";
}
?>
<style type="text/css">
body {
    background: #ffffff;
    color: #1f275c;
    margin: auto;
    padding: 5px;
    font-family: arial;
    font-size: 11px;
}

.userinput {
    padding-bottom: 15px;
}

    .userinput label {
        float: left;
        clear: left;
        padding: 2px 5px 0px 0px;
    }

    .userinput .red {
        color: red;
    }

    .userinput input {
        float: left;
        clear: left;
        margin: 2px 0 0 0;
    }

.success-box {
    display: none;
    border: #99cc66 2px solid;
    background-color: #ccffcc;
    padding: 10px;
    font-weight: bold;
    width: 420px;
}

.error-box {
    display: none;
    clear: both;
    font-weight: bold;
    border: #FF0000 2px solid;
    background-color: #ffb3b3;
    padding: 10px;
    margin: 5px 0;
    width: 420px;
}

.info-box {
    border: #eaeaea 2px solid;
    background-color: #f9f9f9;
    padding: 10px;
    font-weight: bold;
}

.clear {
    float: none;
    clear: both;
    height: 0;
}

.webkit #File1 {
    color: #1f275c;
}

.webkit #File2 {
    color: #1f275c;
}

.webkit #File3 {
    color: #1f275c;
}

.webkit #File4 {
    color: #1f275c;
}

.webkit #File5 {
    color: #1f275c;
}

.webkit #File6 {
    color: #1f275c;
}

.webkit #File7 {
    color: #1f275c;
}

.webkit #File8 {
    color: #1f275c;
}

.webkit #File9 {
    color: #1f275c;
}

.webkit #File10 {
    color: #1f275c;
}
</style>


<div class="inner-page">
<?php
wp_nav_menu ( array (
        'theme_location' => 'leftsidebar',
        'menu' => 'leftsidebar',
        'depth' => 0,
        'fallback_cb' => false,
        'container' => '',
        'container_class' => '' 
) );
?>
</div>
<div class="container-fluid">
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="blog-list">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        
                        <div class="col-md-12">
                        <div class="inner-page1">
                            <div class="row">
                        <?php get_template_part( "innerpage", "header" ); ?>
                         <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9 col-sm-9">
                                        <?php
                                            $currentPageId = $post->ID;
                                            $parents = get_post_ancestors ( $post->ID );
                                            $pageParentId = ($parents) ? $parents [count ( $parents ) - 1] : $post->ID;
                                            $parent = get_post ( $pageParentId );
                                            $mobileBackgroundImage = get_field('heading_background_image_for_small_devices',$pageParentId);
                                            $mobileBackgroundImageColor = get_field('heading_background_image_color_for_small_devices',$pageParentId);
                                            
                                            $args = array(
                                            'post_type' => 'page',
                                            'post_status' => 'publish',
                                            'post_parent' => $pageParentId, // any parent
                                            'numberposts' => -1,
                                            'orderby'=> 'menu_order',
                                            );
                                            
                                            $attachments = get_posts($args);
                                            
                                            if ($attachments) {
                                                foreach ($attachments as $post) {
                                                    $subpagesDropdown[$post->ID]['id'] = $post->ID;
                                                    $subpagesDropdown[$post->ID]['link'] = get_permalink();
                                                    $subpagesDropdown[$post->ID]['title'] = get_the_title();
                                                }
                                            }
                                            ?>
                                        <div class="page-main-heading">
                                                <h1 <?php echo $colorStyle; ?>><?php echo $parent-> post_title; ?></h1>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-7">
                                            <?php if ( meris_options_array('logo')!="") { ?>
                                                <div class="logo-div innerpage-logo">
                                                <a href="<?php echo esc_url(home_url('/')); ?>"> <img
                                                    src="<?php echo esc_url(meris_options_array('logo')); ?>"
                                                    class="site-logo" alt="<?php bloginfo('name'); ?>" />
                                                </a>
                                            </div>
                                             <?php } ?>
                                     </div>
                                    </div>
                                </div>
                            <div class="col-md-12 gallery-padding">
                                <div class="submenu-pages <?php echo $submenuColorClass; ?>" >
                                    <?php   wp_list_pages("title_li=&child_of=$pageParentId&sort_column=menu_order"); ?>
                                </div>
                                
                                <div class="mobile-banner-image mobile-gellary-title" style="background-image:url('<?php echo $mobileBackgroundImage; ?>');background-color:<?php echo $mobileBackgroundImageColor; ?>"><h1><?php echo $parent-> post_title; ?></h1></div>
                                <div class="submenu-pages-dropdown">
                                    <select id="changepage">
                                        <?php
                                            foreach($subpagesDropdown as $k=>$v){
                                                ?>
                                                    <option <?php echo $v['id']== $currentPageId? 'selected':''; ?> value="<?php echo $v['link'] ; ?>" ><?php echo $v['title']; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="main-content orange-color case-main container-a3 orange-color">                                
                                    <form id="sfUploadForm" enctype="multipart/form-data" method="post" action="">
    
                                        <input type="hidden" id="debug" name="debug" value="false" />
                                        <input type="hidden" id="base" name="base" value="https://adform.sharefile.com" />
                                        <input type="hidden" id="pid" name="pid" value="rf22936d7fae42bf8" />
                                        <input type="hidden" id="originalPID" name="originalPID" value="rf22936d7fae42bf8" />
                                        
                                        
                                        <input type="hidden" id="details" name="details" />
                                        <input type="hidden" id="type" name="type" value="Message" />
                                        <input type="hidden" id="redirectonsuccess" name="redirectonsuccess" value="" />
                                        <input type="hidden" id="redirectonerror" name="redirectonerror" value="" />
                                        <input type="hidden" id="batchID" />
                                        <input type="hidden" id="authid" />
                                        
                                        <noscript>
                                            <style type="text/css">
                                                #formLoadingContainer {
                                                    display: none;
                                                }
                                            </style>
                                            <div id="noJavaScript">
                                                You must have JavaScript enabled to use this remote upload form.
                                            </div>
                                        </noscript>
                                        <div id="formLoadingContainer">
                                             Please wait while the upload form loads...
                                        </div>
                                        <div id="formErrorContainer" style="display: none;">
                                            There was an error loading the upload form. Please contact support.
                                             We apologize for the inconvenience.
                                            <br />
                                            <br />
                                        </div>
                                        
                                        <div id="uploadFormContainer" style="display: none;">
                                            <input id='apicsrftoken' type='hidden' value='S9K3dGWWOX3GI/B0uHjAaQ=='>
                                            <div id="uploadProgressIndicator"></div>
                                            <div class="error-box" id="ErrorMsg">Error. We are sorry, but your file will not upload. Please contact our Customer Service Team for assistance.</div>
                                            <div class="success-box" id="SuccessMsg">Thank you! Your file has been successfully uploaded.</div>
                                            
                                            <div class="info-box" id="InfoMsg">Files cannot exceed 2 GB</div>
                                            
                                            <div class="userinput">
                                                <label>Email: <span class="red">*</span> </label><input name="txta06c8d8" type="text" id="txta06c8d8" maxlength="100" style="width:200px;" /><label>First Name: <span class="red">*</span> </label><input name="txtce1ea76" type="text" id="txtce1ea76" maxlength="100" style="width:200px;" /><label>Last Name: <span class="red">*</span> </label><input name="txtc4bf904" type="text" id="txtc4bf904" maxlength="100" style="width:200px;" /><label>Company: <span class="red">*</span> </label><input name="txtff05001" type="text" id="txtff05001" maxlength="100" style="width:200px;" /><label>Phone #: </label><input name="txt84c8742" type="text" id="txt84c8742" maxlength="100" style="width:200px;" />
                                                <div class="clear"></div>
                                            </div>
                                            <div id="pnlStandardUpload">
                                        
                                                <label>File 1:</label><input type="file" id="File1" name="File1" /><br />
                                                <label>File 2:</label><input type="file" id="File2" name="File2" /><br />
                                                <label>File 3:</label><input type="file" id="File3" name="File3" /><br />
                                                <label>File 4:</label><input type="file" id="File4" name="File4" /><br />
                                                <label>File 5:</label><input type="file" id="File5" name="File5" /><br />
                                                <br />
                                                <input type="submit" value="Upload Files" onclick="return remoteUpload.formatUpload();" />
                                                
                                            
                                    </div>
                                            
                                            
                                        </div>
                                        </form>  
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>