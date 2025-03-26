<?php
/**
 * Plugin Name: Plugin for create block
 * Description: This is a plugin used to create custom WordPress blocks.
 * Author: Iván David Guzmán Ruiz
 * Version: 1.0
 */

// Prevent direct access to the plugin file
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Block_React_Example {
	function __construct() {
		add_action('init', array($this, 'admin_assets'));
	}
	public function admin_assets(): void {
		wp_register_script( 'block-react-example-block', plugin_dir_url(__FILE__ ). 'build/index.js', ['wp-blocks', 'wp-element'],  );
		register_block_type('block-example/block-react-example', [
			'editor_script' => 'block-react-example-block',
			'render_callback' => [$this, 'render_block']
		]);
	}
	public function render_block($attributes): string{
		return '<h1>The sky is ' . $attributes['skyColor'] . 'and the grass is ' . $attributes['grassColor']. 'color </h1>';
	}
}
$block_react_example = new Block_React_Example();