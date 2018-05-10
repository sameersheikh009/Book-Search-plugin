<?php
$price=  explode('-',$_REQUEST['price']);
$title=  $_REQUEST['book_name'];

       

		// If only for title 
		$results1 =get_posts(array(
            'post_type' => 'book',
            's' => $title,
           ));
		
        $results =array(
            'post_type' => 'book',
           // 's' => $title,
            'post_status' => 'publish',
             'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'publisher',
                    'field'    => 'slug',
                    'terms'    => $_REQUEST['publisher'],
                ),
                array(
                    'taxonomy' => 'author',
                    'field'    => 'slug',
                    'terms'    => $_REQUEST['author'],
                ),
            ),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'rating',
                    'value'   => $_REQUEST['rating'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => 'price',
                    'value'   => $price,
                    'type' => 'numeric',
                    'compare' => 'BETWEEN'
                )
            )
        );

		//$postData = new wp_query($results);
	
        $postResult= get_posts($results);

        $dataMerge = array_merge($results1,$postResult); // merget 2 array 

        $finalResult = array_unique($dataMerge,SORT_REGULAR);
 
        ?>
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th> 
                <th>Book Name</th>
                <th>Price</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Rating</th>
            </tr>
        </thead>
      
        <tbody>
        <?php
        $j=1;
        foreach ($finalResult as $key => $value) {
            $rating=get_post_meta($value->ID,'rating',true);
            $book_details_url = add_query_arg('', $term->post_title, get_permalink($value->ID)); // Create book detail page URL
            $publisher = wp_get_post_terms($value->ID, 'publisher', array("fields" => "all"));
            $author = wp_get_post_terms($value->ID, 'author', array("fields" => "all"));

            ?>
             <tr>
                <td><?php  echo $j;?></td>
                <td>
                    <h5><a href="<?php echo $book_details_url; ?>"><?php  echo $value->post_title;?></a></h5>
                    <span><?php   echo wp_trim_words( $value->post_content, 10, '...' );?></span>
                </td>
                <td><?php  echo get_post_meta($value->ID,'price',true);?></td>
                 <td><?php  echo $author[0]->name;?></td>
                <td><?php  echo $publisher[0]->name;?></td>               
                <td><?php
                            if(isset($rating) && $rating >=0){
                                
                                for ($i=1; $i <=$rating ; $i++) { // loop for showing activated star                                    
                                       ?>
                                        <span class="fa fa-star checked"></span>
                                       <?php
                                    }    
                            }else{
                                echo '<span class="fa fa-star"></span>';                                
                            }
                             ?>
                </td>                  
            </tr>           
            <?php
            $j++;
        }
        ?>
        </tbody>
    </table>
    <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#example').DataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bInfo": false,
                    "bAutoWidth": false });
            } );
    </script>