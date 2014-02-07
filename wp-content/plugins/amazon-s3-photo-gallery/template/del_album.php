<form method="post" action="" name="frmlist" id="frmlist">
<input type="hidden" name="token" id="token" value="<?php echo base64_encode('delete_album'); ?>" />
<input type="hidden" name="album_id" id="album_id" value="<?php echo $album->id; ?>" />
<div class="wrap">
<p style="color:#21759B; font-size:20px; font-weight:bold;">Are You sure delete <?php echo stripslashes($album_name); ?> album?</p>
<p><a class="button-primary" onclick="document.frmlist.submit();">Delete</a>&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=manage-gallery'); ?>" class="button-highlighted">Cancel</a></p>
</div>
</form>