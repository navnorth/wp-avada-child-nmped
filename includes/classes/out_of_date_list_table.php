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
}


?>