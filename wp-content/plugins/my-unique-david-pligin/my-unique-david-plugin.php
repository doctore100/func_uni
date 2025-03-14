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
			array( $this, 'settingsInit' )
		);
	}

	function settingsInit() {
		add_settings_section(
			'wpc_first_section',
			'Word Count',
			array( $this, 'sectionHTML' ),
			'my-unique-david-plugin'
		);

		add_settings_field(
			'wcp_location',
			'Display Location',
			array( $this, 'locationHTML' ),
			'my-unique-david-plugin',
			'wcp_first_section'
		);

		register_setting(
			'wordCountPlugin',
			'wcp_location',
			array(
				$this,
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0'
			)
		);
	}

	function adminPage( $content ): void {
		add_options_page(
			'Word Count page',
			'Word Count',
			'manage_options',
			'my-unique-david-plugin',
			array( $this, 'ourHTML' )
		);
	}

	function ourHTML() {
		?>
        echo "Hello ClassLoader";
	<?php }
}

$wordCountUniquePlugin = new wordCountUniquePlugin();

