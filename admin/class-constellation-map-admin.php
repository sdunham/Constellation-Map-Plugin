<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/sdunham
 * @since      1.0.0
 *
 * @package    Constellation_Map
 * @subpackage Constellation_Map/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Constellation_Map
 * @subpackage Constellation_Map/admin
 * @author     Scott Dunham <dunham.scott@gmail.com>
 */
class Constellation_Map_Admin {

	const POST_TYPE = 'constellation_map';
	const LEAFLET_JS = 'https://unpkg.com/leaflet@1.3.3/dist/leaflet.js';
	const LEAFLET_CSS = 'https://unpkg.com/leaflet@1.3.3/dist/leaflet.css';
	const LEAFLET_DRAW_JS = 'https://unpkg.com/leaflet-draw@1.0.2/dist/leaflet.draw.js';
	const LEAFLET_DRAW_CSS = 'https://unpkg.com/leaflet-draw@1.0.2/dist/leaflet.draw.css';
	const LEAFLET_PM_JS = 'https://unpkg.com/leaflet.pm@latest/dist/leaflet.pm.min.js';
	const LEAFLET_PM_CSS = 'https://unpkg.com/leaflet.pm@latest/dist/leaflet.pm.css';
	const VUE_JS = 'https://unpkg.com/vue@2.5.17/dist/vue.js';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Constellation_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Constellation_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;
		if ( $this->is_plugin_post_type_admin_page( $hook, $post->post_type ) ) {
			wp_enqueue_style( 'leafletjs', SELF::LEAFLET_CSS, [], $this->version );
			//wp_enqueue_style( 'leafletdraw', SELF::LEAFLET_DRAW_CSS, ['leafletjs'], $this->version );
			wp_enqueue_style( 'leafletpm', SELF::LEAFLET_PM_CSS, ['leafletjs'], $this->version );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/constellation-map-admin.css', ['leafletjs', 'leafletpm'], $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Constellation_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Constellation_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;
		if ( $this->is_plugin_post_type_admin_page( $hook, $post->post_type ) ) {
			wp_enqueue_script( 'leafletjs', SELF::LEAFLET_JS, [], $this->version, true );
			//wp_enqueue_script( 'leafletdraw', SELF::LEAFLET_DRAW_JS, ['leafletjs'], $this->version, true );
			wp_enqueue_script( 'leafletpm', SELF::LEAFLET_PM_JS, ['leafletjs'], $this->version, true );
			wp_enqueue_script( 'vuejs', SELF::VUE_JS, [], $this->version, true );			


			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/constellation-map-admin.js', ['jquery', 'leafletjs', 'leafletpm', 'vuejs'], $this->version, true );
			wp_localize_script( $this->plugin_name, 'custom_vue_templates', $this->get_vue_templates_array() );
		}
	}

	public function register_post_type(){
		$labels = [
			'name'                  => _x( 'Constellation Maps', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'Constellation Map', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'Constellation Maps', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'Constellation Map', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Add New', 'textdomain' ),
			'add_new_item'          => __( 'Add New Constellation Map', 'textdomain' ),
			'new_item'              => __( 'New Constellation Map', 'textdomain' ),
			'edit_item'             => __( 'Edit Constellation Map', 'textdomain' ),
			'view_item'             => __( 'View Constellation Map', 'textdomain' ),
			'all_items'             => __( 'All Constellation Maps', 'textdomain' ),
			'search_items'          => __( 'Search Constellation Maps', 'textdomain' ),
			'parent_item_colon'     => __( 'Parent Constellation Maps:', 'textdomain' ),
			'not_found'             => __( 'No constellation maps found.', 'textdomain' ),
			'not_found_in_trash'    => __( 'No constellation maps found in Trash.', 'textdomain' ),
			'featured_image'        => _x( 'Constellation Map Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'archives'              => _x( 'Constellation Map archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
			'insert_into_item'      => _x( 'Insert into constellation map', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this constellation map', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
			'filter_items_list'     => _x( 'Filter constellation map list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
			'items_list_navigation' => _x( 'Constellation map list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
			'items_list'            => _x( 'Constellation map list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'menu_icon'          => 'dashicons-location-alt',
			'supports'           => [ 'title' ],
		];

		register_post_type( self::POST_TYPE, $args );
	}

	public function save_post_meta($post_id){
		if (isset($_POST['constellationData'])) {
			// TODO: Start back up here. Parse json, sanitize and save as meta data
			//update_post_meta( $post_id, 'constellation_data', sanitize_text_field( $_POST['constellationData'] ) );
		}
	}

	// TODO: Documentation
	public function add_leaflet_container_to_post_edit_screen($post){
		global $pagenow;
		if ( $this->is_plugin_post_type_admin_page( $pagenow, $post->post_type ) ) {
			$containId = $this->plugin_name . '-contain';
			$leafletId = $this->plugin_name . '-admin-leaflet';
			$interfaceId = $this->plugin_name . '-ui';
			include( plugin_dir_path( __FILE__ ) . 'partials/vue-app.php' );
		}
	}

	// TODO: Documentation
	private function is_plugin_post_type_admin_page($admin_page, $post_type){
		if (
			( $admin_page === 'post-new.php' || $admin_page === 'post.php' ) && 
			self::POST_TYPE === $post_type
		) {
			return true;
		}

		return false;
	}

	// TODO: Documentation
	private function get_vue_templates_array(){
		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'partials/vue-constellation.php' );
		$constellation = ob_get_clean();

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'partials/vue-star.php' );
		$star = ob_get_clean();

		return [
			'constellation' => $constellation,
			'star' => $star,
		];
	}
}
