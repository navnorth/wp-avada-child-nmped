<?php

/**
 * For advanced users:
 *
 * Place any custom WordPress hooks or theme functions into this file.
 *
 * This file is optional and can be omitted from your custom theme.
 */
add_filter( 'ai1ec_theme_args_event-single.twig', 'nmped_event_single_args', 10, 2 );

function nmped_event_single_args($args, $is_admin){
    $contact      = '<ul class="h-card">';
    $has_contents = false;
    if ( $args['event']->get( 'contact_name' ) ) {
        $contact     .=
        '<li class="ai1ec-contact-name p-name">' .
        '<i class="ai1ec-fa ai1ec-fa-fw ai1ec-fa-user"></i> ' .
        esc_html( $args['event']->get( 'contact_name' ) ) .
        '</li> ';
        $has_contents = true;
    }
    if ( $args['event']->get( 'contact_phone' ) ) {
        $contact     .=
        '<li class="ai1ec-contact-phone p-tel">' .
        '<i class="ai1ec-fa ai1ec-fa-fw ai1ec-fa-phone"></i> ' .
        esc_html( $args['event']->get( 'contact_phone' ) ) .
        '</li> ';
        $has_contents = true;
    }
    if ( $args['event']->get( 'contact_email' ) ) {
        $contact     .=
        '<li class="ai1ec-contact-email">' .
        '<a class="u-email" href="mailto:' .
        esc_attr( $args['event']->get( 'contact_email' ) ) . '">' .
        '<i class="ai1ec-fa ai1ec-fa-fw ai1ec-fa-envelope-o"></i> ' .
        __( 'Email', AI1EC_PLUGIN_NAME ) . '</a></li> ';
        $has_contents = true;
    }
    if ( $args['event']->get( 'contact_url' ) ) {
        $contact     .=
        '<li class="ai1ec-contact-url">' .
        '<a class="u-url" target="_blank" href="' .
        esc_attr( $args['event']->get( 'contact_url' ) ) .
        '"><i class="ai1ec-fa ai1ec-fa-fw ai1ec-fa-link"></i> ' .
        apply_filters(
            'ai1ec_contact_url',
            __( 'Event website', AI1EC_PLUGIN_NAME )
        ) .
        ' <i class="ai1ec-fa ai1ec-fa-external-link" alt="'.__( 'Event website', AI1EC_PLUGIN_NAME ).'" title="'.__( 'Event website', AI1EC_PLUGIN_NAME ).'"></i></a></li>';
        $has_contents = true;
    }
    $contact .= '</ul>';
    
    $args['contact'] = $has_contents ? $contact : '';
    
    $args['categories'] = get_categories_html($args['post_id']);
    
    return $args;
}

function get_categories_html( $post_id ) {
    $categories = get_the_category($post_id);
	
    if (empty($categories))
        return;
        
    foreach ( $categories as &$category ) {
        
        $href = site_url('/calendar/cat_ids~'.$category->term_id.'/');

        $class = $data_type = $title = '';
        if ( $category->description ) {
            $title = 'title="' .
                esc_attr( $category->description ) . '" ';
        }

        $html        = '';
        $class      .= ' ai1ec-category';
        $color_style = '';

        $html .= '<a ' . $data_type . ' class="' . $class .
        ' ai1ec-term-id-' . $category->term_id . ' p-category" ' .
        $title . $color_style . 'href="' . $href . '">';

        $html .= esc_html( $category->name ) . '</a>';
        $category = $html;
    }
    
    return implode( ' ', $categories );
}

add_filter( 'ai1ec_theme_args_calendar.twig', 'nmped_calendar_args', 10, 2 );

function nmped_calendar_args( $args, $is_admin  ) {
    add_filter( 'body_class', 'add_calendar_class' );
    return $args;
}

function add_calendar_class( $classes ) {
    $classes[] = "nmped-calendar-page";
    return $classes;
}