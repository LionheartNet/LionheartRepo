<?php
	global $wpdb;	
	global $post;
	$table = $wpdb->prefix . "fbthumbnails";
	$post_id = $post;	
	   		//echo $post_id;
	   		    
		    if ( is_single($post_id) || is_page($post_id) )
		    {
		    	//print_r( $post );
		    	$post_date = $post_id->post_date;
		    	$post_modified = $post_id->post_modified;
		    	
		    	$post_id = $post_id->ID;
		      	$site_title = get_the_title($post->ID);
		      	$fb_thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($thumbnail->ID), array( 200, 200 ) );
		      	//wp_get_attachment_image_src( get_the_post_thumbnail( $post_id, 'medium' ) );
		      	//wp_get_attachment_image_src(get_post_thumbnail_id($thumbnail->ID, 'medium'));
		      	$post_var = get_post($post_id);		      	
		        $raw_content = $post_var->post_content;	
		        if( $post_var->post_content != "" ):
		        	if( $post_var->post_excerpt != "" ):
		        		$content = wp_trim_words( strip_shortcodes( strip_tags( $post_var->post_excerpt ) ) );
		        	else:
		        		//$content = strip_tags( strip_shortcodes( $post_var->post_content ) ) . "asds";	
		        		$content = wp_trim_words( strip_shortcodes( strip_tags( $post_var->post_content ) ) );
		        	endif;
		        else:      
				$content = wp_trim_words( strip_shortcodes( strip_tags( $post_var->post_content ) ) );			
			endif;
			//$content = strpos( $raw_content, "." );	
	
			 echo "\n";
			 echo "\n";
			 //echo $post_id;
			 echo "<!-- Easy Facebook Share Thumbnails 1.9.6 -->";
			  echo "\n";
			 echo "<!-- Post/Page -->";
			 echo "\n";
			 echo '<meta property="og:title" content="'. $site_title . '"/>' . "\n";
			 echo '<meta property="og:type" content="article"/>' . "\n";
			 echo '<meta property="og:url" content="' . get_permalink($post_id->ID) . '"/>' . "\n";		
			 
			 //site name
			 if( get_option( 'esft_site_name' ) != "" ):
			 	echo '<meta property="og:site_name" content="' . get_option( 'esft_site_name' ) . '"/>' . "\n";
			 else:
			 	echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
			 endif;
			 
			 //app id
			 if( get_option( 'esft_app_id' ) != "" ):
			 	echo '<meta property="fb:app_id" content="' . get_option( 'esft_app_id' ) . '"/>' . "\n";			 
			 endif;
			 
			 //published_time
			 if( get_option( 'esft_published_time' ) != "" ):
			 	echo '<meta property="article:published_time" content="' . $post_date . '"/>' . "\n";			
			 endif;
			 
			 //modified_time
			 if( get_option( 'esft_modified_time' ) != "" ):
			 	echo '<meta property="article:modified_time" content="' . $post_modified . '"/>' . "\n";
			 endif;
			 
			 //page or post description
			 if( $content ):
			 	echo '<meta property="og:description" content="' . $content . '"/>' . "\n";
			 else:
			 	echo '<meta property="og:description" content="' . $content . '"/> <!-- This page/post has no either content or post excerpt -->' . "\n";
			 endif;
			 
			 if( has_post_thumbnail($post->ID) ):
			 
			 	//echo "\n";
			 	echo "<!-- Has Featured Image -->";
			 	echo "\n";
			 	
			 	/*
			 	 @ Check if this post has metadata for featured image overlay. 
			 	 @ If none, display normal thumbnail
			 	*/			 	
			 	$md=get_post_meta($post->ID, "_esft_meta");			 	
			 	if( $md ):			 		
			 		//user choose not to overlay
			 		if( $md[0] == "none" || $md[0] == "" ):
			 			echo "<!-- Has Metadata 'NONE' -->";
			 			echo "\n";
			 			echo '<meta property="og:image" content="' . $fb_thumbnail_src[0] . '" />' . "\n" . '<link rel="image_src" href="' . $fb_thumbnail_src[0] . '"/>' . "\n";
			 		
			 		/*
			 		 @ User choose grudge, masked and punched
			 		 @ if nothing comes here, it might premium
			 		*/
			 		elseif( $md[0] == "grunge" || $md[0] == "masked" || $md[0] == "punch" || $md[0] == "rounded" ):
			 			/*
			 			 @ Check if already in cache
			 			 @ if none, create thumbnail 
			 			 @ and use it
			 			*/
			 			$file_ext = ltrim( strstr( basename( $fb_thumbnail_src[0] ), '.' ), '.' );
			 			
			 			if( file_exists( easy_facebook_share_thumbnails_path . '/fi-cache/' . substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ) . '-' . $md[0] . '.' . $file_ext ) ):
			 				echo '<meta property="og:image" content="' . 
			 					easy_facebook_share_plugin_url . 
			 					'fi-cache/' . 
			 					substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ) . 
			 					'-' . $md[0] . '.' . 
			 					$file_ext . '" />' . "\n" . 
			 					'<link rel="image_src" href="' . 
			 					easy_facebook_share_plugin_url . 
			 					'fi-cache/' . 
			 					substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ) . 
			 					'-' . $md[0] . '.' . 
			 					$file_ext . '"/>' . "\n";
			 			else:
			 				/*
			 				 @ The given thumbnail is already 200 x 200		 				
			 				 @ Overlay thumbnail based on selected
			 				 @ thumbnail mask
			 				*/
						 	
			 				easy_facebook_overlay( $fb_thumbnail_src[0], 
			 					substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ), 
			 					exif_imagetype( $fb_thumbnail_src[0] ), 
			 					easy_facebook_share_thumbnails_path . "/masks/" . $md[0] . '.png', 
			 					easy_facebook_share_thumbnails_path . '/fi-cache/', 
			 					$md[0] );
			 					
			 				/*
			 				 @ Output our new thumbnail here
			 				*/
			 				echo "<!-- Has '" . $md[0] . "' Metadata -->";
			 				echo "\n";
			 				echo '<meta property="og:image" content="' . 
			 					easy_facebook_share_plugin_url . 
			 					'fi-cache/' . 
			 					substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ) . 
			 					'-' . $md[0] . '.' . 
			 					$file_ext . '" />' . "\n" . 
			 					'<link rel="image_src" href="' . 
			 					easy_facebook_share_plugin_url . 
			 					'fi-cache/' . 
			 					substr( basename( $fb_thumbnail_src[0], $file_ext ), 0, -1 ) . 
			 					'-' . $md[0] . '.' . 
			 					$file_ext . '"/>' . "\n";
			 			endif;
			 		
			 		else:
			 			/*
			 			 @ We deal with premium mask
			 			 @ in this area
			 			*/	
			 			$chkpremium = esft_check_premium( array( 'method' => urlencode( 'check_subscription' ), 'host' => urlencode( $_SERVER['HTTP_HOST'] ) ) );
						$data = unserialize( $chkpremium );
						
						if( $data ):
				 			$file_ext = ltrim( strstr( basename( $fb_thumbnail_src[0] ), '.' ), '.' );	 			
				 			$result=esft_get_premium_image( 
								array( 'method' => urlencode( 'display' ), 
								'host' => urlencode( $_SERVER['HTTP_HOST'] ),
								'thumbnail' => urlencode( substr( basename( $fb_thumbnail_src[0] ), 0, -4 ) ), 
								'image_type' => urlencode( $file_ext ), 
								'mask_type' => urlencode( $md[0] ) ) );	
							
							if( $result ):	
								echo "<!-- Using " . $md[0] . " Metadata( Premium ) -->";
					 			echo "\n";						 		
								echo '<meta property="og:image" content="' . $result . '"/>' . "\n";
							 	echo '<link rel="image_src" href="' . $result . '"/>' . "\n";
							 else:
							 	echo "<!-- Can't Communicate to Premium Server -->";						 	
								echo "<!-- Return Value: '" . $result . "' -->";
								echo "\n";
							 endif;
						else:
							echo "<!-- Can't Communicate to Premium Server -->";
							echo "<!-- Return Value: '" . $data . "' -->";
							echo "\n";
						endif;
			 		 endif;			 			
			 	else:
			 		echo "<!-- Has No Metadata -->";
			 		echo "\n";
			 		
			 		/**
			 		 @ Check thumbnail dimension
			 		 @ If lower than 200x200 respectively,
			 		 @ display error message
			 		*/
			 		
			 		echo '<meta property="og:image" content="' . $fb_thumbnail_src[0] . '" />' . "\n";
			 		echo '<link rel="image_src" href="' . $fb_thumbnail_src[0] . '"/>' . "\n";
			 	endif;
			 else:
			 	//echo "\n";
			 	echo "<!-- No Featured Image, use Default -->";
				echo "\n";
			 	$active = $wpdb->get_row("SELECT * FROM $table WHERE active = '1'", ARRAY_A);
			 	if( $active ):
			 		$image = $active['url'];				 	
				 	
		 			if( get_option( 'easy_share_mask_type' ) == "none" ):
				 		//$image = $active['url'];
				 		
				 		//echo "\n";
					 	echo "<!-- Using 'none' Mask Type -->";
					 	echo "\n";
					 	echo '<meta property="og:image" content="' . $image . '"/>' . "\n";
					 	echo '<link rel="image_src" href="' . $image . '"/>' . "\n";
					 	
					 elseif( get_option( 'easy_share_mask_type' ) == "grunge" || get_option( 'easy_share_mask_type' ) == "masked" || get_option( 'easy_share_mask_type' ) == "punch" || get_option( 'easy_share_mask_type' ) == "rounded" ):
					 	echo "<!-- Using '" . get_option( 'easy_share_mask_type' ) . "' Mask Type -->";
					 	echo "\n";
					 	
					 	echo '<meta property="og:image" content="' . easy_facebook_share_thumbnails_url . $active['thumbnail'] . "-" . get_option( 'easy_share_mask_type' ) . "." . $active['image_type'] . '" />' . "\n";
					 	echo '<link rel="image_src" href="' . easy_facebook_share_thumbnails_url . $active['thumbnail'] . "-" . get_option( 'easy_share_mask_type' ) . "." . $active['image_type'] . '"/>' . "\n";
					 
					 else:
					 	$name = $active['thumbnail'];
					 	/*
					 	 @ Check premium Subscription
					 	*/
					 	$chkpremium = esft_check_premium( array( 'method' => urlencode( 'check_subscription' ), 'host' => urlencode( $_SERVER['HTTP_HOST'] ) ) );
						$data = unserialize( $chkpremium );
																	
							 if( $data ):
							 	/*
							 	 @ get thumbnail
							 	*/
							 	
							 	$result=esft_get_premium_image( 
							 			array( 'method' => urlencode( 'display' ), 
							 			'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
							 			'mask_type' => urlencode( get_option( 'easy_share_mask_type' ) ), 
							 			'thumbnail' => urlencode( $name ), 
							 			'image_type' => urlencode( $active['image_type'] ) ) );
							 	
							 	if( $result ):
								 	echo "<!-- Using '" . get_option( 'easy_share_mask_type' ) . "' Mask Type( Premium ) -->";
									echo "\n";		
									echo '<meta property="og:image" content="' . $result . '"/>' . "\n";
						 			echo '<link rel="image_src" href="' . $result . '"/>' . "\n";	
					 			else:
					 				echo "<!-- Can't Communicate to Premium Server -->";
							 		echo "<!-- Return Value: '" . $result . "' -->";
									echo "\n";
					 			endif;
							 else:
							 	echo "<!-- Can't Communicate to Premium Server -->";
							 	echo "<!-- Return Value: '" . $data . "' -->";
								echo "\n";
							 endif;
					 endif;
				 else:
				 	echo "<!-- No Default Image, Please upload one! -->";
					echo "\n";				 
				 endif;
			 endif;
			 
			 echo "<!-- Easy Facebook Share Thumbnails -->";
			 echo "\n";
			 echo "\n";
		    }		    
		    else
		    {		 	
		 	//use default image instead
		 	//select active thumbnail in database
		 	$active = $wpdb->get_row("SELECT * FROM $table WHERE active = '1'", ARRAY_A);
		 	$image = $active['url'];
		 	$post_id = $post_id->ID;
		      	$site_title = get_the_title($post->ID);	
		      	
		 	 
		 	 echo "\n";
		 	 echo "\n";
		 	 echo "<!-- Easy Facebook Share Thumbnails 1.9.6 -->";
		 	 echo "<!-- Non Post/Page -->";
			 echo "\n";
			 echo '<meta property="og:title" content="'. get_bloginfo('name') . '"/>' . "\n";
			 echo '<meta property="og:type" content="article"/>' . "\n";
			 echo '<meta property="og:url" content="' . get_bloginfo('wpurl') . '"/>' . "\n";
			 
			 if( get_option( 'esft_site_name' ) != "" ):
			 	echo '<meta property="og:site_name" content="' . get_option( 'esft_site_name' ) . '"/>' . "\n";
			 else:
			 	echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
			 endif;
			 
			 //app id
			 if( get_option( 'esft_app_id' ) != "" ):
			 	echo '<meta property="fb:app_id" content="' . get_option( 'esft_app_id' ) . '"/>' . "\n";			 
			 endif;
			 
			 if( get_bloginfo('description') ):		 	
			 	echo '<meta property="og:description" content="' . get_bloginfo('description') . '"/>' . "\n";
			 else:
			 	echo '<meta property="og:description" content="' . get_bloginfo('description') . '"/> <!-- Please define Website Description in Wordpress General Settings -->' . "\n";
			 endif;
			 
			 	$active = $wpdb->get_row("SELECT * FROM $table WHERE active = '1'", ARRAY_A);
		 		if( $active ):
		 		
		 			//check mask if other than none
		 			//echo get_option( 'easy_share_mask_type' );
		 			if( get_option( 'easy_share_mask_type' ) == "none" ):
				 		$image = $active['url'];
				 		
				 		//echo "\n";
					 	echo "<!-- Using 'NONE' for Default Image -->";
					 	echo "\n";
					 	echo '<meta property="og:image" content="' . $image . '"/>' . "\n";
					 	echo '<link rel="image_src" href="' . $image . '"/>' . "\n";
					 	
					 elseif( get_option( 'easy_share_mask_type' ) == "grunge" || get_option( 'easy_share_mask_type' ) == "masked" || get_option( 'easy_share_mask_type' ) == "punch" || get_option( 'easy_share_mask_type' ) == "rounded" ):
					 	echo "<!-- Using '" . get_option( 'easy_share_mask_type' ) . "' for Default Image -->";
					 	echo "\n";
					 	echo '<meta property="og:image" content="' . easy_facebook_share_thumbnails_url . $active['thumbnail'] . "-" . get_option( 'easy_share_mask_type' ) . "." . $active['image_type'] . '" />' . "\n";
					 	echo '<link rel="image_src" href="' . easy_facebook_share_thumbnails_url . $active['thumbnail'] . "-" . get_option( 'easy_share_mask_type' ) . "." . $active['image_type'] . '"/>' . "\n";
					 else:					 	
					 	$name = $active['thumbnail'];		
					 	//check for premium subscription					 	 	
					 	$results = esft_check_premium( 
					 	 	array( 'method' => urlencode( 'check_subscription' ), 
					 	 	'host' => urlencode( $_SERVER['HTTP_HOST'] ) ) 
					 	);
					 	 		
						$data = unserialize( $results );
																
						 if( $data ):
						 	$result=esft_get_premium_image(
							 		array( 'method' => urlencode( 'display' ), 
							 		'host' => urlencode( $_SERVER['HTTP_HOST'] ), 
							 		'mask_type' => urlencode( get_option( 'easy_share_mask_type' ) ), 
							 		'thumbnail' => urlencode( $name ), 
							 		'image_type' => urlencode( $active['image_type'] ) )
							 	);
							if( $result ):
								echo '<meta property="og:image" content="' . $result . '"/>' . "\n";
					 			echo '<link rel="image_src" href="' . $result . '"/>' . "\n";	
					 		else:
					 			echo "<!-- Can't Get Overlay from Premium Server -->";
							 	echo "<!-- Return Value: '" . $result . "' -->";
								echo "\n";
					 		endif;
						 else:
						 	echo "<!-- Can't Communicate to Premium Server -->";
							echo "<!-- Return Value: '" . $data . "' -->";
							echo "\n";
						 endif;
					 endif;
				 else:
				 	echo "<!-- No Default image uploaded -->";
				 	echo "\n";
				 endif;
			// endif;
			 
			 echo "<!-- Easy Facebook Share Thumbnails -->";
			 echo "\n";
			 echo "\n";

		   }
?>