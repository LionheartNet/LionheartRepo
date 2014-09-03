<?php
/*
 @ Overlaying PNG's function
*/

function easy_facebook_overlay( $image, $image_name, $image_types, $overlay, $output, $overlay_name ){
	//Open two images 
	//determine background file type
	$image_type = exif_imagetype( $image );
	if( $image_type == IMAGETYPE_GIF ):
		$background = imagecreatefromgif( $image );  
	endif;
	
	if( $image_type == IMAGETYPE_PNG ):
		$background = imagecreatefrompng( $image );  
	endif;
	
	if( $image_type == IMAGETYPE_JPEG ):
		$background = imagecreatefromjpeg( $image );  
	endif;
	
	$logo = imagecreatefrompng( $overlay ); 
	
	// check if logo exist to avoid error
	if( $logo ):
		//Find the size of the PNG 
		$sizepng = getimagesize( $overlay ); 
		$pngw = $sizepng[0]; 
		$pngh = $sizepng[1]; 
		
		$sizejpg = getimagesize( $image ); 
		$placementx = $sizejpg[0] - ($pngw + 0); 
		$placementy = $sizejpg[1] - ($pngh + 0); 
	
		//Merge two images together 
		imagecopy($background,$logo,0,0,0,0,$pngw, $pngh); 
		//echo $image_type;
		//Output 
		if( $image_type == IMAGETYPE_GIF ):
			imagejpeg($background, $output . "/" . $image_name . "-" . $overlay_name . ".gif"); 
		endif;
		
		if( $image_type == IMAGETYPE_PNG ):
			imagepng($background, $output . "/" . $image_name . "-" . $overlay_name . ".png" , 9); 
		endif;
		
		if( $image_type == IMAGETYPE_JPEG ):
			imagejpeg($background, $output . "/" . $image_name . "-" . $overlay_name . ".jpg" , 100);  
		endif;  
	endif;
}

/*
 @ Get the subscription here
*/
function esft_get_subscription( $data=array( 'method', 'host', 'image_url', 'thumbnail', 'image_type' ) ){

	/**
	 * Checking for premium subscription
	*/			        		
	$url = "http://cdn.easyfacebooksharethumbnail.com/premium.php"; 
		        	  
	$fields = array( 'method' => urlencode( $data['method'] ), 'host' => urlencode( $data['host'] ), 'image_url' => urlencode( $data['image_url'] ), 'thumbnail' => $data['thumbnail'], 'image_type' => $data['image_type'] );
		        	  
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
				  
	//open connection
	$ch = curl_init();
					
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
	//execute post
	$result = curl_exec($ch);
					
	//close connection
	curl_close($ch);
	
	return $result;
}

/*
 @ Check for premium subscription
 @ function
*/
function esft_check_premium( $data=array( 'method', 'host' ) ){
 	$url = "http://cdn.easyfacebooksharethumbnail.com/premium.php"; 
		        	  
	$fields = array( 'method' => urlencode( $data['method'] ), 'host' => urlencode( $data['host'] ) );
				        	  
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
						  
	//open connection
	$ch = curl_init();
							
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
	//execute post
	$result = curl_exec($ch);
							
	//close connection
	curl_close($ch);
	
	return $result;
}

/*
 @ Overlay premium 
*/
function esft_overlay( $data=array( 'method', 'host', 'image_url', 'thumbnail', 'image_type', 'mask_type' ) ){
 	$url = "http://cdn.easyfacebooksharethumbnail.com/premium.php"; 
		        	  
	$fields = array( 'method' => urlencode( $data['method'] ), 'host' => urlencode( $data['host'] ), 'image_url' => urlencode( $data['image_url'] ), 'thumbnail' => urlencode( $data['thumbnail'] ), 'image_type' => urlencode( $data['image_type'] ), 'mask_type' => urlencode( $data['mask_type'] ) );
				        	  
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
						  
	//open connection
	$ch = curl_init();
							
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
	//execute post
	$result = curl_exec($ch);
							
	//close connection
	curl_close($ch);
	
	return $result;
}

/*
 @ Get the overlayed images
*/
function esft_get_premium_image( $data=array( 'method', 'host', 'thumbnail', 'image_type', 'mask_type' ) ){
 	$url = "http://cdn.easyfacebooksharethumbnail.com/premium.php"; 
		        	  
	$fields = array( 'method' => urlencode( $data['method'] ), 'host' => urlencode( $data['host'] ), 'thumbnail' => urlencode( $data['thumbnail'] ), 'image_type' => urlencode( $data['image_type'] ), 'mask_type' => urlencode( $data['mask_type'] ) );
				        	  
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
						  
	//open connection
	$ch = curl_init();
							
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
	//execute post
	$result = curl_exec($ch);
							
	//close connection
	curl_close($ch);
	
	return $result;
}

/*
 @ Get sample premium mask
 @ and display
*/
function esft_display_premium_samples( $data=array( 'method', 'host' ) ){
	$url = "http://cdn.easyfacebooksharethumbnail.com/premium.php"; 
		        	  
	$fields = array( 'method' => urlencode( $data['method'] ), 'host' => urlencode( $data['host'] ) );
				        	  
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
						  
	//open connection
	$ch = curl_init();
							
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
	//execute post
	$result = curl_exec($ch);
							
	//close connection
	curl_close($ch);
	
	return unserialize( $result );
}

?>