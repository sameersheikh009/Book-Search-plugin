<?php
/*
Plugin Name: Book Search
Plugin URI: 
Description:This plugin is for searching books
Author: Sameer Sheikh
Version: 1.1
Author 
*/


/*
* CREATE Constructor class to define all action and filter related of books
*/

    $config = array(
    'api_key' => 'cd64539dd19283cdcc637f2ccddcd45-us6'
);

class Book
{
    

    function __construct()
    {
        # create custom post with assigne taxonomies(author and publisher)
        add_action( 'init', array($this,'book_post_data') );

        #custom taxonomies declaration
        add_action( 'init', array($this,'books_taxonomy'));

        #add custom metabox to book post
        add_action( 'add_meta_boxes', array($this,'books_meta_box' ));

        # save custom field
        add_action( 'save_post', array($this, 'save_book_custom_meta') );

        # add css / js
        add_action( 'wp_enqueue_scripts', array($this,'book_related_scripts' ));

        // the ajax function
        add_action('wp_ajax_book_data_fetch' , array($this,'book_data_fetch'));
        add_action('wp_ajax_nopriv_book_data_fetch', array($this,'book_data_fetch'));

        /* CREATE SHORT CODE ACTION */
        add_shortcode('book_lauout', array($this,'book_layout_data'));

        /* create admin menu page for display(Copy) shortcode in back-end*/
        add_action('admin_menu', array($this,'display_book_shortcode'));

        /* Following filter is used for book post to redirect custom single page*/
        add_filter('single_template', array($this,'book_custom_template'));
    }

  

    function book_post_data(){
        $labels = array(
            'name'               => _x( 'Books', 'post type general name' ),
            'singular_name'      => _x( 'Book', 'post type singular name' ),
            'add_new'            => _x( 'Add New', 'book' ),
            'add_new_item'       => __( 'Add New Book' ),
            'edit_item'          => __( 'Edit Book' ),
            'new_item'           => __( 'New Book' ),
            'all_items'          => __( 'All Books' ),
            'view_item'          => __( 'View Book' ),
            'search_items'       => __( 'Search Books' ),
            'not_found'          => __( 'No Books found' ),
            'not_found_in_trash' => __( 'No Books found in the Trash' ), 
            'parent_item_colon'  => '',
            'menu_name'          => 'Books'
        );
        
        $args = array(
            'labels'        => $labels,
            'description'   => 'Holds our Books and Book specific data',
            'public'        => true,
            'menu_position' => 5,
            'taxonomies' => array( 'test' ),
            'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
            'has_archive'   => true,
        );
        register_post_type( 'Book', $args ); 

    }

    /*
    *  Taxonomies callback 
    */

    function books_taxonomy(){

        /* Create Author Taxonomy */
        $args = array(
            'label' => __( 'Author' ),
            'rewrite' => array( 'slug' => 'author' ),
            'hierarchical' => true,
        );

        register_taxonomy( 'author', 'book', $args );

        /* Create Publisher Type Taxonomy */
        $args = array(
                'label' => __( 'Publisher' ),
                'rewrite' => array( 'slug' => 'publisher' ),
                'hierarchical' => true,
            );

        register_taxonomy( 'publisher', 'book', $args );

    }

    /*
    * METABOX CALLBACK
    */

    function books_meta_box(){
        add_meta_box(
            'book_custom_data', // $id
            'Book Custom Data(Price & Rating)', // $title
            array( $this, 'show_book_fields_meta_box' ),
            'book', // $screen
            'normal', // $context
            'high' // $priority
    );
    }

    
    /*
    *  CALL BAKC META DATA APPEARING IN ADMIN BACKEND
    */
    function show_book_fields_meta_box() {
        global $post;  
        $post->ID;
        $price = get_post_meta( $post->ID, 'price', true );
        $rating = get_post_meta( $post->ID, 'rating', true ); ?>

        <input type="hidden" name="price" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

        <p>
            <label for="price">Add Price</label>
            <br>
            <input type="text" name="price" id="price" class="regular-text" value="<?php echo $price; ?>">
        </p>

        <p>
            <label for="your_fields[select]">Rating</label>
            <br>
            <select name="rating" id="your_fields[select]">
                    <option value="1" <?php if($rating == 1){ echo 'selected="selected"';}  ?>>1</option>
                    <option value="2" <?php if($rating == 2){ echo 'selected="selected"';}  ?>>2</option>
                    <option value="3" <?php if($rating == 3){ echo 'selected="selected"';}  ?>>3</option>
                    <option value="4" <?php if($rating == 4){ echo 'selected="selected"';}  ?>>4</option>
                    <option value="5" <?php if($rating == 5){ echo 'selected="selected"';}  ?>>5</option>
            </select>
        </p>
    <?php }

