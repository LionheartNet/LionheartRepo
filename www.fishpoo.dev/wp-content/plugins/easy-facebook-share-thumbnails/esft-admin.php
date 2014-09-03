<?php
	
	$record = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %s", $table ));
	
	if( is_wp_error($record) ) echo $record->get_error_message();
	
		if ( $record != "0" )
			{
?>
		<div class="wrap">
		 <?php
		 	echo '<a href="http://easyfacebooksharethumbnail.com" target="_blank"><img src="' . plugins_url('easy-facebook-share-thumbnails/images/logosm.jpg') . '"></a>';
		 ?>
		 <h2>Welcome to Easy Facebook Share Thumbnail</h2>
		 <?php
		 function get_external_ip() {
		    $ch = curl_init("http://icanhazip.com/");
		    curl_setopt($ch, CURLOPT_HEADER, FALSE);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		    $result = curl_exec($ch);
		    curl_close($ch);
		    if ($result === FALSE) {
		        return "ERROR";
		    } else {
		        return trim($result);
		    }
		}	
		
		//echo get_external_ip();
		//print_r( $_SERVER );
		//$ip=$REMOTE_ADDR;  
		//echo "IP: $ip";
		
		/*
		 @ Check if blog has tagline
		 @ useful when sharing homepage in
		 @ facebook
		*/
		if( get_bloginfo(  'description' ) == "" )
			echo '<div id="message" class="error"><p>Please define a tagline in Settings > General > Tagline. This is used for the facebook open graph site description when the post excerpt is undefined.</p></div>';
		
		/*
		 @ If there's a default thumbnail
		 @ Force user to upload/set default thumbnail
		*/
		if( !$wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE active=1" ) )
			echo '<div id="message" class="error"><p>No default thumbnail! Please upload or set new default thumbnail.</p></div>';
		
    		?>     
		 <div class="postbox">
		  <table class="form-table">
		   <tr valign="top" class="alternate">
		    <th scope="row"><label>Available Thumbnail</label></th><th scope="row"><label>Default Mask</label></th><th scope="row"><label>Default Thumbnail</label></th><th scope="row"><label>Action</label></th>
		   </tr>
<?php
			$select_frame = $wpdb->get_results("SELECT * FROM $table ORDER BY active desc");
			
			if( is_wp_error($select_frame) ) echo $select_frame->get_error_message();
			
			foreach( $select_frame as $key => $row ) {
			// each column in your row will be accessible like this
			$thumbnail = $row->thumbnail;
			$url = $row->url;
			$activate = $row->active;
?>
		    <tr>
		     <td>
		      <img src = "<?php echo easy_facebook_share_thumbnails_url . $thumbnail . '-rounded.' . $row->image_type; ?>" width = "50px">
		     </td>
		     <td>		     
		      <form method="post" action="options-general.php?page=facebook-thumbnail-slug&a=change-mask">
		       <input type="hidden" name="thumbnail_url" value="<?php echo $row->url; ?>">
		       <input type="hidden" name="thumbnail" value="<?php echo $row->thumbnail; ?>">
		       <input type="hidden" name="image_type" value="<?php echo $row->image_type; ?>">
		      
		       <select name="mask_type" <?php if($activate == "1"){ echo ""; }else{ echo "hidden"; } ?>> 
		        <option value="none" <?php selected( get_option( 'easy_share_mask_type' ), "none" ); ?>>None</option>
		        <option value="grunge" <?php selected( get_option( 'easy_share_mask_type' ), "grunge" ); ?>>Grunge</option>
		        <option value="masked" <?php selected( get_option( 'easy_share_mask_type'), "masked" ); ?>>Masked</option>
		        <option value="punch" <?php selected( get_option( 'easy_share_mask_type'), "punch" ); ?>>Punch</option>
		        <option value="rounded" <?php selected( get_option( 'easy_share_mask_type'), "rounded" ); ?>>Rounded</option>
		        <?php	
		        	        	/**
		        /*
	        	 @ Check for any premium subscription here
	        	 @ Return dropdown
	        	*/	        
	        	
			$result = esft_get_subscription( 
				array( 'method' => urlencode( 'get' ), 
				'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
				'image_url' => urlencode( $row->url ), 
				'thumbnail' => $row->thumbnail, 
				'image_type' => $row->image_type ) 
				);			        		
		        	 	
					$data = unserialize( $result );
					var_dump( $data );										
					 if( $data ):
					for( $x=0; $x < count( $data ); $x++ ){					
						echo '<option value="' . $data[$x]['content'] . '"';
						selected( get_option( 'easy_share_mask_type'), $data[$x]['content'] ); 
						echo '>' . ucfirst( $data[$x]['content'] ) . '</option>';					
					}				
					
					endif;
										
		        ?>
		       </select>
		       <input type="submit" class="button-primary" value = "Change mask" <?php if($activate == "1"){ echo ""; }else{ echo 'disabled="disabled"'; } ?>>
		      </form>
		     </td>
		     <td>
		      <?php if($activate == "1"){ echo "Yes"; } else { echo "No"; }; ?>
		     </td>
		     <td>
		      <input type="submit" class="button-primary" value = "Delete" onClick = "location.href='options-general.php?page=facebook-thumbnail-slug&a=delete-thumbnail&ID=<?php echo $row->id; ?>';"> 
		      <?php if($activate == "1") {?>
		       <input type="submit" class="button-primary" value = "Unset as default" onClick = "location.href='options-general.php?page=facebook-thumbnail-slug&a=remove-thumbnail&ID=<?php echo $row->id; ?>';">
		      <?php }else{ ?> 
		       <input type="submit" class="button-primary" value = "Set as default" onClick = "location.href='options-general.php?page=facebook-thumbnail-slug&a=activate-thumbnail&ID=<?php echo $row->id; ?>';">
		      <?php } ;?>
		     </td>
		    </tr>
<?php
				}				
?>		   			 	
 		  </table>
 		 </div>
		</div>
		
		<?php
		/*
		 @ Check premium susbcription
		 @ if not, display message to persuade user
		 @ to buy premium
		*/
		$chck_premium = esft_check_premium( array( 'method' => urlencode( 'check_subscription' ), 'host' => urlencode( $_SERVER['HTTP_HOST'] ) ) );
		echo unserialize( $chck_premium );
		if( unserialize( $chck_premium ) == "This domain is premium" ):
		?>
			<div class="wrap">
			<div style="border: 1px solid; margin: 10px 0px; padding: 8px 10px 8px 40px; background-repeat: no-repeat; background-position: 10px 10px; border-radius: 3px 3px 3px 3px;color: #000000; background-color: #E1FED4;"><p>You are using the premium version with extra masks enabled. To unlock more, <a href="http://easyfacebooksharethumbnail.com/unlock/" target="_blank">Click Here</a>!</p></div>
			</div>
		<?php
				
		else:
		?>
			<div class="wrap">
			<div style="border: 1px solid; margin: 10px 0px; padding: 8px 10px 8px 40px; background-repeat: no-repeat; background-position: 10px 10px; border-radius: 3px 3px 3px 3px;color: #9F6000; background-color: #FEEFB3;"><p>You are using the free version with 4 great overlay masks. We have others available that you can unlock! <a href="http://easyfacebooksharethumbnail.com/unlock/" target="_blank">Click Here</a></p></div>
			</div>
		<?php
		endif;
		?>
		
		<div class="wrap">
		<h2>Thumbnail Mask Samples</h2>
		 <div class="postbox">
 		  <table class="form-table" cellpadding="20">
 		  <tr><td><img src="<?php echo plugins_url( '/easy-facebook-share-thumbnails/masks-sample/roundmask.png' ); ?>"></td><td><img src="<?php echo plugins_url( '/easy-facebook-share-thumbnails/masks-sample/grungesample.png' ); ?>"></td>
 		  </tr>
 		  <tr><td><img src="<?php echo plugins_url( '/easy-facebook-share-thumbnails/masks-sample/roundedsample.png' ); ?>"></td><td><img src="<?php echo plugins_url( '/easy-facebook-share-thumbnails/masks-sample/punchsample.png' ); ?>"></td></tr>
 		  </table>
 		  </div>
 		 </div>
 		 
 		<?php
 		 /*
 		  @ Check and display premium
 		  @ overlay here
 		*/
 		$premium_sample = esft_display_premium_samples( array( 'method' => 'display_sample', 'host' => urlencode( $_SERVER['HTTP_HOST'] ) ) );
 		
 		if( $premium_sample ):	
 		?>
 		<div class="wrap">
		<h2>Premium Thumbnail Mask Samples</h2>
		 <div class="postbox">
 		  <table class="form-table" cellpadding="20">
 		<?php
 		//print_r($premium_sample );
 		for( $x=0; $x<count( $premium_sample ); $x++ ):
 		if( !fmod( $x, 2 ) )
 			echo '<tr><td><img src="' . $premium_sample[$x]['url'] . '"></td><td><img src="' . $premium_sample[$x + 1]['url'] . '"></td>' ;//</tr>
 		
 		
 		if( fmod( $x, 2 ) )
 			echo '</tr>';
 		
 		endfor;
 		?>
 		  </table>
 		  </div>
 		 </div> 		 
<?php
		 endif;
			}
?>
		<div class="wrap">
		<form method="post" enctype="multipart/form-data" action="options-general.php?page=facebook-thumbnail-slug&a=add-new-thumbnail">
		<input type="hidden" name="" id="info_update1" value="true" />
		 <h2>Upload New Thumbnail</h2>
		 <div class="postbox">
		  <table class="form-table">
		   <!--<tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Thumbnail Name:</label></th><td><input type = "text" name = "thumbnail"></td></tr>-->
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Set To Default. Use the thumbnail about to be uploaded for posts without featured images and as well as the home page.:</label></th><td><input type = "checkbox" name = "active"></td></tr>	  
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Image:</label></th><td><input type = "file" name = "image"></td></tr> 
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ></th><td><input type="submit" class="button-primary" value = "Submit"></td></tr> 
		  </table>
		 </div>
		</form>
		</div>
		
		<div class="wrap">
		<form method="post" action="options-general.php?page=facebook-thumbnail-slug&a=other-settings">
		 <h2>Additional Facebook OpenGraph Settings</h2>
		 <div class="postbox">		  
		  <table class="form-table">
		  
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Specify Site Name:</label></th><td><input type = "text" name = "site_name" value="<?php echo get_option( 'esft_site_name' ); ?>"></td></tr>
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Specify App ID:</label></th><td><input type = "text" name = "app_id" value="<?php echo get_option( 'esft_app_id' ); ?>"></td></tr>
		   
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Use Published Time Info:</label></th><td><input type = "checkbox" name = "published_time" <?php get_option( 'esft_published_time' ) == "on" ? $chk = "checked" : $chk = ""; echo $chk; ?>></td></tr>	
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ><label>Use Modified Time Info:</label></th><td><input type = "checkbox" name = "modified_time" <?php get_option( 'esft_modified_time' ) == "on" ? $chk = "checked" : $chk = ""; echo $chk; ?>></td></tr>	
		   <tr valign="top" class="alternate"><th scope="row"style="width:32%;" ></th><td><input type="submit" class="button-primary" value = "Submit"></td></tr> 
		   <tr valign="top" class="alternate">
		    <td colspan="2">
		     <label><input value="Debug or validate your pages" type="button" onclick="window.open( 'https://developers.facebook.com/tools/debug' );" ></label> 
		     <label><input value="Generate like button code" type="button" onclick="window.open( 'http://developers.facebook.com/docs/reference/plugins/like/' );" ></label>
		    </td>
		   </tr>
		   <tr valign="top" class="alternate">
		    <td colspan="2">
		     <label>
		     <strong>FAQ:<br><br>Q: I have updated the featured image but it is not being reflected when sharing the post on facebook</strong>
		     <br>A: When trying out sharing functionality on facebook, your page may be saved or cached in the incorrect manner. If you have been making a number of changes to your post and would like facebook to get a fresh copy, kindly add a random number to your post url instead of the Xs like so:
http://domain.com/this-is-the-post-title/?efs=XXXX
This will trick facebook into thinking that you are sharing a different page, and load up the latest copy (of the same page).<br><br>
			<strong>Q: I have updated the plugin and it stopped working!</strong><br>A: Kindly try and deactivate and reactive the plugin on the Plugins page. If this doesnt work, please make sure that no other facebook function-adding plugins are running alongside this one. <br/><br/> 
			<strong>Q: I cannot seem to upload an image. </strong><br/>A: Be sure that the thumbnails directory in the plugin directory is writable.<br/><br/>
			<strong>Q: I want to use different thumbnail for each pages/posts that I want to share in Facebook
			</strong><br/>A: You can do this using Featured Image in Post/Page settings.<br/><br/>
			<strong>Q: My current theme does not support Featured Image
			</strong><br/>A: The plugin will automatically activate Featured Image even the current theme used does not support this feature.<br/><br/>
			<strong>Q: What image files that are currently supported? 
			</strong><br/>A: Only .GIF, .PNG and .JPEG that are currently supported.<br/><br/>
			<strong>Q: Something is not working correctly. Whats wrong?
			</strong><br/>A: The plugin is still in its infancy. The rapid evolution of facebook is great, but its hard to keep up sometimes when developing free plugins. Were committed to the plugin though! Please help by reporting any bugs you find. Please do this before giving us a bad rating - we will do everything we can to fix things! <a href="http://easyfacebooksharethumbnail.com/report-bug/" target="_blank">Report A bug</a>
			</label><br/><br/>
		     <iframe src="http://easyfacebooksharethumbnail.com/info.html" width="700px" height="400px" frameborder="0" scrolling="no"></iframe>
		    </td>
		   </tr>
		  </table>
		 </div>
		</form>
		</div>