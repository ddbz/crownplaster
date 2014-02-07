 <table class="widefat" cellpadding="0" cellspacing="0">
 <thead>
 <tr>
  <th>S.No.</th>
  <th>Comment</th>
  <th>Delete</th>
 </tr>
 </thead>
  <tfoot>
 <tr>
  <th>S.No.</th>
  <th>Comment</th>
  <th>Delete</th>
 </tr>
 </tfoot>
 <tbody>
 <?php
 $counter = 0;
 foreach($comments as $comment){
 ?>
 <tr>
  <td><?php echo $counter+1; ?></p>
</td>
  <td>
    <?php echo stripslashes($comment->comment); ?>
	<div class="submitted-on">Submitted on : <?php echo date('F j, Y',strtotime($comment->when_add)); ?> at <?php echo date('g:i a',strtotime($comment->when_add)); ?></div>
  </td>
  <td><a href="<?php echo admin_url('admin.php?page=manage-gallery&mode=delete&cid='.$comment->id); ?>" class="button-primary" >Delete</a></td>
 <?php
 $counter++;
 }
 ?> 