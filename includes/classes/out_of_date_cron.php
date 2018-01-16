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
        
        if (count($nmPosts->posts)>0) {
        
            $recipients = array();
            
            // All Editors
            $all_editors = get_option('nmped_to_all_editors');
            if ($all_editors){
                $editors = get_users(array('role'=>'editor'));
                foreach ($editors as $editor){
                    $recipients[]  = $editor->user_email;
                }
            }
            
            // All Division Leads
            $all_div_leads = get_option('nmped_to_all_division_leads');
            if ($all_div_leads){
                $div_leads = get_users(array('role'=>'division_lead'));
                foreach ($div_leads as $lead){
                    $recipients[]  = $lead->user_email;
                }
            }
            
            // additional recipients
            $to = get_option('nmped_recipient_emails');
            $to = explode(',', $to);
            if (is_array($to)) {
                foreach($to as $email){
                    $recipients[] = $email;   
                }
            }
            
            $recipients = array_unique($recipients);
            
            $subject = "Out-Of-Date Content Reminder";
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            $mail = wp_mail($recipients, $subject, $email_content, $headers);
        
        }
    }
}
?>