<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() ;?>/amazon-s3-photo-gallery/style.css"  />
<?php
    	 if(!empty($success)){
	  echo '<div class="updated settings-error">'.$success.'</div>';
	  }
	  
	  ?>
<form action="" name="add_caption" id="add_caption" method="post" >
<input type="hidden" name="token" id="token" value="save_caption"  />
<table class="widefat">
<thead>
 <tr>
  <th>S.No.</th>
  <th>Image</th>
  <th>Caption</th>
   <th>Comment</th>
 </tr>
</thead>

<tfoot>
 <tr>
  <th>S.No.</th>
  <th>Image</th>
  <th>Caption</th>
  <th>Comment</th>
  
 </tr>
</tfoot>

<tbody> 
<tr>
<td colspan="3"><input type="submit" name="save_caption" id="save_caption" value="Save Captions"  /></td>
</tr>
 <?php
 $counter = 0;
$c_t=$wpdb->prefix."amg_caption";
 if(empty($_GET['count']))
 $_GET['count']=1;
 $total_images=count($photos);
 $up_to=($_GET['count']-1)*10;
 $p_keys=array_keys($photos);
 
 for($k=0;$k<$up_to;$k++)
 {
		 unset($photos[$p_keys[$k]]);
 }
 
  $k=0;
 foreach($photos as $key => $photo){
 if($k>9)
 {
	break;
 }
 $k++;
	 $hash=$photo['hash'];
	 $c_row = $wpdb->get_row("SELECT * FROM ".$c_t." WHERE photo_hash= '".$hash."'");
	 $comment = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."amg_comment WHERE photo_hash='".$hash."'"));
	 

 ?>
 <tr>
   <td><?php echo $counter+1 ?></td>
   
   <td><img src="<?php echo "http://".$row->url.".s3.amazonaws.com/".$photo['name']; ?>" width="100" height="100"/></td>
   <td><textarea name="caption[<?php echo $hash; ?>]" rows="3" cols="80"><?php echo trim(stripslashes($c_row->caption));?></textarea></td>
   <td><?php if($comment): ?><a href="<?php echo admin_url('admin.php?page=manage-gallery&mode=comment&hash='.$hash); ?>"><?php echo stripslashes($comment); ?></a><?php endif;?></td>

 </tr>
 <?php
 $counter++;
 }
 
 ?>
 <tr>
 </tr>
 </tbody>
</table>
</form>
<br />
<?php
$total_pages=ceil($total_images/10);
	   
	   echo '<div style="clear:both;"> <ul id="pagination-digg">';
	   for($p=1;$p<=$total_pages;$p++)
	   {
		if($p==$_GET['count'])
		$s='class="active"';
		else
		$s='';
		
		 echo "<li><a ".$s." href='admin.php?page=manage-gallery&mode=view&id=".$_GET['id']."&count=".$p."' >".$p." </a></li>";   
	   }
	   //end here
	  echo "</ul>";
?>	  

