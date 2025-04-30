<?php

/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Ivan David Guzman Ruiz
  Author URI: https://www.udemy.com/user/bradschiff/
*/

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
require_once plugin_dir_path( __FILE__ ) . 'inc/generateProfessorHtml.php';

class FeaturedProfessor {
	function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'rest_api_init', [ $this, 'professor_editor_html' ] );
	}

	function register_block(): void {
		wp_register_script( 'featuredProfessorScript', plugin_dir_url( __FILE__ ) . 'build/index.js', array(
			'wp-blocks',
			'wp-i18n',
			'wp-editor'
		) );
		wp_register_style( 'featuredProfessorStyle', plugin_dir_url( __FILE__ ) . 'build/index.css' );

		register_block_type( 'ourplugin/featured-professor', array(
			'render_callback' => [ $this, 'render_callback' ],
			'editor_script' => 'featuredProfessorScript',
			'editor_style' => 'featuredProfessorStyle'
		) );
	}


	function render_callback( $attributes ): ?string {
		if ( isset( $attributes['profId'] ) ) {
			wp_enqueue_style( 'featuredProfessorStyle' );
			return generateProfessorHtml( $attributes['profId'] );

		} else {
			return null;
		}
	}

	function professor_editor_html() {
		register_rest_route( 'featuresProfessor/v1', 'get_html', [
				'methods' => WP_REST_SERVER::READABLE,
				'callback' => [ $this, 'get_prof_html' ]
			]
		);
	}

	function get_prof_html( $data ): string {
		return generateProfessorHtml( $data['profId'] );

	}
}

$featuredProfessor = new FeaturedProfessor();