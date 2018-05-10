<?php get_header(); ?>
<div class="content">
	<?php
	
	$id=get_the_ID();

	$postData = get_post($id);

	$publisher = wp_get_post_terms($id, 'publisher', array("fields" => "all"));
    $author = wp_get_post_terms($id, 'author', array("fields" => "all"));

	 ?>
	<div class="post_details">
		<div class="title"><h2><?php echo the_title(); ?></h2></div>
		<div class="description"><p><?php echo $postData->post_content; ?></p></div>
		<div class="meta_info">
			<span>Price</span>
			<p><?php echo get_post_meta($id,'price',true); ?></p>

			<span>Rating</span>
			<p><?php echo get_post_meta($id,'rating',true); ?></p>
		</div>
		<div class="cleat" style="clear:both"></div>

		<div class="terms">
			<span>Author Name</span>
			<p><?php  echo $author[0]->name;?></p>
			<span>Publisher Name</span>
           <p><?php  echo $publisher[0]->name;?></p>
		</div>
	</div>
</div>
<?php get_footer(); ?>