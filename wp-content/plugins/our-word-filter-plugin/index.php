<?php
/**
 * Plugin Name: Plugin Fictional University Text Filter
 * Description: This is my second plugin, which is a plugin that uses JavaScript and PHP to create a dynamic filter.
 * Author: Iván David Guzmán Ruiz
 * Version: 1.0
 */

// Prevent direct access to the plugin file
if (!defined('ABSPATH')) {
	exit;
}

class Our_Word_Filter_Plugin {
	// Class constants for menu slugs
	const PLUGIN_SLUG = 'our-word-filter';
	const PLUGIN_SLUG_OPTIONS = 'our-word-filter-options';

	/**
	 * Constructor - initialize hooks
	 */
	public function __construct() {
		// Register the admin menu
		add_action('admin_menu', [$this, 'register_admin_menu']);
        // Clean cache
//		add_action( 'update_option_wcp_location', [ $this, 'clean_cache_after_location_change' ], 10, 2 );

	}

	/**
	 * Register the main menu and submenu pages
	 */
	public function register_admin_menu(): void {
		// Add main menu page
		add_menu_page(
			'Our Word Filter',           // Page title
			'Our Word Filter',           // Menu title
			'manage_options',            // Capability required
			self::PLUGIN_SLUG,           // Menu slug
			[$this, 'render_main_page'], // Callback function
			'dashicons-smiley',          // Icon
			100                          // Position
		);

		// Add submenu page
		add_submenu_page(
			self::PLUGIN_SLUG,                // Parent slug
			'Our Word Filter Options',        // Page title
			'Options',                        // Menu title
			'manage_options',                 // Capability required
			self::PLUGIN_SLUG_OPTIONS,        // Menu slug
			[$this, 'render_options_page']    // Callback function
		);
	}

	/**
	 * Render the main plugin page
	 */
	public function render_main_page(): void {
		?>
        <div class="wrap">
            <h1>Our Word Filter</h1>
            <p>This is my second plugin, which is a plugin that uses JavaScript and PHP to create a dynamic filter.</p>
        </div>
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