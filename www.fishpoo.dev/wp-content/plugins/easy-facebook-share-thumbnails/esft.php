<?php
/*
Plugin Name: Easy Facebook Share Thumbnail
Plugin URI: http://easyfacebooksharethumbnail.com/
Description: The post's featured image is used as the thumbnail when the page is being shared on facebook. A default image can also be specified.
Version: 1.9.6
Author: Hebeisen Consulting - R Bueno
Author URI: http://www.hebeisenconsulting.com
License: A "Slug" license name e.g. GPL2

   Copyright 2011 Hebeisen Consulting

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


Wordpress admin settings and core installations
*/

add_action('admin_menu', 'fbthumbnails_menu');
add_action('wp_head', 'fbthumbnails_head');
add_option('fbthumbnails_head_section', '');
add_option('fbthumbnails_link_rel', '');
define('easy_facebook_share_thumbnails_path', ABSPATH . 'wp-content/plugins/easy-facebook-share-thumbnails');
define('easy_facebook_share_thumbnails_url', get_bloginfo('siteurl') . '/wp-content/plugins/easy-facebook-share-thumbnails/thumbnails/');
define('easy_facebook_share_plugin_url', get_bloginfo('siteurl') . '/wp-content/plugins/easy-facebook-share-thumbnails/');
require easy_facebook_share_thumbnails_path . '/esft-functions.php';

//Check if featured immage is supported on current theme
//If not, set it on      
if(!function_exists('the_post_thumbnail()')){
	add_theme_support( 'post-thumbnails' ); 
	//add_image_size( 'esft_standard_size', 200, 200, true );
	set_post_thumbnail_size( 200, 200, true );
}

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'esft_standard_size', 200, 200, true );
}

function esft_message_admin( $message, $errormsg = false )
{
	if ($errormsg)
		echo '<div id="message" class="error">';
	
	else
		echo '<div id="message" class="updated fade">';
	
	echo "<p><strong>$message</strong></p></div>";
}   

//plugin installation
//create ew table upon activating plugin
function fbthumbnails_install()
{
    global $wpdb;
    $table = $wpdb->prefix . "fbthumbnails";
	if($wpdb->get_var("show tables like '$table'") != $table) {
	    $sql = "CREATE TABLE " . $table . " (
					  id int(11) NOT NULL AUTO_INCREMENT,
					  thumbnail varchar(150) NOT NULL,
					  path text NOT NULL,
					  url text NOT NULL,
					  active INT(1) NOT NULL,
					  image_type varchar(10) NOT NULL,
					  PRIMARY KEY (id)
					)";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	}
}

function fbthumbnails_uninstall(){
	global $wpdb;
        $table = $wpdb->prefix . "fbthumbnails";
        
        $wpdb->query("DROP TABLE IF EXISTS $table");
}

register_activation_hook(__FILE__,'fbthumbnails_install');
register_deactivation_hook(__FILE__,'fbthumbnails_uninstall');

//plugin <head></head> overriding
function fbthumbnails_head()
{
	include easy_facebook_share_thumbnails_path . '/esft-head.php';
}

//Wordpress admin menu
function fbthumbnails_menu()
{
	add_options_page('Easy Facebook Share Thumbnails', 'Easy Facebook Share Thumbnails', 'manage_options', 'facebook-thumbnail-slug', 'facebook_thumbnail_option');
}

