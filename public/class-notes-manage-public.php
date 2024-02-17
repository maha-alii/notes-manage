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
	 */
	public $id;
	public $title;
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
		$this->version     = $version;
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/notes-manage-public.css', array(), $this->version, 'all' );
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/notes-manage-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'notes_manage_public_ajax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
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

		// Update note on user sumbit.
		if ( isset( $_POST['title'] ) ) {
				// Update Note Ajax Callback code.
				global $wpdb;
				$note_id     = wp_unslash( $_POST['id'] );
				$title       = wp_unslash( $_POST['title'] );
				$description = wp_unslash( $_POST['description'] );
				// Check if title is empty.
			if ( ! $title ) {
				return;
			}

			$wpdb->query(
				$wpdb->prepare( "UPDATE {$wpdb->prefix}notes SET title = %s , description = %s WHERE id = %d", $title, $description, $note_id )
			);
		}
	}

	/**
	 * Executes the AJAX request on delete_note action triggered by JS
	 *
	 * @return void
	 */
	public function delete_note() {
		// Delete Note Ajax Callback code.
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}notes WHERE id = $id" ) );
	}


	/**
	 * Executes the AJAX request on insert_note action triggered by JS
	 *
	 * @return void
	 */
	public function insert_note() {
		// Insert Note Ajax Callback code.

		if ( isset( $_POST['title'] ) && isset( $_POST['description'] ) ) {
			$title       = wp_unslash( $_POST['title'] );
			$description = wp_unslash( $_POST['description'] );
			// Check if title is empty.
			if ( ! isset( $_POST['title'] ) ) {
				return;
			}
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}notes( title, description )
					VALUES ( %s, %s )",
					array(
						$title,
						$description,
					)
				)
			);
		}
	}

	/**
	 * Basic structure of our code
	 *
	 * @return void
	 */
	public function show_notes_callback() {
		global $wpdb;
		?>
		<!DOCTYPE html>
		<html>

		<head>
			<meta charset="UTF-8">
			<title>Coding Arena</title>
			<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

		</head>

		<body>
			<div class='container'>

				<h1>NOTES</h1>
				<a onclick="show_insert_note()">
					<button class="btn btn-lg btn-primary my-5  float-right-top">Insert</button>
				</a>
				<a onclick="show_list_note()">
					<button class="btn btn-lg btn-primary my-5  float-right-top ">List of notes</button>
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
						$this->id          = $notes_data->id;
						$this->title       = $notes_data->title;
						$this->description = $notes_data->description;
						?>
							<tr id=note-<?php echo htmlspecialchars( $this->id ); ?>>
								<th class="id"><?php echo htmlspecialchars( $this->id ); ?></th>
								<td class="note-title"><?php echo htmlspecialchars( $this->title ); ?></td>
								<td class="note-description"><?php echo htmlspecialchars( $this->description ); ?></td>
								<td>
									<a onclick="update_note(<?php echo htmlspecialchars( $this->id ); ?>)">
										<button class="btn btn-lg btn-primary">Update</button>
									</a>
								
									<a onclick="delete_note(<?php echo htmlspecialchars( $this->id ); ?>)">
										<button class="btn btn-lg btn-danger"  >Delete</button>
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
					<h2>Add Notes</h2>
					<form class="form" method="post">
						<div class="form-group">
							<label for="title">Title:</label>
							<input class="form-control" name="title" id="title" required>
							<p class="text-danger" id="title-warning" display=' hidden'>This field is required</p>
						</div>
						<div class="form-group">
							<label for="description">Description:</label>
							<input class="form-control" name="description" id="description">
						</div>
						<input class="form-control" type="hidden" name="note_id" id="note_id" value="">
						<button onclick="insert_note()" id="save-button" type="button" class="btn btn-primary" name="save">Save</button>
					</form>
				</div>
			</div>
		</body>

		</html>

			<?php
	}
}
