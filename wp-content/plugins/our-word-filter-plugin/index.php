<?php
/**
 * Plugin Name: Plugin Fictional University Text Filter
 * Description: This is my second plugin, which is a plugin that uses JavaScript and PHP to create a dynamic filter.
 * Author: Iván David Guzmán Ruiz
 * Version: 1.0
 */

// Prevent direct access to the plugin file
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Our_Word_Filter_Plugin {
	// Class constants for menu slugs
	const string PLUGIN_SLUG = 'our-word-filter';
	const string PLUGIN_SLUG_OPTIONS = 'our-word-filter-options';
	// Size icon 20x20 px
	const string SVG_ICON = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+';

	const string SECTION_ID = 'replace-text-section';

	/**
	 * Constructor - initialize hooks
	 */
	public function __construct() {
		// Register the admin menu
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		if ( get_option( 'plugin_word_to_filter' ) ) {
			add_filter( 'the_content', [ $this, 'filter_logic' ] );
		}


	}

	/**
	 * Register settings sections for the plugin options page.
	 *
	 * This method creates a settings section where plugin options
	 * will be registered. The section has no title or callback
	 * as these will be handled by individual settings fields.
	 */
	public function register_settings(): void {
		add_settings_section(
			self::SECTION_ID,       // ID of the settings section
			null,             // No section title needed
			null,             // No section callback needed
			self::PLUGIN_SLUG_OPTIONS  // Page slug where to show the section
		);
		register_setting(
			'replace-field',
			'replace-text'
		);
		add_settings_field(
			'replace-text-field',
			'Word to filter',
			[ $this, 'replace_field_html' ],
			self::PLUGIN_SLUG_OPTIONS,
			self::SECTION_ID
		);
	}

	public function replace_field_html(): void {
		$replace_text = get_option( 'replace-text', '***' );
		?>
        <label for="replace_text_id"></label>
        <input id="replace_text_id" type="text" name="replace-text" value="<?php echo esc_attr( $replace_text ); ?>">
        <p class="description">Leave blank to simply remove the filter word</p>
		<?php
	}


	public function filter_logic( $content ): string {
		$bad_words = explode( ',', get_option( 'plugin_word_to_filter' ) );
		$bad_words_trim = array_map( 'trim', $bad_words );
		$replacement = esc_html( get_option( "replace-text", "***" ) );
		return str_ireplace( $bad_words_trim, $replacement, $content );
	}


	/**
	 * Register the main menu and submenu pages
	 */
	public function register_admin_menu(): void {
		// Add main menu page

		$main_page_hook = add_menu_page(
			'Our Word Filter',           // Page title
			'Our Word Filter',           // Menu title
			'manage_options',            // Capability required
			self::PLUGIN_SLUG,           // Menu slug
			[ $this, 'render_main_page' ], // Callback function
			self::SVG_ICON,          // Icon
			100                          // Position
		);

		// Add submenu page
		add_submenu_page(
			self::PLUGIN_SLUG,                // Parent slug
			'Word to Filter',        // Page title
			'Word List',                        // Menu title
			'manage_options',                 // Capability required
			self::PLUGIN_SLUG,        // Menu slug
			[ $this, 'render_main_page' ]    // Callback function
		);

		// Add submenu page
		add_submenu_page(
			self::PLUGIN_SLUG,                // Parent slug
			'Our Word Filter Options',        // Page title
			'Options',                        // Menu title
			'manage_options',                 // Capability required
			self::PLUGIN_SLUG_OPTIONS,        // Menu slug
			[ $this, 'render_options_page' ]    // Callback function
		);
		add_action( "load-{$main_page_hook}", [ $this, 'render_main_page_assets' ] );
	}

	public function render_main_page_assets(): void {
		wp_enqueue_style( 'filterAdminCss', plugin_dir_url( __FILE__ ) . "css/admin.css" );
	}

	public function handle_submit(): void {
		if ( wp_verify_nonce( $_POST['plugin_word_filter_nonce'], 'plugin_word_filter_action' ) && current_user_can( 'manage_options' ) ) {
			update_option( 'plugin_word_to_filter', sanitize_text_field( trim( $_POST['plugin_word_to_filter'] ) ) ); ?>
            <div class="updated">
                <p>The word has been saved</p>
            </div>
		<?php } else { ?>
            <div class="error">
                <p>Sorry you don't have permission for that action</p>
            </div>

		<?php }

	}

	/**
	 * Render the main plugin page
	 */
	public function render_main_page(): void {
		?>
        <section class="wrap">
            <h1> Word Filter</h1>
			<?php
			$is_form_submitted = isset( $_POST['form_submitted'] ) && $_POST['form_submitted'] === 'true';
			if ( $is_form_submitted ) {
				$this->handle_submit();
			} ?>
            <p>This is the main page of the plugin</p>
            <form method="post">
                <input type="hidden" name="form_submitted" value="true">

				<?php wp_nonce_field( 'plugin_word_filter_action', 'plugin_word_filter_nonce' ); ?>
                <label for="plugin_word_to_filter">
                    Enter <strong>separate by coma</strong> the word you want to fiend
                </label>
                <div class="word-filter_flex-container">
                    <textarea name="plugin_word_to_filter" id="plugin_word_to_filter"
                              placeholder="main, bock, teacher"><?php echo esc_textarea( trim( get_option( "plugin_word_to_filter" ) ) ); ?></textarea>
                </div>
                <input type="submit" value="Save Changes" id="submit" class="button button-primary">
            </form>
        </section>
		<?php
	}

	/**
	 * Render the options page
	 */
	public function render_options_page(): void {
		?>
        <div class="wrap">

            <!-- Options form would go here -->
            <div class="warp">
                <h1>Word Filter Options</h1>
                <p>Configure your word filter options here.</p>
                <form action="options.php" method="post">
					<?php
					settings_fields( 'replace-field' );
					do_settings_sections( self::PLUGIN_SLUG_OPTIONS );
					submit_button(); ?>

                </form>

            </div>
        </div>
		<?php
	}

	/**
	 * Initialize the plugin
	 */
	public static function init(): self {
		return new self();
	}
}

// Initialize the plugin
$our_word_filter_plugin = Our_Word_Filter_Plugin::init();