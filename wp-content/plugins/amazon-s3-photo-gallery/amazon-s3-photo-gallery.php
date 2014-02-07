<?php 
/*
Plugin Name: Amazon S3 Photo Gallery
Plugin URI: http://wordpress.org/extend/plugins/amazon-s3-photo-gallery/
Description: This WordPress plugin allows you to use Amazon's Simple Storage Service to host your images for your Photo Gallery.
Author: flippercode
Version: 1.0.2
Author URI: http://wordpress.org/extend/plugins/amazon-s3-photo-gallery/
*/

/*
Copyright 2011-2013 phpsourcecode (http://phpsourcecode.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
error_reporting(0);
$amazon_settings = get_option('amg_settings');
if(!is_array($amazon_settings)){
   add_action('admin_notices', 'amg_settings_notice');
}
function amg_settings_notice(){
    echo '<div class="updated">
           <p>Enter amazon access key and secret key <a href="'.admin_url().'admin.php?page=settings">Click here</a></p>
         </div>';
}
define('ACCESS_KEY',$amazon_settings['amg_key']);
define('SECRET_KEY',$amazon_settings['amg_sec']);
include_once(dirname(__FILE__)."/lib/S3.php");
$s3 = new S3(ACCESS_KEY,SECRET_KEY);
add_action('admin_menu','amazon_admin_menu');
register_activation_hook( __FILE__, "amazon_register_activate" );
register_deactivation_hook( __FILE__, "amazon_register_deactivate" );


function amazon_admin_menu(){
add_menu_page(__('Amazon S3 Photo Gallery'),__('Amazon S3 Photo Gallery'),'manage_options',__file__,'amazon_gallery_menu');
  add_submenu_page(__FILE__,__('Manage Albums'),__('Manage Albums'),'manage_options','manage-gallery','amazon_gallery_manage');
  add_submenu_page(__FILE__,__('Settings'),__('Settings'),'manage_options','settings','amazon_gallery_settings');
}

function amazon_gallery_settings(){
  ?>
   <?php 
    $options =  get_option('amg_settings');
	$amg_key = $options['amg_key'];
	$amg_sec = $options['amg_sec'];
    if(isset($_POST['token']) && $_POST['token'] == 'save_settings'){
	  $amg_key = esc_attr($_POST['accesskey']);
 	  $amg_sec = esc_attr($_POST['secretkey']);
	  if($amg_key != ''){
	    if($amg_sec != ''){
		  $amgs3 = new S3($amg_key,$amg_sec);
          if($amgs3->listBuckets()){
		    update_option('amg_settings',array('amg_key' => $amg_key,'amg_sec' => $amg_sec));
			$success='<p>Settings Saved Successfully.</p>';
		  }else{
		    $error = 'Invalid access key and secret';
		  }
		}else{
		  $error = 'Enter amazon secret key';
		}
	  }else{
	    $error = 'Enter amazon access key';
	  }
	}
   ?> 
   <div class="wrap">
		<h2><?php _e('Amazon S3 Settings', 'aspg') ;?></h2>
	 <?php
      if(!empty($error)){
	    echo '<div class="error fade"><p>'.$error.'</p></div>';
	   }
	 ?>
     <?php
      if(!empty($success)){
	    echo '<div class="updated settings-error">'.$success.'</div>';
	  }
	  ?>
        <form name="amg_settings" id="amg_settings" method="POST" action="" accept-charset="utf-8" >
		<input type="hidden" name="token" id="token" value="save_settings" />
		<?php wp_nonce_field('amg_settings') ?>
			<table class="form-table"> 
			<tr valign="top"> 
				<th scope="row"><?php _e('Access Key', 'aspg') ;?>:</th> 
				<td><input type="text" size="35" name="accesskey" value="<?php echo $amg_key; ?>" /><br />
				<i><?php _e('Enter amazon access key', 'aspg') ;?></i></td>
			</tr>

            <tr valign="top"> 
				<th scope="row"><?php _e('Secret Key', 'aspg') ;?>:</th> 
				<td><input type="text" size="35" name="secretkey" value="<?php echo $amg_sec; ?>" /><br />
				<i><?php _e('Enter amazon secret key', 'aspg') ;?></i></td>
			</tr>
			</table>
			<div class="submit"><input class="button-primary" type="submit" name= "addsettings" value="<?php _e('Save', 'aspg') ;?>"/></div>
		</form>
		</div>
  <?php
}

function amazon_gallery_menu() {
global $wpdb,$s3,$amazon_settings;
    if(isset($_POST['bucketname'])){
	  $albumname = esc_attr($_POST['albumname']);
 	  $bucketname = esc_attr($_POST['bucketname']);
	  $path = '';
	  $error = '';
	  check_admin_referer('amg_addgallery');
	  if(!is_array($amazon_settings)){
	   $error = 'Enter amazon access key and secret key <a href="'.admin_url().'admin.php?page=settings">Click here</a>';
	  }
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
		  
		  
		   $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."amg_album (album_name, url) 
		   VALUES (%s, %s)", $amgalbum, $path));
	 	   $success='<p>Album Created Successfully.</p>';
	 	   
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
    ?>
		<!-- create gallery -->
		<div class="wrap">
		<h2><?php _e('Add Album', 'aspg') ;?></h2>
	<?php
    	 if(!empty($error)){
	  echo '<div class="error fade"><p>'.$error.'</p></div>';
	  }
	  
	  ?>

<?php
    	 if(!empty($success)){
	  echo '<div class="updated settings-error">'.$success.'</div>';
	  }
	  
	  ?>

        <form name="addgallery" id="addgallery_form" method="POST" action="" accept-charset="utf-8" >
		<?php wp_nonce_field('amg_addgallery') ?>
			<table class="form-table"> 
			<tr valign="top"> 
				<th scope="row"><?php _e('Album Name', 'aspg') ;?>:</th> 
				<td><input type="text" size="35" name="albumname" value="" /><br />
				<i>( <?php _e('Allowed characters for file and folder names are', 'aspg') ;?>: a-z, A-Z, 0-9, -, _ )</i></td>
			</tr>

            <tr valign="top"> 
				<th scope="row"><?php _e('Bucket Name', 'aspg') ;?>:</th> 
				<td><input type="text" size="35" name="bucketname" value="" /><br />
				<i>( <?php _e('Allowed characters for file and folder names are', 'aspg') ;?>: a-z, A-Z, 0-9, -, _ )</i></td>
			</tr>
			</table>
			<div class="submit"><input class="button-primary" type="submit" name= "addgallery" value="<?php _e('Add Album', 'aspg') ;?>"/></div>
		</form>
		</div>
    <?php 
    }
	
	function amazon_register_activate(){
	 require_once( dirname(__FILE__) . '/lib/install.php' );
	 amazon_install();
	}
	
	function amazon_register_deactivate(){
	 require_once( dirname(__FILE__) . '/lib/install.php' );
	 amazon_uninstall();
	}
	
	function amazon_gallery_manage(){
	
	 global $wpdb,$s3;
	if(isset($_POST['token']) and $_POST['token']=='save_caption')
	{
		$c_t=$wpdb->prefix."amg_caption";
		foreach($_POST['caption'] as $cap=>$val)
		{
			$wpdb->query('delete from '.$c_t.' where photo_hash="'.$cap.'"');
			
			if(trim($val)!='')
			{
		   
			$wpdb->query($wpdb->prepare("insert into ".$c_t." (photo_hash,caption) 
		   VALUES (%s, %s)", $cap, $val));
				
			}
			
		}
		
		$success="All Captions has been updated successfully.";
	}	
		
	
	 $table = $wpdb->prefix."amg_album";
	 $album_id = $_GET['id'];
	 $mod = $_GET['mode'];
	 $current_url = 'admin.php?page=manage-gallery&mode='.$mod.'&id='.$album_id;
	 switch($mod)
	 {
	 
	 case 'view'       :  $row = $wpdb->get_row("SELECT album_name,url FROM $table WHERE id = ".$album_id);
	                      $photos = $s3->getBucket($row->url);
	                      include_once(dirname(__FILE__)."/template/view_gallery.php");
	                      break;
	 
	 case 'edit'	   :  $album = $wpdb->get_row("SELECT * FROM $table WHERE id=".$album_id); 	
	                      include_once(dirname(__FILE__)."/template/edit_album.php");
                          break; 
	 
	 case 'status'	   :  $album = $wpdb->get_row("SELECT * FROM $table WHERE id=".$album_id);
	                      if($album->status == 'Yes'){
						   $status = 'No';
						  }else{
						   $status = 'Yes'; 
						  }	  
	                      $wpdb->update($table,array('status' => $status),array('id' => $album_id));
						  site_redirect(admin_url('admin.php?page=manage-gallery'));
						  break;
						  
	case 'delete'	   :  $album = $wpdb->get_row("SELECT * FROM $table WHERE id=".$album_id);
	                      $album_name = $album->album_name;
	                      if(isset($_POST['token']) && base64_decode($_POST['token']) == 'delete_album'){
						   $album_id = esc_attr($_POST['album_id']);
						   $wpdb->show_errors();
						   $wpdb->query('DELETE FROM '.$table.' WHERE id='.$album_id);
						   site_redirect('admin.php?page=manage-gallery&msg='.$album_name." delete successfully.");
						  }
						  if(isset($_POST['token']) && base64_decode($_POST['token']) == 'delete_comment'){
						    $cid = $_POST['cid'];
							$wpdb->query('DELETE FROM '.$wpdb->prefix.'amg_comment WHERE id='.$cid);
							site_redirect('admin.php?page=manage-gallery&msg=Comment delete successfully.');
						  }
	                      include_once(dirname(__file__)."/template/del_form.php");
	                      break;
						  
	case 'comment'	   :  $hash = $_GET['hash'];
	                      $comments = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."amg_comment WHERE photo_hash='".$hash."'");				                   				  
	                      include_once(dirname(__FILE__)."/template/comments.php");
						  break;
						  
	 default           :  $galleries = $wpdb->get_results("SELECT * FROM $table");
	                      include_once(dirname(__FILE__)."/template/view_album.php");
						  break;
					 

	 }
	 
   }	
   add_shortcode('amazons3_photo_gallery','shotcode_show');
  
  function shotcode_show($attr){
  global $wpdb,$s3;

    if(isset($attr['id']) && $attr['id'] != ''){
	 $query = 'SELECT * FROM '.$wpdb->prefix.'amg_album WHERE id = '.$attr['id'].' AND status = "Yes"';
	 
	}
	else{
    $query = 'SELECT * FROM '.$wpdb->prefix.'amg_album WHERE status = "Yes"';
	}
	
	
	$albums =$wpdb->get_results($query);
	if($albums){
		
	
		
	  $output = '<div id="amg-gallery" style="width:620px;height:auto;">';
	  foreach($albums as $album){
	    $photos = $s3->getBucket($album->url,'','',1);
		$photo = array_keys($photos);
	    $output .= '<div style="width:200px;height:130px;float:left;padding:2px 2px;"><a href="index.php?amgallery=true&gallery='.$album->url.'"><img src="'."http://".$album->url.".s3.amazonaws.com/".$photo[0].'" width="200" height="100"></a><div style="width:200px;text-align:center;">'.$album->album_name.'</div></div>';
	
	  $output .= '</div>';
	  
	  return $output;
	
	}
		
	
	
	
	}
  
  }
  
  add_action( 'init', 'amg_like_init' );

function amg_like_init() 
{
  add_rewrite_rule( '^amgallery$', 'index.php?amgallery=true', 'top' );
}

add_action( 'query_vars', 'ag_query_vars' );

function ag_query_vars( $query_vars )
{
	$query_vars[] = 'amgallery';
    return $query_vars;
}

add_action( 'parse_request', 'amg_parse_request' );
function amg_parse_request( &$wp )
{
 global $s3,$wpdb;
    if ( array_key_exists( 'amgallery', $wp->query_vars ) ) {
        include( dirname( __FILE__ ) . '/template/amg_gallery.php' );
        exit();
    }
}

function site_redirect($url){
$admin_url = esc_url(admin_url($url));
echo '<script> document.location.href="'.$url.'"</script>';
}
	
?>