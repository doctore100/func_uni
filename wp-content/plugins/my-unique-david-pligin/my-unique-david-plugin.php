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
		/**
		 * Registers the 'wcp_location' setting and adds a settings field for it.
		 */
		register_setting(
			'wordCountPlugin',
			'wcp_location',
			array(
				$this,
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0'
			)
		);

		/**
		 * Adds a settings field for the 'wcp_location' option.
		 *
		 * @param string $id The field ID.
		 * @param string $title The title of the field.
		 * @param callable $callback Function to generate the field's HTML.
		 * @param string $page The menu page.
		 * @param string $section The section on the menu page.
		 */
		add_settings_field(
			'wcp_location',
			'Display Location',
			array( $this, 'locationHTML' ),
			'my-unique-david-plugin',
			'wcp_first_section'
		);


		/**
		 * Registers the 'wcp_headline' setting and adds a settings field for it.
		 */
		register_setting(
			'wordCountPlugin',  // The settings group name.
			'wcp_headline',     // The option name in the database.
			array(
				'sanitize_callback' => 'sanitize_text_field', // Callback to sanitize the field input.
				'default'           => '0'                   // Default value for the setting.
			)
		);

		/**
		 * Adds a settings field for the 'wcp_headline' option.
		 *
		 * @param string $id The field ID.
		 * @param string $title The title of the field displayed in the UI.
		 * @param callable $callback Function to generate the field's HTML.
		 * @param string $page The menu page where the settings field is displayed.
		 * @param string $section The section on the menu page where the field is displayed.
		 */
		add_settings_field(
			'wcp_headline',            // Field ID.
			'Headline Text',           // Title of the field.
			array( $this, 'headlineHTML' ), // Callback function to render the field's HTML.
			'my-unique-david-plugin',  // Page on which to display the field.
			'wcp_first_section'        // Section to place the field in.
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
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ) ?>>Beginning of Post</option>
            <option value="1"<?php selected( get_option( 'wcp_location' ), '1' ) ?>>End of Post</option>
        </select>
		<?php
	}
    function headlineHTML() {
	    ?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ) ?>>Beginning of Post</option>
            <option value="1"<?php selected( get_option( 'wcp_location' ), '1' ) ?>>End of Post</option>
        </select>
	    <?php
    }
}

$wordCountUniquePlugin = new wordCountUniquePlugin();

