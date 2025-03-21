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


	/**
	 * Constructor - initialize hooks
	 */
	public function __construct() {
		// Register the admin menu
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		// Clean cache
//		add_action( 'update_option_wcp_location', [ $this, 'clean_cache_after_location_change' ], 10, 2 );

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
			self::PLUGIN_SLUG_OPTIONS,        // Menu slug
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
		wp_enqueue_style( 'filter_admin_css', plugins_url( __FILE__ . "css/admin.css" ) );
	}

	/**
	 * Render the main plugin page
	 */
	public function render_main_page(): void {
		?>
        <section>
            <h1> Word Filter</h1>
            <form method="post">
                <label for="plugin_word_to_filter">Enter <strong>separate by coma</strong> the word you want to
                    fiend</label>
                <div class="word-filter_flex-container">
                    <textarea name="plugin_word_to_filter" id="plugin_word_to_filter"
                              placeholder="main, bock, teacher"></textarea>
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
            <h1>Word Filter Options</h1>
            <p>Configure your word filter options here.</p>
            <!-- Options form would go here -->
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