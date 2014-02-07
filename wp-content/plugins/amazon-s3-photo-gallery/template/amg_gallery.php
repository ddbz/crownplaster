<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() ;?>/amazon-s3-photo-gallery/style.css"  />
<?php

$table = $wpdb->prefix."amg_comment";
if(isset($_POST['photo_comment'])){
 $email = esc_attr($_POST['email']);
 $author = esc_attr($_POST['author']);
 $comment = esc_attr($_POST['comment']);
 $error = '';
 if ( $email == '' || '' == $author )
 $error = __('Error: please fill the required fields (name, email).') ;
 elseif ( !is_email($email))
 $error =  __('Error: please enter a valid email address.');
 if ( '' == $comment )
 $error =  __('Error: please type a comment.');
 if(empty($error)){
   $data = array('photo_hash' => $_GET['id'],'comment' => $comment);
   $wpdb->insert($table,$data);
   $admin_email  = get_option('admin_email');
   $admin_email='rob.hozour@gmail.com';
   $subject = "A New comment added on Gallery";
   $headers = 'From: '.$email . "\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $comments = "<br>A new comment added by ".$author.".  <a href='".site_url()."/index.php?amgallery=true&gallery=".$_GET['gallery']."&file=".$_GET['file']."&id=".$_GET['id']."'>Click Here</a> to view comment. <br><br><br>".esc_attr($_POST['comment']);
   if(!wp_mail($admin_email,$subject,$comments,$headers)){
    $error = __('Sending email problem.');
   }
 }
}
?>
		<div>
			<div id="content" role="main">
			<div style="width:800px;">
            <?php
			$gallery = isset($_GET['gallery']) ? $_GET['gallery']: '';
			$file = isset($_GET['file']) ? $_GET['file']: '';
			$id = isset($_GET['id']) ? $_GET['id']: '';
			
			if(!empty($gallery) && $file == ''){
			  $photos =  $s3->getBucket($gallery);
			 
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
			  foreach($photos as $key=>$photo){
			  
			  if($k>9)
			  {
			 	break;
			  }
			  $k++;
			  
			  $caption = $wpdb->get_row("SELECT caption FROM ".$wpdb->prefix."amg_caption WHERE photo_hash = '".$photo['hash']."'");
			   ?>
			   <div style="width:160px; float:left;">
			    <a href="index.php?amgallery=true&gallery=<?php echo $gallery; ?>&file=<?php echo base64_encode($photo['name']) ?>&id=<?php echo $photo['hash']; ?>">
				 <img src="<?php echo "http://".$gallery.".s3.amazonaws.com/".$photo['name']; ?>" width="150" height="100" alt="<?php echo stripslashes($caption->caption); ?>" title="<?php echo stripslashes($caption->caption); ?>" />
				</a>

			   </div>
               
			   <?php
			  }
			   // pagination code here
			   $total_pages=ceil($total_images/10);
			   
			   echo '<div style="clear:both;"> <ul id="pagination-digg">';
			   for($p=1;$p<=$total_pages;$p++)
			   {
				if($p==$_GET['count'])
				$s='class="active"';
				else
				$s='';
				
				 echo "<li><a ".$s." href='index.php?amgallery=true&gallery=".$_GET['gallery']."&count=".$p."' >".$p." </a></li>";   
			   }
			   //end here
			  echo "</ul>";
			 }
			 else{
			 $imagepath = "http://".$gallery.".s3.amazonaws.com/".base64_decode($_GET['file']);
			 $wpdb->show_errors();
			 $comments = $wpdb->get_results("SELECT * FROM $table WHERE photo_hash ='".$_GET['id']."'");
			 $caption = $wpdb->get_row("SELECT caption FROM ".$wpdb->prefix."amg_caption WHERE photo_hash = '".$_GET['id']."'");
			 echo '<img src="'.$imagepath.'" width="800" />';
			 echo $caption->caption;     
			 ?>
			 <?php 
			  if($error)
			   echo '<font color="#FF0000">'.$error.'</font>';
			?>
			 <?php
			 if($comments){
			  foreach($comments as $comment){
			 ?>
			<ol class="commentlist">
			<li id="" class="comment even thread-even depth-1">
		     <article class="comment" id="comment-6">
		      	<footer class="comment-meta">
                  <em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
				 <br>
			    </footer>
			   <div class="comment-content"><p><?php echo $comment->comment; ?></p></div>
		    </article>
	      </li>
		  </ol>
		  <?php
		    }
		  }
		  ?>
           	
             
			<div id="respond">
			 <h3 id="reply-title">Leave a Reply</h3>
			 <form id="commentform" method="post" action="">
				<p class="comment-notes">Your email address will not be published. Required fields are marked <span class="required">*</span></p>							
				<p class="comment-form-author"><label for="author">Name</label> <span class="required">*</span><input type="text" aria-required="true" size="30" value="<?php echo $_POST['author'];?>" name="author" id="author"></p>
				<p class="comment-form-email">
				<label for="email">Email</label> 
				<span class="required">*</span><input type="text" aria-required="true" size="30" value="<?php echo $_POST['email'];?>" name="email" id="email"></p>
				<p class="comment-form-comment">
				  <label for="comment">Comment</label>
				  <textarea aria-required="true" rows="8" cols="45" name="comment" id="comment"><?php echo $_POST['comment'];?></textarea></p>						<p class="form-allowed-tags">You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:  <code>&lt;a href="" title=""&gt; &lt;abbr title=""&gt; &lt;acronym title=""&gt; &lt;b&gt; &lt;blockquote cite=""&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=""&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=""&gt; &lt;strike&gt; &lt;strong&gt; </code></p>						<p class="form-submit">
				  <input type="submit" value="Post Comment" id="submit" name="submit">
				  <input type="hidden" name="amg_image_id" value="<?php echo $id; ?>" />
				  <input type="hidden" value="token" id="token" name="photo_comment">
				</p>
			</form>
		   </div>
			 <?php	   
			 } 
			 ?>  
             </div>
			</div><!-- #content -->
			
		</div><!-- #primary -->

<?php get_footer(); ?>