<div data-role="main" class="ui-content">
	<form class="form-book" id="book_search_form" name="book_search_form"  action="<?php //echo admin_url('admin-ajax.php'); ?>" method="post">

	<div class="left">
	    <div class="element">
	        <label>Book Name</label>
	        <input type="text" name="book_name" id="book_name">
	    </div>

	    <div class="element">
	        <label>Publisher</label>
	        <select name="publisher" id="publisher">
	            <?php
	                $getPublisher = get_terms('publisher');
	                foreach ($getPublisher as $key => $value) {
	                   echo '<option value='.$value->term_id.'>'.$value->name.'</option>';
	                }
	             ?>
	        </select>
	    </div>

	    <div class="element">
	        <label for="amount">Price range:</label>
	        <input type="text" name="price" id="amount"  style="border:0; color:#f6931f; font-weight:bold;">
	        <div id="slider-range"></div>
	    </div>

	</div>

	<div class="right">
	    <div class="element">
	        <label>Author</label>
	        <input type="text" name="author" id="author">
	    </div>


	    <div class="element">
	        <label>Rating</label>
	        <select name="rating" id="rating">
	            <option value="1" <?php //if($rating == 1){ echo 'selected="selected"';} //selected( $meta['select'], '1' ); ?>>1</option>
	            <option value="2" <?php //if($rating == 2){ echo 'selected="selected"';} //selected( $meta['select'], '2' ); ?>>2</option>
	            <option value="3" <?php //if($rating == 3){ echo 'selected="selected"';} //selected( $meta['select'], '3' ); ?>>3</option>
	            <option value="4" <?php //if($rating == 4){ echo 'selected="selected"';} //selected( $meta['select'], '4' ); ?>>4</option>
	            <option value="5" <?php //if($rating == 5){ echo 'selected="selected"';} //selected( $meta['select'], '5' ); ?>>5</option>
	    </select>
	    </div>
	</div>   

	     <input type="submit" class="center" align="center" id="find_book" data-inline="true" value="Submit">   

	</form>

	<div id="datafetch"></div>
</div>
<script type="text/javascript">
 jQuery(document).ready(function(){
	jQuery("#find_book").click(function(){
	        var book_name = jQuery('#book_name').val();
	        var author = jQuery('#author').val();
	        var publisher = jQuery('#publisher option:selected').text();
	        var rating = jQuery('#rating option:selected').text();	      
	        var price = jQuery('#amount').val();

	        jQuery.ajax({
	                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
	                    type: jQuery('#book_search_form').attr('method'),
	                    data: { action: 'book_data_fetch',
	                    book_name:book_name,
	                    author:author,
	                    publisher: publisher,
	                    rating:rating,
	                    price:price,
	                formData: jQuery('#book_search_form').serialize() },	                 
	                success: function(response) {
	                   jQuery('#datafetch').html(response);
	                }
	            });
	        return false;
	       });
    });
</script>