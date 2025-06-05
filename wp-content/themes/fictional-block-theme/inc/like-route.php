<?php
add_action( 'rest_api_init', 'universityLikeRoute' );

function universityLikeRoute() {
	register_rest_route( 'university/v1', 'manageLike', array(
		'methods'  => 'POST',
		'callback' => 'createLike'
	) );
	register_rest_route( 'university/v1', 'manageLike', array(
		'methods'  => 'DELETE',
		'callback' => 'deleteLike'
	) );
}


function createLike( $data ) {
	// Check if the user is logged in; if not, return an error response
	if ( ! is_user_logged_in() ) {
		return new WP_REST_Response( array(
			'status'  => 'error',
			'message' => 'User must be logged in to create a like.'
		), 403 ); // HTTP 403 Forbidden
	}
	$professorId = sanitize_text_field( $data['professorID'] );
	$likeArray   = array(
		'post_type'  => 'like',
		'meta_query' => array(
			array(
				'key'     => 'liked_professor_id',
				'compare' => '=',
				'value'   => $professorId,
			)
		)
	);
	$likesQuery  = new WP_Query( $likeArray );
	// Sanitize input data
	$insertPost = false;
	// Insert the post
	if ( $likesQuery->found_posts == 0 and get_post_type( $professorId ) == 'professor' ) {
		$insertPost = wp_insert_post( array(
			'post_type'   => 'like',
			'post_status' => 'publish',
			'post_title'  => 'Tests Professor Like',
			'meta_input'  => array(
				'liked_professor_id' => $professorId
			)
		) );

	}


	// Handle success or failure response
	if ( $insertPost ) {
		return new WP_REST_Response( array(
			'status'      => 'success',
			'message'     => 'Like successfully created.',
			'likeID'      => $insertPost,
			'professorID' => $professorId
		), 201 ); // HTTP 201 Created
	}

	return new WP_REST_Response( array(
		'status'  => 'error',
		'message' => 'There was an error creating the Like.'
	), 500 ); // HTTP 500 Internal Server Error
}

function deleteLike($data) {
	// Sanitize the input
	$likeId = sanitize_text_field($data['like']);

	// Verify if the current post type is "like" and the user is the author of the like
	if (get_post_type($likeId) === 'like' && get_current_user_id() === (int)get_post_field('post_author', $likeId)) {
		// Delete the post
		wp_delete_post($likeId, true);

		// Return success response
		return new WP_REST_Response(
			[
				'status'  => 'success',
				'message' => 'Like successfully deleted.',
				'likeID'  => $likeId,
			],
			200 // HTTP OK
		);
	}

	// Return error response if the deletion is not allowed
	return new WP_REST_Response(
		[
			'status'  => 'error',
			'message' => 'You are not allowed to delete this like.',
		],
		403 // HTTP Forbidden
	);
}