    /*
    * CALLBACK SAVE CUSTOM FIELDS
    */

     public  function save_book_custom_meta($post_id)
    {
        if (array_key_exists('price', $_POST)) {
            update_post_meta($post_id, 'price', $_POST['price'] );
        }

           if (array_key_exists('rating', $_POST)) {
            update_post_meta($post_id, 'rating', $_POST['rating'] );
        }
    }

    /*
    * SCITP CALLBACK
    */

    public function book_related_scripts()
    {
       wp_enqueue_style( 'jquery.mobile', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );

        //wp_enqueue_style( 'font-awesome', plugins_url('/css/font-awesome.min.css', __FILE__) );
        wp_enqueue_style( 'jquery-ui.css', plugins_url('/css/jquery-ui.css', __FILE__) );
        wp_enqueue_style( 'custom', plugins_url('/css/custom.css', __FILE__) );
        wp_enqueue_style( 'dataTables', plugins_url('/css/dataTables.bootstrap.min.css', __FILE__) );

        //wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.js');


       // wp_register_script('jquery-1.12.4', plugins_url('/js/jquery-1.12.4.js', __FILE__), true);
       // wp_enqueue_script('jquery-1.12.4');

        wp_register_script('jquery-ui', plugins_url('/js/jquery-ui.js', __FILE__), array('jquery'),'1.1', true);
        wp_enqueue_script('jquery-ui');

        wp_register_script('jquery.dataTables', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array('jquery'),'1.1', true);
        wp_enqueue_script('jquery.dataTables');

        wp_register_script('custom', plugins_url('/js/custom.js', __FILE__), array('jquery'),'1.1', true);
        wp_enqueue_script('custom');

    }

    /*
    * BOOK LAYOUT FUNCTIOIN (SHORTCODE CALLBACK)
    */ 
    function book_layout_data($atts, $content = null) {

        /*
        *  CALL CUSTOM PAGE FOR DISPLAY BOOK SEARCH LAYOUT
        */

        include( plugin_dir_path( __FILE__ ) . 'front_book_page.php');

    }

    /*
    * BOOK RESULT DISPLAY CALL BACK FUNCTION
    */ 
    public function book_data_fetch()
    {
       
        // Calll a custom file for showing fetch data       
        include( plugin_dir_path( __FILE__ ) . 'book_fetch_data.php');
    }

    /*
    * following function is for callback of admin menu page for display Or copy book shortcode
    */

    public function display_book_shortcode()
     {
       add_menu_page('Book Shortcode', 'Book shortcode', 'manage_options', 'menu_slug', array($this, 'book_shortcode_admin_page_call') ,'dashicons-media-spreadsheet');

     } 

     // following function for diplay shortcode name in admin menu page in backend
     public static function book_shortcode_admin_page_call()
     {
       
        echo '<h3> Copy folliwing Shortcode to any page.</h3>';
        echo '<span>[book_lauout]</span>';
     }


       /*
    * Following callbakc is used for single page of book post detail
    */
    function book_custom_template($single) {

    global $wp_query, $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'book' ) {
     
        if ( file_exists( plugin_dir_path( __FILE__ ). '/single-book.php' ) ) {
            return plugin_dir_path( __FILE__ ). '/single-book.php';
        }
    }

    return $single;

}
}

$var = new Book ($config);