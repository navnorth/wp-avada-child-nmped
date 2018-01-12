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
           /*'ID'=>__('ID'),*/
           'post_title'=>__(''),
           /*'post_url'=>__('Url'),*/
           'post_modified'=>__(''),
           /*'post_author_name'=>__('')*/
        );
    }
    
    /**
    * Decide which columns to activate the sorting functionality on
    * @return array $sortable, the array of columns that can be sorted by the user
    */
    public function get_sortable_columns() {
        return $sortable = array();
    }
    
     public function get_hidden_columns() {
        return array();
    }
    
    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'ID':
            case 'post_title':
            case 'post_url':
            case 'post_modified':
            case 'post_author_name':
                return $item->$column_name;
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
    
    /**
    * Prepare the table with different parameters, pagination, columns and table elements
    */
    function prepare_items() {
        
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
         
        $this->_column_headers = array($columns);
   
        /* -- Fetch the items -- */
        $outPosts = new WP_Query(
            apply_filters(
                'widget_post_args' ,
                $args
            )
        );
        
        $results = $outPosts->posts;
        
        foreach($results as $result){
            $author = get_userdata($result->post_author);
            $author_url = get_author_posts_url($result->post_author);
            
            $author_name = $author->display_name;
            if ($author->first_name!=="" || $author->last_name!=="")
                $author_name = $author->first_name . " " . $author->last_name;
            
            $result->post_author_name = $author_name;
            $result->post_url = esc_url(get_permalink($result->ID));
            $result->author_url = $author_url;
        }
        
        $this->items = $outPosts->posts;
    }
    
    /**
    * Display the rows of records in the table
    * @return string, echo the markup of the rows
    */
    function display_rows() {
   
        //Get the records registered in the prepare_items method
        $records = $this->items;
   
        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();
   
        //Loop for each record
        if(!empty($records)){
            foreach($records as $rec){
        
                //Open the line
                echo '<tr id="record_'.$rec->ID.'">';
           
                    foreach ( $columns as $column_name => $column_display_name ) {
   
                        //Style attributes for each col
                        $class = "class='$column_name column-$column_name'";
                        $style = "";
                        if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
                            $attributes = $class . $style;
               
                        //edit link
                        $editlink  = admin_url('post.php?post='.(int)$rec->ID.'&action=edit');
               
                        //Display the cell
                        switch ( $column_name ) {
                            case "ID":
                                echo '<td '.$attributes.'>'.stripslashes($rec->ID).'</td>';
                                break;
                            case "post_title":
                                echo '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($rec->post_title).'</a><br/>'.$rec->post_url.'</td>';
                                break;
                            case "post_url":
                                echo '<td '.$attributes.'>'.$rec->post_url.'</td>';
                                break;
                           case "post_modified":
                                $post_mod_time = strtotime($rec->post_modified);
                                echo '<td '.$attributes.'>'.date( "M jS Y", $post_mod_time ).'<br/><a href="'.$rec->author_url.'" target="_blank">'.stripslashes($rec->post_author_name).'</a></td>';
                                break;
                           case "post_author_name":
                                echo '<td '.$attributes.'>'.stripslashes($rec->post_author_name).'</td>';
                                break;
                        }
                    }
           
                 //Close the line
                 echo '</tr>';
            }
        }
    }
    
    public function no_items() {
        _e( 'No contents found.', 'wp-avada-child-nmped' );
    }
    
    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name( $item ) {
    
        $title = '<strong>' . $item['name'] . '</strong>';
    
        return $title;
    }
}


?>