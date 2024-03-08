<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/maha-alii
 * @since      1.0.0
 *
 * @package    Notes_Manage
 * @subpackage Notes_Manage/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Notes_Manage
 * @subpackage Notes_Manage/public
 * @author     Maha Ali <maha@gmail.com>
 */
class Notes_Manage_Public {
	/**
	 * The id  we get from our database table.
	 *
	 * @var      int    $id    The id  we get from our database table.
	 */
	public $id;
	/**
	 * The tite  we get from our database table.
	 *
	 * @var      int    $title    The title  we get from our database table.
	 */

	public $title;
	/**
	 * The description  we get from our database table.
	 *
	 * @var      string    $description   The description  we get from our database table.
	 */
	public $description;

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Notes_Manage_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Notes_Manage_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/notes-manage-public.css',
			[],
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Notes_Manage_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Notes_Manage_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/notes-manage-public.js',
				['jquery' ],
				$this->version,
				false
			);
			wp_localize_script( $this->plugin_name, 'notes_manage_public_ajax', [
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ajax-nonce' ),
			] );
	}
		/**
		 * Executes the user login function
		 *
		 * @return void
		 */
	public function user_login() {
		if ( is_user_logged_in() ) {
				$user_id = wp_get_current_user();
				$user = get_userdataby( 'id', $note_id );
		}
	}
	/**
	 * Executes the function for not loggedin users.
	 *
	 * @return void
	 */
public function not_login() {
		if ( ! is_user_logged_in() ) {
			$unique_id = uniqid();
			setcookie( 'user_id', $note_id, time() + 30 * 24 * 60 * 60 );
		}
	}

	/**
	 * Executes the AJAX request on update_note action triggered by JS
	 *
	 * @return void
	 */
	public function update_note() {
		// No note variable found, or invalid delete_id, so exit.
		if ( ! isset( $_POST['id'] ) ) {
			die();
		}
		check_ajax_referer( 'ajax-nonce', 'nonce' );
		// Update note on user sumbit.
		if ( isset( $_POST['title'] ) && isset( $_POST['description'] ) ) {
			// Update Note Ajax Callback code.
			global $wpdb;
			$note_id = sanitize_text_field( wp_unslash( $_POST['id'] ) );
			$title = sanitize_text_field( wp_unslash( $_POST['title'] ) );
			$description = sanitize_text_field( wp_unslash( $_POST['description'] ) );
			// Check if title is empty.
			if ( ! $title ) {
				return;
			}

			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}notes SET title = %s , description = %s WHERE id = %d",
					$title,
					$description,
					$note_id
				)
			);
		}
	}

	/**
	 * Executes the AJAX request on delete_note action triggered by JS
	 *
	 * @return void
	 */
	public function delete_note() {
		// No note variable found, or invalid delete_id, so exit.
		if ( ! isset( $_GET['id'] ) || ! is_numeric( $_GET['id'] ) ) {
			die();
		}
		check_ajax_referer( 'ajax-nonce', 'nonce' );
		if ( isset( $_GET['id'] ) ) {
			// Delete Note Ajax Callback code.
			global $wpdb;
			$id = sanitize_text_field( wp_unslash( $_GET['id'] ) );
			if ( $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}notes WHERE id = %d", $id ) ) ) {
				echo 1; // Success indicator.
			} else {
				echo 'No rows affected. The record may not exist.';
			}
			wp_die();
		}
	}

	/**
	 * Executes the AJAX request on insert_note action triggered by JS
	 *
	 * @return void
	 */
	public function insert_note() {
		check_ajax_referer( 'ajax-nonce', 'nonce' );

		if ( isset( $_POST['title'] ) && isset( $_POST['description'] ) ) {
			// Insert Note Ajax Callback code.
			$title = sanitize_text_field( wp_unslash( $_POST['title'] ) );
			$description = sanitize_text_field( wp_unslash( $_POST['description'] ) );
			// Check if title is empty.
			if ( ! isset( $_POST['title'] ) ) {
				return;
			}
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare( "INSERT INTO {$wpdb->prefix}notes( title, description )VALUES ( %s, %s )", [ $title , $description ] )
			);
			echo esc_html( $wpdb->insert_id );
			wp_die();
		}
	}

	/**
	 * Basic structure of our code.
	 *
	 * @return void
	 */
	public function show_notes_callback() {
		 global $wpdb; ?>
		<div class='wp-fn-notes-show-notes-container'>
				<h1><?php esc_html_e( 'NOTES', 'wp-fn-notes' ); ?></h1>
				<a onclick="wp_fn_notes_show_insert_note()">
					<button class="btn btn-lg btn-primary my-5  float-right-top"><?php esc_html_e( 'Insert', 'wp-fn-notes' ); ?></button>
				</a>
				<a onclick="wp_fn_notes_show_list_note()">
					<button class="btn btn-lg btn-primary my-5  float-right-top "><?php esc_html_e( 'List of notes', 'wp-fn-notes' ); ?></button>
				</a>
				<table id="list-notes-wrap" class="table">
					<thead>
						<tr>
							<th>S.no</th>
							<th>Title</th>
							<th>Description</th>
							<th>options</th>
						</tr>
					</thead>
					<tbody id="list-notes-body">
						<?php
						$notes = $wpdb->get_results( "SELECT id, title , description FROM {$wpdb->prefix}notes" );
						foreach ( $notes as $notes_data ) {

								$this->id = $notes_data->id;
								$this->title = $notes_data->title;
								$this->description = $notes_data->description;
							?>
						<tr id=note-<?php echo esc_html( $this->id ); ?>>
							<th class="id"><?php echo esc_html( $this->id ); ?></th>
							<td class="note-title"><?php echo esc_html( $this->title ); ?></td>
							<td class="note-description"><?php echo esc_html( $this->description ); ?></td>
							<td>
								<a onclick="wp_fn_notes_update_note(<?php echo esc_html( $this->id ); ?>)">
									<button class="btn btn-lg btn-primary"><?php esc_html_e( 'Update', 'wp-fn-notes' ); ?></button>
								</a>
								<a onclick="wp_fn_notes_delete_note(<?php echo esc_html( $this->id ); ?>)">
									<button class="btn btn-lg btn-danger"><?php esc_html_e( 'Delete', 'wp-fn-notes' ); ?> </button>
								</a>
							</td>
						</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<!-- HTML structre for Add Note -->
				<div id="add-note-wrap">
					<h2><?php esc_html_e( 'Add Notes', 'wp-fn-notes' ); ?></h2>
					<form class="form" method="post">
						<div class="form-group">
							<label for="title"><?php esc_html_e( 'Title', 'wp-fn-notes' ); ?></label>
							<input class="form-control" name="title" id="title" required>
							<p class="text-danger" id="title-warning" display=' hidden'><?php esc_html_e( 'This field is required', 'wp-fn-notes' ); ?></p>
						</div>
						<div class="form-group">
							<label for="description"><?php esc_html_e( 'Description', 'wp-fn-notes' ); ?></label>
							<input class="form-control" name="description" id="description">
						</div>
						<input class="form-control" type="hidden" name="note_id" id="note_id" value="">
						<button onclick="wp_fn_notes_insert_note()" id="save-button" type="button" class="btn btn-primary" name="save"><?php esc_html_e( 'Save', 'wp-fn-notes' ); ?></button>
					</form>
				</div>
	</div>
						<?php
	}
}

