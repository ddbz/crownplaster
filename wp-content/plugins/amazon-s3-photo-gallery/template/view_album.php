<?php
     $success = $_GET['msg'];
    	 if(!empty($success)){
	  echo '<div class="updated settings-error"><p>'.$success.'</p></div><br>';
	  }
echo '<div class="updated settings-error"><p><b>How to Use Amazon S3 Photo Gallery</b> - Use [amazons3_photo_gallery] shortcut code in a post or page.</p></div><br>';
if($galleries){ 
?>
 <table class="widefat" cellpadding="0" cellspacing="0">
 <thead>
 <tr>
  <th>S.No.</th>
  <th>Album Name</th>
  <th>Edit</th>
  <th>Status</th>
  <th>Delete</th>
  <th>View</th>
  <th>Photos</th>
    <th>Shortcode</th>

 </tr>
 </thead>
  <tfoot>
 <tr>
  <th>S.No.</th>
  <th>Album Name</th>
  <th>Edit</th>
  <th>Status</th>
  <th>Delete</th>
  <th>View</th>
  <th>Photos</th>
      <th>Shortcode</th>

 </tr>
 </tfoot>
 <tbody>
 <?php 
   $counter = 0;
   foreach($galleries as $album){
   $photos =  $s3->getBucket($album->url);
  if($album->status == 'Yes'){
  $status = 'Unpublished';
  }
  else{
  $status = 'Published';
  }
 ?> 
 <tr>
   <td><?php echo $counter+1 ?></td>
   <td><a href="admin.php?page=manage-gallery&mode=view&id=<?php echo $album->id; ?>" ><?php echo stripslashes($album->album_name); ?></a></td>
   <td><a href="admin.php?page=manage-gallery&mode=edit&id=<?php echo $album->id; ?>" class="button-secondary">Edit</a></td>
   <td><a href="admin.php?page=manage-gallery&mode=status&id=<?php echo $album->id; ?>"  class="button-secondary"><?php echo $status; ?></a></td>
   <td><a href="admin.php?page=manage-gallery&mode=delete&id=<?php echo $album->id; ?>" class="button-secondary">Delete</a></td>
   <td><a href="admin.php?page=manage-gallery&mode=view&id=<?php echo $album->id; ?>" class="button-secondary">View</a></td>
   <td><?php echo count($photos); ?></td>
   <td>Copy and paste <b><?php echo '[amazons3_photo_gallery id='.$album->id.']'; ?></b> your page on post<br /> to view single album.</td>

 </tr>
 <?php
 }
  ?>
  </tbody>
 </table>
 <?php
 }
 else
 {
 ?>
 <table class="widefat">
  <tr>
   <td height="50" align="center">
     <strong><h2>No album exists.</h2></strong>
	 Create new <a href="<?php echo admin_url('admin.php?page=admin.php?page=amazon-s3-photo-gallery/amazon-s3-photo-gallery.php'); ?>">album</a>
   </td>
  </tr>
 </table>
 <?php
 }
 ?>