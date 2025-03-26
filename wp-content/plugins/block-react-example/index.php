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
		add_action('enqueue_block_editor_assets', array($this, 'admin_assets'));
	}
	public function admin_assets(): void {
		wp_enqueue_script( 'block-react-example-block', plugin_dir_url(__FILE__ ). 'build/index.js', ['wp-blocks', 'wp-element'],  );
	}
}
$block_react_example = new Block_React_Example();