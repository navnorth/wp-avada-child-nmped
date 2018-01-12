<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/**
 *
 * Out of Date List Table extending the Built in WP List Table
 * 
 **/
class Out_Of_Date_List_Table extends WP_List_Table{
    
    function __construct() {
        parent::__construct( array(
            'singular'=> 'content', //Singular label
            'plural' => 'contents', //plural label, also this well be one of the table css class
            'ajax'   => false //We won't support Ajax for this table
        ) );
    }
    
    /** Displays table before and after the table **/
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
           //The code that goes before the table is here
           
        }
        if ( $which == "bottom" ){
           //The code that goes after the table is there
           
        }
    }
    
    /**
    * Define the columns that are going to be used in the table
    * @return array $columns, the array of columns to use with the table
    */
    function get_columns() {
        return $columns= array(
           'col_id'=>__('ID'),
           'col_title'=>__('Name'),
           'col_url'=>__('Url'),
           'col_modified_date'=>__('Post_Modified'),
           'col_author'=>__('Author')
        );
    }
    
    /**
    * Decide which columns to activate the sorting functionality on
    * @return array $sortable, the array of columns that can be sorted by the user
    */
    public function get_sortable_columns() {
        return $sortable = array();
    }
    
    /**
    * Prepare the table with different parameters, pagination, columns and table elements
    */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
        
        $args = array(
                    'post_type' => array( 'post', 'page' ),
                    'posts_per_page' => 25 ,
                    'post_status' => 'publish',
                    'orderby' => 'modified',
                    'order' => 'ASC'
                );
      
        /* -- Preparing your query -- */
   
        /* -- Pagination parameters -- */
        $allPosts = new WP_Query(apply_filters(
                'widget_post_args' ,
                array(
                    'post_type' => array( 'post', 'page' ),
                    'posts_per_page' => -1 ,
                    'post_status' => 'publish',
                    'orderby' => 'modified',
                    'order' => 'ASC'
                )
            ));
        
        //Number of elements in your table?
        $totalitems = $allPosts->post_count; //return the total number of affected rows
        
        //How many to display per page?
        $perpage = 25;
        
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
           
        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        
        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
        
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $args['posts_per_page'] = $perpage;
            $args['paged'] = $paged;
        }
        
        /* -- Register the pagination -- */
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
         ) );
         //The pagination links are automatically built according to those parameters
   
        /* -- Register the Columns -- */
         $columns = $this->get_columns();
         $_wp_column_headers[$screen->id]=$columns;
   
        /* -- Fetch the items -- */
        $outPosts = new WP_Query(
            apply_filters(
                'widget_post_args' ,
                $args
            )
        );
        $this->items = $outPosts->posts;
    }
}


?>