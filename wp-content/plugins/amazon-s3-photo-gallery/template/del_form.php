<?php 
if($album_id){
?>
<form method="post" action="" name="frmlist" id="frmlist">
<input type="hidden" name="token" id="token" value="<?php echo base64_encode('delete_album'); ?>" />
<input type="hidden" name="album_id" id="album_id" value="<?php echo $album->id; ?>" />
<div class="wrap">
<p style="color:#21759B; font-size:20px; font-weight:bold;">Are You sure delete <?php echo stripslashes($album_name); ?> album?</p>
<p><a class="button-primary" onclick="document.frmlist.submit();">Delete</a>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=manage-gallery'); ?>" class="button-highlighted">Cancel</a></p>
</div>
</form>
<?php 
}
if(isset($_GET['cid'])){
$cid = $_GET['cid'];
$comments = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'amg_comment WHERE id = '.$cid);
?>
<form method="post" action="" name="frmlist" id="frmlist">
<input type="hidden" name="token" id="token" value="<?php echo base64_encode('delete_comment'); ?>" />
<input type="hidden" name="cid" id="cid" value="<?php echo $_GET['cid']; ?>" />
<div class="wrap">
<p style="color:#21759B; font-size:20px; font-weight:bold;">Are You sure delete this comment?</p>
<p><?php echo $comments->comment; ?></p>
<p>&nbsp;</p>
<p><a class="button-primary" onclick="document.frmlist.submit();">Delete</a>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=manage-gallery&mode=comment&hash='.$comments->photo_hash); ?>" class="button-highlighted">Cancel</a></p>
</div>
<?php
}
?>

