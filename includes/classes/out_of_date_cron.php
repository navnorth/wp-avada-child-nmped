<?php

include_once wp_normalize_path( get_stylesheet_directory() . '/templates/email.notification.php' );

class NMPED_Notification_Cron {
    private $_debug;

    public function __construct()
    {

    }
    
    /**
     * Run
     * Description
     */
    public static function run()
    {
        $age = get_option('nmped_age_days');
        
        $args = array(
            'post_type' => array( 'post', 'page' ),
            'posts_per_page' => -1 ,
            'post_status' => 'publish',
            'date_query' => array(
                array(
                        'column' => 'post_modified_gmt',
                        'before' => $age . ' days ago'
                )
            ),
            'orderby' => 'modified',
            'order' => 'ASC'
        );
        
        $nmPosts = new WP_Query($args);
        
        $email_content = out_of_date_notification($nmPosts->posts);
        
        $to = get_option('nmped_recipient_emails');
        $subject = "Out-Of-Date Content Reminder";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        $mail = wp_mail($to, $subject, $email_content, $headers);
    }
}
?>