<?php	
	   if(isset($_POST['bucketname'])){
	  $albumname = esc_attr($_POST['albumname']);
 	  $bucketname = esc_attr($_POST['bucketname']);
	  $path = '';
	  $error = '';
	  check_admin_referer('amg_editgallery');
	  
	  if($albumname == '')
	  {
		  $error="Enter your album name.";
	}
	  
	  if($error=='')
	  {
		  
	
	  if($bucketname != '' ){
		   $amgalbum = $albumname;
		   $path= $bucketname;
		 
		 if($s3->getBucketLocation($bucketname)){
		   $wpdb->show_errors();
		   $data = array('album_name' => $amgalbum,'url' => $path);
		   $wpdb->update($table,$data,array('id' => $album_id));
	 	    site_redirect($current_url.'&msg=Album Update Successfully.');
		 }
		 else{
		 $error = "This bucket not exists";
		 } 
	   }	  
	  else{
	   $error =  'Enter your amazon storage bucket name.';
	  }
	  
	  }
	 	}
		$album->album_name=stripslashes($album->album_name);
	?>	
		<div class="wrap">
		<h2><?php _e('Edit '.$album->album_name) ;?></h2>
	<?php
    	 if(!empty($error)){
		 unset($_GET['msg']);
	  echo '<div class="error fade"><p>'.$error.'</p></div>';
	  }
	  
	  ?>

<?php
         $success = $_GET['msg'];
    	 if(!empty($success)){
	  echo '<div class="updated settings-error"><p>'.$success.'</p></div>';
	  }
	  
	  ?>

        <form name="editgallery" id="editgallery_form" method="POST" action="" accept-charset="utf-8" >
		<?php wp_nonce_field('amg_editgallery') ?>
			<table class="form-table"> 
			<tr valign="top"> 
				<th scope="row"><?php _e('Album Name') ;?>:</th> 
				<td><input type="text" size="35" name="albumname" value="<?php echo $album->album_name; ?>" /><br />
				<i>( <?php _e('Allowed characters for file and folder names are', 'nggallery') ;?>: a-z, A-Z, 0-9, -, _ )</i></td>
			</tr>

            <tr valign="top"> 
				<th scope="row"><?php _e('Bucket Name', 'nggallery') ;?>:</th> 
				<td><input type="text" size="35" name="bucketname" value="<?php echo $album->url; ?>" /><br />
				<i>( <?php _e('Allowed characters for file and folder names are', 'nggallery') ;?>: a-z, A-Z, 0-9, -, _ )</i></td>
			</tr>
			</table>
			<div class="submit">
			  <input class="button-primary" type="submit" name= "addgallery" value="<?php _e('Edit Album', 'nggallery') ;?>"/>
			  <a href="<?php echo admin_url('admin.php?page=manage-gallery'); ?>" class="button-highlighted">Cancel</a>
			</div>
		</form>
		</div>
