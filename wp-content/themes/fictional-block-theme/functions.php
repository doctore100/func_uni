<?php
require get_theme_file_path() . '/inc/search-route.php';
require get_theme_file_path() . '/inc/like-route.php';

add_action( 'rest_api_init', 'university_custom_rest' );
function university_custom_rest() {
	register_rest_field( 'post', 'authorName',
		array(
			'get_callback' => function () {
				return get_the_author();
			}

		) );
	register_rest_field( 'note', 'userNoteContent',
		array(
			'get_callback' => function () {
				return count_user_posts( get_current_user_id(), 'note' );
			}

		) );

}

function pageBanner( array $args = [] ): void {
	if ( !isset( $args['title'] ) ) {
		$args['title'] = get_the_title();
	}
	if ( !isset( $args['subtitle'] ) ) {
		$args['subtitle'] = get_field( 'page_banner_subtitle' );
	}
	?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
             style="background-image: url(<?php
		     $pageBannerImage = get_field( 'page_banner_background_image' );
		     if ( $pageBannerImage ) {
			     echo $pageBannerImage['sizes']['pageBanner'];
		     } else {
			     echo get_theme_file_uri( '/images/ocean.jpg' );
		     }
		     ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
<?php }

add_action( 'wp_enqueue_scripts', 'university_files' );
function university_files() {
	wp_enqueue_script( 'googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyDLU0MkvdkC8BJ-7nBmb-9DCRvw_yhTTe4', null, '1.0', true );
	wp_enqueue_script( 'main-university-js', get_theme_file_uri( '/build/index.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_style( 'custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'university_main_styles', get_theme_file_uri( '/build/style-index.css' ) );
	wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( '/build/index.css' ) );

	wp_localize_script( 'main-university-js', 'universityData', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce( 'wp_rest' ),

	) );
}

add_action( 'after_setup_theme', 'university_features' );
function university_features() {
//	hook actions for create and manage the menus in the admin
//	register_nav_menu('headerMenuLocation', 'Header Menu Location');
//	register_nav_menu('footerLocationOne', 'Footer Location One');
//	register_nav_menu('footerLocationTwo', 'Footer Location Two');
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'professorLandscape', 400, 600, true );
	add_image_size( 'professorPortrait', 480, 650, true );
	add_image_size( 'pageBanner', 1500, 350, true );
	add_theme_support( 'editor-styles' );
	add_editor_style( [
		'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i',
		'build/style-index.css',
		'build/index.css'
	] );
}

add_action( 'pre_get_posts', 'university_adjust_query' );
function university_adjust_query( $query ) {
	$today = date( 'Ymd' );
	if ( !is_admin() and is_post_type_archive( 'program' ) and $query->is_main_query() ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', - 1 );

	}

	if ( !is_admin() and is_post_type_archive( 'campus' ) and $query->is_main_query() ) {
		$query->set( 'posts_per_page', - 1 );
	}

	if ( !is_admin() and is_post_type_archive( 'event' ) and $query->is_main_query() ) {
		$query->set( 'meta_key', 'event_date' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'ASC' );
		$query->set( 'meta_query', array(
			array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
				'type' => 'numeric'
			)
		) );
	}
}

add_filter( 'acf/fields/google_map/api', "universityMapKey" );

function universityMapKey( array $api = [] ) {
	$api['key'] = 'AIzaSyDLU0MkvdkC8BJ-7nBmb-9DCRvw_yhTTe4';

	return $api;
}

// Redirect subscriber accounts out of admin and onto homepage
add_action( 'admin_init', 'redirectSubsToFrontEnd' );
function redirectSubsToFrontEnd() {
	$ourCurrentUser = wp_get_current_user();
	if ( count( $ourCurrentUser->roles ) == 1 and $ourCurrentUser->roles[0] == 'subscriber' ) {
		wp_redirect( site_url( '/' ) );
		exit;

	}
}

add_action( 'wp_loaded', 'notSubAdminBar' );
function notSubAdminBar() {
	if ( !current_user_can( 'administrator' ) ) {
		show_admin_bar( false );
	}
}

