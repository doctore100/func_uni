<?php
/**
 * Plugin Name: Plugin Fictional University
 * Description: This is my first plugin
 * Author: Ivan David Guzman Ruiz
 * Version: 1.0
 */

/**
 * Class Word_Count_Plugin
 *
 * A simple WordPress plugin to display word count statistics.
 */
class Word_Count_Plugin {
	/**
	 * Plugin slug used for settings
	 */
	const PLUGIN_SLUG = 'my-unique-david-plugin';

	/**
	 * Settings group name
	 */
	const SETTINGS_GROUP = 'wordCountPlugin';

	/**
	 * Initialize the plugin by setting up hooks
	 */
	public function __construct() {
		// Add admin pages
		add_action( 'admin_menu', [ $this, 'register_admin_page' ] );

		// Register settings
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register the admin settings page
	 */
	public function register_admin_page(): void {
		add_options_page(
			'Word Count Settings',  // Page title
			'Word Count',          // Menu title
			'manage_options',      // Capability
			self::PLUGIN_SLUG,     // Menu slug
			[ $this, 'render_admin_page' ] // Callback
		);
	}

	/**
	 * Register all plugin settings
	 */
	public function register_settings(): void {
		// First add the settings section
		add_settings_section(
			'wcp_settings_section',
			null,
			null,
			self::PLUGIN_SLUG
		);

		// Register location setting
		register_setting(
			self::SETTINGS_GROUP,
			'wcp_location',
			[
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '0'
			]
		);

		add_settings_field(
			'wcp_location',
			'Display Location',
			[ $this, 'render_location_field' ],
			self::PLUGIN_SLUG,
			'wcp_settings_section'
		);

		// Register headline setting
		register_setting(
			self::SETTINGS_GROUP,
			'wcp_headline',
			[
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => 'Post Statistics'
			]
		);

		add_settings_field(
			'wcp_headline',
			'Headline Text',
			[ $this, 'render_headline_field' ],
			self::PLUGIN_SLUG,
			'wcp_settings_section'
		);

		// Register word count setting
		register_setting(
			self::SETTINGS_GROUP,
			'wcp_word_count',
			[
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '1'
			]
		);

		add_settings_field(
			'wcp_word_count',
			'Word Count',
			[ $this, 'render_word_count_field' ],
			self::PLUGIN_SLUG,
			'wcp_settings_section'
		);
		// Register Character Count
		register_setting(
			self::SETTINGS_GROUP,
			'wcp_character_count',
			[
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '1'
			]
		);
		add_settings_field(
			'wcp_character_count',
			'Character Count',
			[ $this, 'render_character_count_field' ],
			self::PLUGIN_SLUG,
			'wcp_settings_section'
		);
		// Register Read Time
		register_setting(
			self::SETTINGS_GROUP,
			'wcp_read_time',
			[
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '1'
			]
		);
		add_settings_field(
			'wcp_read_time',
			'Read Time',
			[ $this, 'render_read_time_field' ],
			self::PLUGIN_SLUG,
			'wcp_settings_section'
		);

	}

	/**
	 * Render the admin settings page
	 */
	public function render_admin_page(): void {
		?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
				<?php
				settings_fields( self::SETTINGS_GROUP );
				do_settings_sections( self::PLUGIN_SLUG );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Render the location dropdown field
	 */
	public function render_location_field(): void {
		?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ); ?>>Beginning of Post</option>
            <option value="1" <?php selected( get_option( 'wcp_location' ), '1' ); ?>>End of Post</option>
        </select>
		<?php
	}

	/**
	 * Render the headline text field
	 */
	public function render_headline_field(): void {
		?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ); ?>">
		<?php
	}

	/**
	 * Render the word count field
	 */
	public function render_word_count_field(): void {
		?>
        <input type="checkbox" name="wcp_word_count" value="1" <?php checked( get_option( 'wcp_word_count' ), '1' ); ?>>
		<?php
	}

	public function render_character_count_field(): void {
		?>
        <input type="checkbox" name="wcp_character_count"
               value="1" <?php checked( get_option( 'wcp_character_count' ), '1' ); ?>>
	<?php }

	public function render_read_time_field(): void {
		?>
        <input type="checkbox" name="wcp_read_time" value="1" <?php checked( get_option( 'wcp_read_time' ), '1' ); ?>>
	<?php }


}

// Initialize the plugin
$word_count_plugin = new Word_Count_Plugin();