<?php

function out_of_date_notification($posts) {
    $html = "<p>The following pages on the NMPED site have not been updated recently:</p>";
    
    if ($posts){
        $html .= '<ul>';
        foreach($posts as $post){
            $html .= '<li>';
            
            $url = get_permalink($post->ID);
            
            $author = get_userdata($post->post_author);
            
            $author_name = $author->display_name;
            if ($author->first_name!=="" || $author->last_name!=="")
                $author_name = $author->first_name . " " . $author->last_name;
            
            $post_mod_time = strtotime($post->post_modified);
                
            $html .= '<a href="'.$url.'">'.$post->post_title.'</a>, '.$author_name.' - ' . date( "F j, Y", $post_mod_time );
            
            $html .= '</li>';
        }
        $html .= '</ul>';
    }
    return $html;
}
?>