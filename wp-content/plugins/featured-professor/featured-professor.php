<?php

/*
	Plugin Name: Featured Professor Block Type
	Version: 1.0
	Author: Ivan David Guzman Ruiz
	Author URI: https://www.udemy.com/user/bradschiff/
	Text Domain: features-professor
	Domain Path: /languages

*/

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
require_once plugin_dir_path( __FILE__ ) . 'inc/generateProfessorHtml.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/related-post-html.php';

class FeaturedProfessor {
	function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'rest_api_init', [ $this, 'professor_editor_html' ] );
		add_filter( 'the_content', [ $this, 'add_related_post' ] );
	}

	function add_related_post( $content ) {
		if ( is_singular( 'professor' ) and in_the_loop() and is_main_query() ) {
			return $content . related_post_html( get_the_ID() );

		}
		return $content;
	}

	function register_block(): void {
		load_plugin_textdomain(
			'featured-professor',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		register_meta(
			'post',
			'featuredProfessor',
			[
				'show_in_rest' => true,
				'type' => 'number',
				'single' => false,
			]
		);
		wp_register_script(
			'featuredProfessorScript',
			plugin_dir_url( __FILE__ ) . 'build/index.js',
			[
				'wp-blocks',
				'wp-i18n',
				'wp-editor'
			]

		);
		wp_register_style(
			'featuredProfessorStyle',
			plugin_dir_url( __FILE__ ) . 'build/index.css' );

		register_block_type(
			'ourplugin/featured-professor', array(
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

	function professor_editor_html(): void {
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