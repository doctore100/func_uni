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
		add_action(
                'init',
                [ $this, 'admin_assets' ]
        );
	}

	public function admin_assets(): void {
		wp_register_style(
			'edit-css',
			plugin_dir_url( __FILE__ ) . 'build/index.css'
		);

		wp_register_script(
			'block-react-example-block',
			plugin_dir_url( __FILE__ ) . 'build/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ],
			'1.0.0',
			true
		);

		register_block_type(
			'block-example/block-react-example',
			[
				'editor_style' => 'edit-css',
				'editor_script' => 'block-react-example-block',
				'render_callback' => [ $this, 'render_block' ]
			]
		);
	}

	public function render_block( $attributes ): string {
		wp_enqueue_script(
			'FrontEndScript',
			plugin_dir_url( __FILE__ ) . 'build/FrontEnd.js',
			[ 'wp-element', 'react-jsx-runtime', 'wp-components' ],
		);
		wp_enqueue_style(
			'FrontEndStyles',
			plugin_dir_url( __FILE__ ) . 'build/FrontEnd.css',
            [ 'wp-element', 'react-jsx-runtime', 'wp-components']
		);

		ob_start(); ?>
        <div class="paying-attention-me">
            <pre style="display: none"><?php echo wp_json_encode( $attributes ) ?></pre>
        </div>
		<?php return ob_get_clean();
	}
}

$block_react_example = new Block_React_Example();