function facebook_thumbnail_option()
{
	global $wpdb;	
	$table = $wpdb->prefix . "fbthumbnails";
	switch( $_GET['a'] )
		{
			case'delete-thumbnail':
				//delete thumbnail
				//perform deletion
				$wpdb->query("DELETE FROM $table WHERE id = '".$_GET['ID']."'"); 
				echo '<div id="message" class="updated fade"><p>Thumbnail deleted.</p></div>';				
			break;
			case'remove-thumbnail':
				//delete thumbnail
				//perform deletion
				$wpdb->query("UPDATE $table set active = '0' WHERE id = '".$_GET['ID']."'"); 
				echo '<div id="message" class="updated fade"><p>Thumbnail updated.</p></div>';				
			break;
			case'activate-thumbnail':
				//activate thumbnail
				//select current activate thumbnail		
				$active = $wpdb->get_row("SELECT * FROM $table WHERE active = '1'", ARRAY_A);		
				
				//and turn it off
				$wpdb->query( "UPDATE $table SET active = '0' WHERE id = '" . $active['id'] . "'" );
				
				//activate currently selected thumbnail from $_GET['id']
				$wpdb->query( "UPDATE $table SET active = '1' WHERE id = '" . $_GET['ID'] . "'" );
				echo '<div id="message" class="updated fade"><p>Thumbnail Activated.</p></div>';
			break;
			case'change-mask':
				update_option( 'easy_share_mask_type', $_POST['mask_type'] );
				
				//check if premium
				//and curl back to API
				if( $_POST['mask_type'] != "none" && $_POST['mask_type'] != "grunge" && $_POST['mask_type'] != "masked" && $_POST['mask_type'] != "punch" && $_POST['mask_type'] != "rounded" ):
					$overlay= esft_overlay( 
					 	array( 'method' => urlencode( 'overlay' ), 
					 	'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
					 	'image_url' => urlencode( $_POST['thumbnail_url'] ), 
					 	'thumbnail' => $_POST['thumbnail'], 
					 	'image_type' => $_POST['image_type'], 
					 	'mask_type' => $_POST['mask_type'] ) );					
				endif;
				
			break;
			case'other-settings':
				//other settings
				update_option( 'esft_site_name', $_POST['site_name'] );
				update_option( 'esft_app_id', $_POST['app_id'] );
				update_option( 'esft_published_time', $_POST['published_time'] );
				update_option( 'esft_modified_time', $_POST['modified_time'] );
			break;
			case'add-new-thumbnail':
				
				list($width, $height, $type, $attr) = getimagesize( $_FILES['image']['tmp_name'] );
						
		
					//image directories
					$upload_dir = wp_upload_dir();
					//add new thumbnail			
					//image name generator
					//use md5 and rand() to generate new image name
					$image_name = md5(rand());
					
					//determine active value
					if($_POST['active'] == ""){
					
					 //not use as default
					 // leave setting as it is
					 $active = "0";
					
					 //determine image type
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF )
					 {
					  	$target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".gif"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".gif";
					 // endif;
					 }
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG )
					 {
					  	$target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".jpg"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".jpg";
					 // endif;
					 }
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG )
					 {
					  	$target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".png"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".png";
					 // endif;
					 }		  
					  
					  //check file upload if image 
					  if((exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF ) || (exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG ) || (exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG ))
					  {
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF )
					  			$image_type = "gif";
					  	 
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG )
					  			$image_type = "jpg";
					  			
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG )
					  			$image_type = "png";
					  			
					  			
					  	 //validate image
					  	 //perform operation
						 if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {	
						 
						 //echo easy_facebook_share_thumbnails_path . '/resize_class.php';
						 include easy_facebook_share_thumbnails_path . '/resize_class.php';
						
						 //resize to 200 x 200 pixels
						 //Image resize
						 $resize = new resize( $target_path );							 						 
						 $resize -> resizeImage( 200, 200, 'crop' );						 						 
						 $resize -> saveImage( $target_path , 100);
						 
						 $dir_list = scandir( easy_facebook_share_thumbnails_path . "/masks/" );
					
						 for( $i=2; $i<count( $dir_list ); $i++ ){
							$fileinfo = pathinfo( $dir_list[$i] );
							easy_facebook_overlay( $target_path, $image_name, exif_imagetype( $target_path ), easy_facebook_share_thumbnails_path . "/masks/" . $dir_list[$i], easy_facebook_share_thumbnails_path . '/thumbnails/', $fileinfo['filename'] );
						 }						 
						
						 //insert to database
						 $wpdb->insert( $table, array(
										'thumbnail' => $image_name,
										'path' => $target_path,
										'url' => $url,
										'active' => $active,
										'image_type' => $image_type )
										);
						 //declare success message
						 echo '<div id="message" class="updated fade"><p>Success!.</p></div>';
						 
						 }else{
						 
						 //declare error message
						 echo '<div id="message" class="updated fade"><p>There was an error uploading the file, please try again! Please be sure that the thumbnails folder is writable.</p></div>';
						 
						 }
					  }
					  else
					  {
					  	 
					  	//unrecognised, declare error and stop operation
					   	echo '<div id="message" class="updated fade"><p>File type is not supported. Make sure it is either .PNG, .GIF, .JPEG only.</p></div>';
					  }
					//}
					 
					}else{
					
					//use as default
					//first, deactivate currently used thumbnail
					// then set the new thumbnail as default
					
					//select current activate thumbnail		
					$fbthm = $wpdb->get_row("SELECT * FROM $table WHERE active = '1'", ARRAY_A);
					
					//and turn it off
					$wpdb->query( "UPDATE $table SET active = '0' WHERE id = '" . $fbthm['id'] . "'" );
					
					$active = "1";
					 					 
					//determine image type
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF )
					 {
					  $target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".gif"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".gif";
					 // endif;
					 }
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG )
					 {
					    	$target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".jpg"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".jpg";
					 // endif;
					 }
					 if(exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG )
					 {
					   	$target_path = easy_facebook_share_thumbnails_path . "/thumbnails/" . $image_name . ".png"; 
					  	$url = easy_facebook_share_thumbnails_url . $image_name . ".png";
					 // endif;
					 }	 
					
					 //check file upload if image 
					  if((exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF ) || (exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG ) || (exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG ))
					  {
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_GIF )
					  			$image_type = "gif";
					  	 
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_JPEG )
					  			$image_type = "jpg";
					  			
					  	 if( exif_imagetype($_FILES['image']['tmp_name']) == IMAGETYPE_PNG )
					  			$image_type = "png";
					  			
					  	//validate image
					  	 //perform operation
						 if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {	
						 
						 //echo easy_facebook_share_thumbnails_path . '/resize_class.php';
						 include easy_facebook_share_thumbnails_path . '/resize_class.php';
						 //echo $target_path;
						 //resize to 200 x 200 pixels
						 //Image resize
						 $resize = new resize( $target_path );						 						 
						 $resize -> resizeImage( 200, 200, 'crop' );						 						 
						 $resize -> saveImage( $target_path , 100);
						 
						 $dir_list = scandir( easy_facebook_share_thumbnails_path . "/masks/" );
					
						 for( $i=2; $i<count( $dir_list ); $i++ ){
							$fileinfo = pathinfo( $dir_list[$i] );
							easy_facebook_overlay( $target_path, $image_name, exif_imagetype( $target_path ), easy_facebook_share_thumbnails_path . "/masks/" . $dir_list[$i], easy_facebook_share_thumbnails_path . '/thumbnails/', $fileinfo['filename'] );
						 }
						
						 //insert database
						 $wpdb->insert( $table, array(
										'thumbnail' => $image_name,
										'path' => $target_path,
										'url' => $url,
										'active' => $active,
										'image_type' => $image_type )
										);
						 //declare success message
						 echo '<div id="message" class="updated fade"><p>Success!.</p></div>';
						 
						 }else{
						 
						 //declare error message
						 echo '<div id="message" class="updated fade"><p>There was an error uploading the file, please try again! Please be sure that the thumbnails folder is writable.</p></div>';
						 
						 }
					  }
					  else
					  {
					  	 
					  	//unrecognised, declare error and stop operation
					   	echo '<div id="message" class="updated fade"><p>File type is not supported. Make sure it is either .PNG, .GIF, .JPEG only.</p></div>';
					  }
					//}
					
					}
				//endif;
			update_option( 'easy_share_mask_type', "rounded" );
			break;
		}
	switch($_GET['page']) {		
		case 'facebook-thumbnail-slug':		
			include easy_facebook_share_thumbnails_path . '/esft-admin.php';
		break;
	}
}

