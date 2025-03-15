<?php

/*
Plugin Name: Plugin Fictional University
Description: This is my first plugin
Author: Ivan David Guzman Ruiz
Version: 1.0
 */

class wordCountUniquePlugin {
	function __construct() {
		add_action(
			'admin_menu',
			array( $this, 'adminPage' )
		);

		add_action(
			'admin_init',
			array( $this, 'settings' )
		);
	}

	function adminPage(): void {
		add_options_page(
			'Word Count page',
			'Word Count',
			'manage_options',
			'my-unique-david-plugin',
			array( $this, 'ourHTML' )
		);
	}

	function settings(): void {
		register_setting(
			'wordCountPlugin',
			'wcp_location',
			array(
				$this,
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0'
			)
		);

		add_settings_field(
			'wcp_location',
			'Display Location',
			array( $this, 'locationHTML' ),
			'my-unique-david-plugin',
			'wcp_first_section'
		);

		add_settings_section(
			'wcp_first_section',
			null,
			null,
			'my-unique-david-plugin'
		);


	}

	function ourHTML() {
		?>
        <div class="warp">
            <h1>Word Count Setting</h1>
            <form action="options.php" method="POST">
				<?php
				do_settings_sections( 'my-unique-david-plugin' );
				settings_fields( 'wordCountPlugin' );
				submit_button();
				?>
            </form>
        </div>
	<?php }

	function locationHTML() {
		?>
        <select name="wcp_location">
            <option value="0">Beginning of Post</option>
            <option value="1">End of Post</option>
        </select>
		<?php
	}
}

$wordCountUniquePlugin = new wordCountUniquePlugin();

