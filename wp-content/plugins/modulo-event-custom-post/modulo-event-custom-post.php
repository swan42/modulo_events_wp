<?php
/*
Plugin Name: Modulo Event Custom Post
Description: Allow creation of custom posts to manage different types of real life events and gatherings related to Modulo business
Author: Swan MONTIEL
Text Domain: moduloeventcustompost
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action('admin_enqueue_scripts', 'my_enqueue');
add_action( 'init', 'modulo_event_custom_post_type' );
add_action( 'add_meta_boxes', 'modulo_event_box' );
add_action( 'save_post', 'modulo_event_box_save' );

function my_enqueue($hook) {
    // Only add to the post.php admin page.
    if ('post.php' !== $hook) {
        return;
    }
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/myscript.js');
}


function modulo_event_box() {
    add_meta_box( 
        'modulo_event_type',
        'Type',
        'modulo_event_box_type_content',
        'modulo-event',
        'side',
        'high'
    );

    add_meta_box( 
        'modulo_event_organizer',
        'Organisateur',
        'modulo_event_box_organizer_content',
        'modulo-event',
        'side',
        'high'
    );

    add_meta_box( 
        'modulo_event_start_date',
        'Date de début',
        'modulo_event_box_start_date_content',
        'modulo-event',
        'side',
        'high'
    );

    add_meta_box( 
        'modulo_event_end_date',
        'Date de fin',
        'modulo_event_box_end_date_content',
        'modulo-event',
        'side',
        'high'
    );
}

function modulo_event_box_type_content( $post) {
    $modulo_event_type = get_post_meta($post->ID, 'modulo_event_type', true);

    $option_values = array("conference"=>"Conférence", "table_ronde"=>"Table ronde",
                           "spectacle"=>"Spectacle", "concert"=>"Concert" , "debat"=>"Débat");

    $output = '<label for="modulo_event_type"></label>';
    $output .= '<select id="modulo_event_type" name="modulo_event_type">';

    foreach($option_values as $key => $value) 
    {
        if ($key == $modulo_event_type)
        {
            $output .= '<option value="'. $key . '" selected>' . $value . '</option>';
        }
        else
        {
            $output .= '<option value="'. $key . '" >' . $value . '</option>';
        }
    }

    $output .= '</select>';

    echo $output;
}

function modulo_event_box_organizer_content( $post) {
    global $wpdb;

    $modulo_event_organizer = get_post_meta($post->ID, 'modulo_event_organizer', true);

    // We get the event organizers from the "modulo_event_organizers" table in the wordpress database
    $myrows = $wpdb->get_results( "SELECT name FROM modulo_event_organizers" );

    $output = '<label for="modulo_event_organizer"></label>';
    $output .= '<select id="modulo_event_organizer" name="modulo_event_organizer">';

    foreach($myrows as $row) 
    {
        $organizer_name = $row->name;

        if ($organizer_name == $modulo_event_organizer)
        {
            $output .= '<option value="'. $organizer_name . '" selected>' . $organizer_name . '</option>';
        }
        else
        {
            $output .= '<option value="'. $organizer_name . '" >' . $organizer_name . '</option>';
        }
    }

    $output .= '</select>';

    echo $output;
}

function modulo_event_box_start_date_content( $post) {
    $modulo_event_start_date = get_post_meta($post->ID, 'modulo_event_start_date', true);
    
    $output = '<label for="modulo_event_start_date"></label>';
    $output .= '<input type="text" class="custom_date" name="modulo_event_start_date" value="' . date_format($modulo_event_start_date, 'd-m-Y') . '"/>';

    echo $output;
}

function modulo_event_box_end_date_content( $post) {
    $modulo_event_end_date = get_post_meta($post->ID, 'modulo_event_end_date', true);

    $output = '<label for="modulo_event_end_date"></label>';
    $output .= '<input type="text" class="custom_date" name="modulo_event_end_date" value="' . date_format($modulo_event_end_date, 'd-m-Y') . '"/>';

    echo $output;
}

function modulo_event_box_save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;
  
    if ( 'page' == $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_page', $post_id ) )
      return;
    } else {
      if ( !current_user_can( 'edit_post', $post_id ) )
      return;
    }

    // We store the event type
    $modulo_event_type = $_POST['modulo_event_type'];
    update_post_meta( $post_id, 'modulo_event_type', $modulo_event_type );

    // The event start organizer 
    $modulo_event_organizer = $_POST['modulo_event_organizer'];
    update_post_meta( $post_id, 'modulo_event_organizer', $modulo_event_organizer );
    

    // The event start date 
    $modulo_event_start_date = date_create_from_format('d-m-Y', $_POST['modulo_event_start_date']);
    update_post_meta( $post_id, 'modulo_event_start_date', $modulo_event_start_date );

     // The event end date 
     $modulo_event_end_date = date_create_from_format('d-m-Y', $_POST['modulo_event_end_date']);
     update_post_meta( $post_id, 'modulo_event_end_date', $modulo_event_end_date );

     // TODO : check if end date is later or same as start date
    
}

function modulo_event_custom_post_type () {

    register_taxonomy_for_object_type('category', 'modulo-event'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'modulo-event');
    register_post_type('modulo-event', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Evènements', 'html5blank'), // Rename these to suit
            'singular_name' => __('Modulo Event Custom Post', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __("Ajout d'un événement", 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Modulo Event Custom Post', 'html5blank'),
            'new_item' => __('New Modulo Event Custom Post', 'html5blank'),
            'view' => __('View Modulo Event Custom Posts', 'html5blank'),
            'view_item' => __('View Modulo Event Custom Post', 'html5blank'),
            'search_items' => __('Search Modulo Event Custom Post', 'html5blank'),
            'not_found' => __('No Modulo Event Custom Posts found', 'html5blank'),
            'not_found_in_trash' => __('Modulo Event Custom Posts found in Trash', 'html5blank')
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

?>