/* Define the custom box */
add_action( 'add_meta_boxes', 'esft_add_custom_box' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'myplugin_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'esft_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function esft_add_custom_box() {
    $screens = array( 'post', 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'esft',
            __( 'Easy Share Facebook Thumbnail', 'esft' ),
            'esft_inner_custom_box',
            $screen,
            'side'
        );
    }
}

function esft_save_postdata(){
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !isset( $_POST['esft_noncename'] ) || !wp_verify_nonce( $_POST['esft_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  //sanitize user input
  $mydata = sanitize_text_field( $_POST['mask_type'] );

  // Do something with $mydata 
  // either using 
  add_post_meta($post_ID, '_esft_meta', $mydata, true) or
  update_post_meta($post_ID, '_esft_meta', $mydata);
  
  $fb_thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($thumbnail->ID), array( 200, 200 ) );
  $tn=get_the_post_thumbnail( $post_ID );
  if( $_POST['mask_type'] != "none" && $_POST['mask_type'] != "grunge" && $_POST['mask_type'] != "masked" && $_POST['mask_type'] != "punch" && $_POST['mask_type'] != "rounded" ):
	$overlay= esft_overlay( 
	 	array( 'method' => urlencode( 'overlay' ), 
	 	'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
	 	'image_url' => urlencode( $fb_thumbnail_src[0] ), 
	 	'thumbnail' => basename( substr( basename( $fb_thumbnail_src[0] ), 0, -4 ) ), 
	 	'image_type' => ltrim( strstr( basename( $fb_thumbnail_src[0] ), '.' ), '.' ), 
	 	'mask_type' => $_POST['mask_type'] ) );					
 endif;

}

function esft_inner_custom_box( $post ){
	wp_nonce_field( plugin_basename( __FILE__ ), 'esft_noncename' );
	//print_r( get_post_meta($post->ID, '_esft_meta') );
	
	//check if featured image available
	//then get the url
	if( has_post_thumbnail( $post->ID ) )
		$get_fi = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
	
	if( $get_fi[1] < 200 || $get_fi[2] < 200 ):
		//add_action( 'admin_notices', esft_message_admin( 'Error! Featured image dimension must greater than 200 x 200. ', true ) );
		$disable_dd = true;
	endif;
?>
	<p>
		<label for="smashing-post-class"><?php _e( "Default Mask for Featured Image:", 'esft' ); ?></label>
		<br />
		<select name="mask_type" <?php if( $disable_dd == true ): echo "disabled=disabled"; endif; ?> > 
	        <option value="none" <?php selected( get_post_meta($post->ID, '_esft_meta', true), "none" ); ?>>None</option>
		<option value="grunge" <?php selected( get_post_meta($post->ID, '_esft_meta', true), "grunge" ); ?>>Grunge</option>
		<option value="masked" <?php selected( get_post_meta($post->ID, '_esft_meta', true), "masked" ); ?>>Masked</option>
		<option value="punch" <?php selected( get_post_meta($post->ID, '_esft_meta', true), "punch" ); ?>>Punch</option>
		<option value="rounded" <?php selected( get_post_meta($post->ID, '_esft_meta', true), "rounded" ); ?>>Rounded</option>
	        <?php	
	        	/*
	        	 @ Check for any premium subscription here
	        	 @ Return dropdown
	        	*/	        	
			$result = esft_get_subscription( 
				array( 'method' => urlencode( 'get' ), 
				'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
				'image_url' => urlencode( $get_fi[0] ), 
				'thumbnail' => $row->thumbnail, 
				'image_type' => $row->image_type ) 
				);
				
			$data = unserialize( $result );
															
			if( $data ):
			
			for( $x=0; $x < count( $data ); $x++ ){					
					echo '<option value="' . $data[$x]['content'] . '"';
					selected( get_post_meta($post->ID, '_esft_meta', true), $data[$x]['content'] ); 
					echo '>' . ucfirst( $data[$x]['content'] ) . '</option>';					
			}				
					
			endif;
										
		        ?>
		</select>
		
		<p style="font-size: 11px;"><i><a href="http://easyfacebooksharethumbnail.com/masks/" target="_blank">See Mask Samples</a></i></p>
		<?php
			if( $disable_dd == true )
				echo 'Featured image dimension must greater than 200 x 200.';
		?>
	</p>
	
<?php
}



?>