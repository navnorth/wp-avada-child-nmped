<?php

function out_of_date_notification($posts, $age = 90) {
    $html = "<p>The following pages on the NMPED Site have not been updated in the last ". $age ." days: </p>";

    if ($posts){
        $html .= '<table><thead><tr>';
        $html .= '<th scope="col" style="text-align:left;width:50%">Page</th>';
        $html .= '<th scope="col" style="text-align:left;width:25%">Last Author</th>';
        $html .= '<th scope="col" style="text-align:left;width:25%">Last Modified</th>';
        $html .= '</tr></thead><tbody>';

        foreach($posts as $post){
            $html .= '<tr>';

            $url = get_permalink($post->ID);

            $author = get_userdata($post->post_author);

            $author_name = $author->display_name;
            if ($author->first_name!=="" || $author->last_name!=="")
                $author_name = $author->first_name . " " . $author->last_name;

            $post_mod_time = strtotime($post->post_modified);

            $html .= '<td><a href="' . $url . '">' . $post->post_title . '</a></td>';
            $html .= '<td>' . $author_name . '</td>';
            $html .= '<td>' . date( "F j, Y", $post_mod_time ) . '</td>';

            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
    }
    return $html;
}
?>