// Customize login screen
add_filter( 'login_headerurl', 'ourHeaderUrl' );
function ourHeaderUrl() {
	return esc_url( site_url( '/' ) );
}

add_action( 'login_enqueue_scripts', 'ourLoginImage' );
function ourLoginImage() {
	wp_enqueue_style( 'custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'university_main_styles', get_theme_file_uri( '/build/style-index.css' ) );
	wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( '/build/index.css' ) );
}

add_action( 'login_headertitle', 'ourLoginTitle' );
function ourLoginTitle() {
	return get_bloginfo( 'name' );
}

// Force note posts to be private
add_filter( 'wp_insert_post_data', 'forcePrivateNotes', 10, 2 );
function forcePrivateNotes( $data, $postarr ) {
	if ( $data['post_type'] !== 'note' ) {
		return $data;
	}
	if ( count_user_posts( get_current_user_id(), 'note' ) >= 5 and !$postarr['ID'] ) {
		die( 'You have reached your limit of 5 notes' );
	}

	if ( $data['post_status'] !== 'trash' ) {
		$data['post_status'] = 'private';
	}

	$data['post_content'] = sanitize_text_field( $data['post_content'] );
	$data['post_title'] = sanitize_text_field( $data['post_title'] );

	return $data;
}


class JSXBlock {
	function __construct( $name, $my_render_callback = null, $data = null ) {
		$this->data = $data;
		$this->name = $name;
		$this->my_render_callback = $my_render_callback;
		add_action( 'init', [ $this, 'on_init' ] );
	}

	function our_render_callback( $attributes, $content ): false|string {
		ob_start();
		require get_theme_file_path( "/our-blocks/{$this->name}.php" );
		return ob_get_clean();

	}

	function on_init() {
		wp_register_script( $this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", array(
			'wp-blocks',
			'wp-editor'
		) );
		if ( $this->data ) {
			wp_localize_script( $this->name, $this->name, $this->data );
		}
		$our_args = [
			'editor_script' => $this->name,
		];
		if ( $this->my_render_callback ) {
			$our_args['render_callback'] = [ $this, 'our_render_callback' ];
		}
		register_block_type( "ourblocktheme/{$this->name}", $our_args );
	}
}


class Placeholder_Block {
	function __construct( $name ) {
		$this->name = $name;
		add_action( 'init', [ $this, 'on_init' ] );
	}

	function our_render_callback( $attributes, $content ): false|string {
		ob_start();
		require get_theme_file_path( "/our-blocks/{$this->name}.php" );
		return ob_get_clean();

	}

	function on_init(): void {
		wp_register_script( $this->name, get_stylesheet_directory_uri() . "/our-blocks/{$this->name}.js", array(
			'wp-blocks',
			'wp-editor'
		) );

		$our_args = [
			'editor_script' => $this->name,
			'render_callback' => [ $this, 'our_render_callback' ]
		];
		register_block_type( "ourblocktheme/{$this->name}", $our_args );
	}
}

$blocks = [
	[
		'name' => 'banner',
		'render_callback' => true,
		'fallback_img' => get_theme_file_uri( '/images/library-hero.jpg' )
	],
	[
		'name' => 'generic-heading',
		'render_callback' => false,

	],
	[
		'name' => 'generic-button',
		'render_callback' => false,

	],
	[
		'name' => 'slideshow',
		'render_callback' => true,
	],
	[
		'name' => 'slide',
		'render_callback' => true,
	]

];

array_map( function ( $block ) {
	$image_fallback = isset( $block['fallback_img'] ) ? [ 'fallback_image' => $block['fallback_img'] ] : null;
	new JSXBlock( $block['name'], $block['render_callback'], $image_fallback );
}, $blocks );

$elements = [
	[
		'name' => 'events-and-blocks',
	],
	[
		'name' => 'header'
	],
	[
		'name' => 'footer'
	]
];
array_map( function ( $element ) {
	new Placeholder_Block( $element['name'] );
}, $elements );

