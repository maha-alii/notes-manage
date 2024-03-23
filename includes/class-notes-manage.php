<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/maha-alii
 * @since      1.0.0
 *
 * @package    Notes_Manage
 * @subpackage Notes_Manage/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Notes_Manage
 * @subpackage Notes_Manage/includes
 * @author     Maha Ali <maha@gmail.com>
 */
class Notes_Manage {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Notes_Manage_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NOTES_MANAGE_VERSION' ) ) {
			$this->version = NOTES_MANAGE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'notes-manage';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Notes_Manage_Loader. Orchestrates the hooks of the plugin.
	 * - Notes_Manage_i18n. Defines internationalization functionality.
	 * - Notes_Manage_Admin. Defines all hooks for the admin area.
	 * - Notes_Manage_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-notes-manage-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-notes-manage-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-notes-manage-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-notes-manage-public.php';

		$this->loader = new Notes_Manage_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Notes_Manage_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Notes_Manage_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Notes_Manage_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Notes_Manage_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//shortcode to show all notes.
		//add_shortcode( 'show_notes', array( $plugin_public, 'show_notes_callback' ) );

		//shortcode to show only login user notes.
		//add_shortcode( 'login_users_callback', array( $plugin_public, 'show_login_user_notes' ) );

		//shortcode to show only non-login notes.
		add_shortcode( 'non_login_users_callback', array( $plugin_public, 'show_non_login_user_notes' ) );


		// Ajax request for insert when user is logged in.
		$this->loader->add_action( 'wp_ajax_insert_note', $plugin_public, 'insert_note' );

		// Ajax request for insert when user is not logged in.
		$this->loader->add_action( 'wp_ajax_nopriv_insert_note', $plugin_public, 'insert_note' );

		// Ajax request for delete when user is logged in.
		$this->loader->add_action( 'wp_ajax_delete_note', $plugin_public, 'delete_note' );

		// Ajax request for delete when user is not logged in.
		$this->loader->add_action( 'wp_ajax_nopriv_delete_note', $plugin_public, 'delete_note' );

		// Ajax request for update when user is not logged in.
		$this->loader->add_action( 'wp_ajax_update_note', $plugin_public, 'update_note' );

		// Ajax request for update when user is not logged in.
		$this->loader->add_action( 'wp_ajax_nopriv_update_note', $plugin_public, 'update_note' );
		// returns the log-in user's id.
		$this->loader->add_action( 'wp_ajax_login_user_id', $plugin_public, 'login_user_id' );

		// when user is not logged in.
		$this->loader->add_action( 'wp_ajax_nopriv_login_user_id', $plugin_public, 'login_user_id' );

		// $this->loader->add_shortcode( 'show_notes', $plugin_public, 'enqueue_scripts' );

		// add_shortcode( 'show_notes', $plugin_public, 'show_notes_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Notes_Manage_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
