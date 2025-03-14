<?php

/*
Plugin Name: Plugin Fictional University
Description: This is my first plugin
Author: Ivan David Guzman Ruiz
Version: 1.0
 */

class wordCountUniquePlugin {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'adminPage' ) );


	}

	function adminPage( $content ): void {
		add_options_page(
			'Word Count page',
			'Word Count',
			'manage_options',
			'my-unique-david-plugin',
			'ourHTML'
		);
	}

	function ourHTML() {
		?>
        echo "Hello World";
	<?php }
}

$wordCountUniquePlugin = new wordCountUniquePlugin();

