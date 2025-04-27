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


		register_block_type(
			__DIR__,
			[

				'render_callback' => [ $this, 'render_block' ]
			]
		);
	}

	public function render_block( $attributes ): string {


		ob_start(); ?>
        <div class="paying-attention-me">
            <pre style="display: none"><?php echo wp_json_encode( $attributes ) ?></pre>
        </div>
		<?php return ob_get_clean();
	}
}

$block_react_example = new Block_React_Example();