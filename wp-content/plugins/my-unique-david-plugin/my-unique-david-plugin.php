<?php
/**
 * Plugin Name: Plugin Fictional University
 * Description: This is my first plugin
 * Author: Iván David Guzmán Ruiz
 * Version: 1.0
 * Text Domain: domaindavid
 * Domain Path: /languages
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
	const string PLUGIN_SLUG = 'my-unique-david-plugin';

	/**
	 * Settings group name
	 */
	const string SETTINGS_GROUP = 'wordCountPlugin';

	/**
	 * Initialize the plugin by setting up hooks
	 */
	public function __construct() {
		// Add admin pages
		add_action( 'admin_menu', [ $this, 'register_admin_page' ] );

		// Register settings
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Add filter
		add_filter( 'the_content', [ $this, 'is_wrap' ] );
		add_action( 'update_option_wcp_location', [ $this, 'clean_cache_after_location_change' ], 10, 2 );
		add_action( 'init', [ $this, 'language_support' ] );

	}

	public function language_support(): void {
		load_plugin_textdomain( 'domaindavid', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function clean_cache_after_location_change( $old_value, $new_value ): void {
		if ( $old_value !== $new_value ) {
			// Clear the cache for the specific option
			wp_cache_delete( 'wcp_location', 'options' );

			// Clear the cache of transients that might contain affected content
			delete_transient( 'wcp_cached_content' );

			// Clear full page cache of popular plugins

			// W3 Total Cache
			if ( function_exists( 'w3tc_flush_posts' ) ) {
				w3tc_flush_posts();
			}

			// WP Super Cache
			if ( function_exists( 'wp_cache_clear_cache' ) ) {
				wp_cache_clear_cache();
			}

			// WP Rocket
			if ( function_exists( 'rocket_clean_domain' ) ) {
				rocket_clean_domain();
			}

			// LiteSpeed Cache
			if ( class_exists( 'LiteSpeed_Cache_API' ) && method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
				LiteSpeed_Cache_API::purge_all();
			}
		}

	}

	/**
	 * Filter function
	 */
	public function is_wrap( $content ) {
		if ( $this->should_add_word_count_metrics() && $this->is_valid_display_context() ) {
			return $this->create_html( $content );
		}

		return $content;
	}

	/**
	 * Checks if any word count metrics are enabled in the settings
	 *
	 * @return bool Whether any metrics should be displayed
	 */
	private function should_add_word_count_metrics(): bool {
		return
			get_option( 'wcp_character_count', '1' ) ||
			get_option( 'wcp_word_count', '1' ) ||
			get_option( 'wcp_read_time', '1' );
	}

	/**
	 * Determines if the current context is appropriate for displaying metrics
	 *
	 * @return bool Whether the current page context is valid for displaying metrics
	 */
	private function is_valid_display_context(): bool {
		return is_single() && is_main_query();
	}

	/**
	 * Create the HTML output for word count statistics
	 *
	 * @param string $content The post content
	 *
	 * @return string Modified content with statistics
	 */
	public function create_html( $content ): string {
		// Initialize statistics HTML
		$stats_html = '<h3>' . esc_html( get_option( 'wcp_headline', 'Post Statistics' ) ) . '</h3><p>';

		// Calculate word count only if needed
		$word_count = null;
		if ( get_option( 'wcp_word_count', '1' ) || get_option( 'wcp_read_time', '1' ) ) {
			$word_count = str_word_count( strip_tags( $content ) );
		}

		// Add word count if enabled
		if ( get_option( 'wcp_word_count', '1' ) ) {
			$stats_html .= esc_html__( 'This post has:', 'domaindavid' ) . ' ' . number_format( $word_count ) . ' ' . esc_html__( 'words.', 'domaindavid' ) . '<br>';
		}

		// Add character count if enabled
		if ( get_option( 'wcp_character_count', '1' ) ) {
			$char_count = strlen( strip_tags( $content ) );
			$stats_html .= 'This post has: ' . number_format( $char_count ) . ' characters.<br>';
		}

		// Add reading time if enabled
		if ( get_option( 'wcp_read_time', '1' ) && $word_count ) {
			// Average reading speed is about 225 words per minute
			$minutes     = ceil( $word_count / 225 );
			$time_string = $minutes === 1 ? 'minute' : 'minutes';
			$stats_html  .= 'Estimated reading time: ' . $minutes . ' ' . $time_string . '.<br>';
		}

		// Close paragraph tag
		$stats_html .= '</p>';

		// Handle different location settings
		$location = get_option( 'wcp_location', '0' );

		// 0 = after content, 1 = before content, 2 = both before and after
		if ( $location === '1' ) {
			return $content . $stats_html;
		} elseif ( $location === '0' ) {
			return $stats_html . $content;
		} else {
			return $stats_html . $content . $stats_html;
		}
	}

	/**
	 * Register the admin settings page
	 */
	public function register_admin_page(): void {
		add_options_page(
			esc_html__( 'Word Count Settings', 'domaindavid' ),  // Page title
			esc_html__( 'Word Count', 'domaindavid' ),          // Menu title
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
				'sanitize_callback' => [ $this, 'sanitizeLocation' ],
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

	public function sanitizeLocation( $input ) {
		if ( $input != '0' and $input != '1' ) {
			add_settings_error(
				'wcp_location',
				'wcp_location_error',
				'Display location must be either beginning or end of post.',
			);

			return get_option( 'wcp_location' );
		}

		return $input;
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
        <label>
            <select name="wcp_location">
                <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ); ?>>Beginning of Post</option>
                <option value="1" <?php selected( get_option( 'wcp_location' ), '1' ); ?>>End of Post</option>
            </select>
        </label>
		<?php
	}

	/**
	 * Render the headline text field
	 */
	public function render_headline_field(): void {
		?>
        <label>
            <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ) ?>">
        </label>
		<?php
	}

	/**
	 * Generic method to render checkbox fields
	 *
	 * @param string $option_name The option name without the 'wcp_' prefix
	 */
	private function render_checkbox_field( string $option_name ): void {
		$full_option_name = "wcp_{$option_name}";
		?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr( $full_option_name ); ?>"
                   value="1" <?php checked( get_option( $full_option_name ), '1' ); ?>>
        </label>
		<?php
	}

	/**
	 * Render the word count field
	 */
	public function render_word_count_field(): void {
		$this->render_checkbox_field( 'word_count' );
	}

	/**
	 * Render the character count field
	 */
	public function render_character_count_field(): void {
		$this->render_checkbox_field( 'character_count' );
	}

	/**
	 * Render the read time field
	 */
	public function render_read_time_field(): void {
		$this->render_checkbox_field( 'read_time' );
	}
}

// Initialize the plugin
$word_count_plugin = new Word_Count_Plugin();