<?php

add_action('init', 'university_post_types');
function university_post_types()
{

    //	Events Post Type
    $labelsEvent = array(
        'name' => __('Events', 'Post Type General Name'),
        'singular_name' => __('Event', 'Post Type Singular Name'),
        'menu_name' => __('Events', 'university'),
        'name_admin_bar' => __('Event', 'university'),
        'all_items' => __('All Events', 'university'),
        'add_new_item' => __('Add New Event', 'university'),
        'add_new' => __('Add New Event', 'university'),
        'new_item' => __('New Event', 'university'),
        'edit_item' => __('Edit Event', 'university'),
        'update_item' => __('Update Event', 'university'),
        'view_item' => __('View Event', 'university'),
        'view_items' => __('View Events', 'university')

    );
    register_post_type('event', args: array(
        'rewrite' => array('slug' => 'events'),
        'capability_type' => 'event',
        'map_meta_cap' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar',
        'labels' => $labelsEvent,

    ));


    //	Program Post Type
    $labelsProgram = array(
        'name' => __('Programs', 'Post Type General Name'),
        'singular_name' => __('Program', 'Post Type Singular Name'),
        'menu_name' => __('Programs', 'university'),
        'name_admin_bar' => __('Program', 'university'),
        'all_items' => __('All Programs', 'university'),
        'add_new_item' => __('Add New Program', 'university'),
        'add_new' => __('Add New Program', 'university'),
        'new_item' => __('New Program', 'university'),
        'edit_item' => __('Edit Program', 'university'),
        'update_item' => __('Update Program', 'university'),
        'view_item' => __('View Program', 'university'),
        'view_items' => __('View Programs', 'university')

    );
    register_post_type('program', array(
        'rewrite' => array('slug' => 'programs'),
        'supports' => array('title'),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-awards',
        'labels' => $labelsProgram

    ));


    //	Professor Post Type
    $labelsProfessor = array(
        'name' => __('Professor', 'Post Type General Name'),
        'singular_name' => __('Professor', 'Post Type Singular Name'),
        'menu_name' => __('Professors', 'university'),
        'name_admin_bar' => __('Professor', 'university'),
        'all_items' => __('All Professors', 'university'),
        'add_new_item' => __('Add New Professor', 'university'),
        'add_new' => __('Add New Professor', 'university'),
        'new_item' => __('New Professor', 'university'),
        'edit_item' => __('Edit Professor', 'university'),
        'update_item' => __('Update Professor', 'university'),
        'view_item' => __('View Professor', 'university'),
        'view_items' => __('View Professors', 'university')

    );
    register_post_type('professor', array(
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt',),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'labels' => $labelsProfessor,

    ));

    //	Campus Post-Type
    $labelsCampus = array(
        'name' => __('Campus', 'Post Type General Name'),
        'singular_name' => __('Campus', 'Post Type Singular Name'),
        'menu_name' => __('Campus', 'university'),
        'name_admin_bar' => __('Campus', 'university'),
        'all_items' => __('All Campuses', 'university'),
        'add_new_item' => __('Add New Campus', 'university'),
        'add_new' => __('Add New Campus', 'university'),
        'new_item' => __('New Campus', 'university'),
        'edit_item' => __('Edit Campus', 'university'),
        'update_item' => __('Update Campus', 'university'),
        'view_item' => __('View Campus', 'university'),
        'view_items' => __('View Campuses', 'university')

    );
    register_post_type('campus', array(
        'rewrite' => array('slug' => 'campuses'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-location-alt',
        'labels' => $labelsCampus,

    ));

    //	My Note Post-Type
    $labelsNote = array(
        'name' => __('Notes', 'Post Type General Name'),
        'singular_name' => __('Note', 'Post Type Singular Name'),
        'menu_name' => __('Notes', 'university'),
        'name_admin_bar' => __('Note', 'university'),
        'all_items' => __('All Notes', 'university'),
        'add_new_item' => __('Add New Note', 'university'),
        'add_new' => __('Add New Note', 'university'),
        'new_item' => __('New Note', 'university'),
        'edit_item' => __('Edit Note', 'university'),
        'update_item' => __('Update Note', 'university'),
        'view_item' => __('View Note', 'university'),
        'view_items' => __('View Notes', 'university')

    );
    register_post_type('note', array(
        'supports' => array('title', 'editor', 'excerpt'),
		'capability_type' => 'note',
		'map_meta_cap' => true,
        'public' => false,
        'show_ui'=> true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'labels' => $labelsNote,

    ));

	//	Like Post-Type
	$labelsLike = array(
		'name'           => __( 'Likes', 'Post Type General Name' ),
		'singular_name'  => __( 'Like', 'Post Type Singular Name' ),
		'menu_name'      => __( 'Likes', 'university' ),
		'name_admin_bar' => __( 'Like', 'university' ),
		'all_items'      => __( 'All Likes', 'university' ),
		'add_new_item'   => __( 'Add New Like', 'university' ),
		'add_new'        => __( 'Add New Like', 'university' ),
		'new_item'       => __( 'New Like', 'university' ),
		'edit_item'      => __( 'Edit Like', 'university' ),
		'update_item'    => __( 'Update Like', 'university' ),
		'view_item'      => __( 'View Like', 'university' ),
		'view_items'     => __( 'View Likes', 'university' )
	);

	register_post_type( 'like', array(
		'supports'        => array( 'title' ),
		'public'          => false,
		'show_ui'         => true,
		'menu_icon'       => 'dashicons-heart',
		'labels'          => $labelsLike,
	) );
}

