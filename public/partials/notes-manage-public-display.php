<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/maha-alii
 * @since      1.0.0
 *
 * @package    Notes_Manage
 * @subpackage Notes_Manage/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- HTML structre for Add Note -->

<div class='wp-fn-notes-show-notes-container'>
		
			
		<a onclick="wp_fn_notes_show_insert_note()" >
				<button data-mdb-ripple-init class="btn btn-primary float-end " ><?php esc_html_e( 'Insert', 'wp-fn-notes' ); ?></button>
			</a>
			<a onclick="wp_fn_notes_show_list_note()">
				<button  data-mdb-ripple-init class="btn btn-primary float-end"><?php esc_html_e( 'List', 'wp-fn-notes' ); ?></button>
			</a>
			<table id="list-notes-wrap"class=" table align-middle mb-0 bg-white">
				<thead>
					<tr>
						<th>S.no</th>
						<th>User Info</th>
						<th>Title</th>
						<th>Description</th>
						<th>options</th>
					</tr>
				</thead>
				<tbody id="list-notes-body">
				<tr id=note-<?php echo esc_html( $this->id ); ?>>
								<th class="id"><?php echo esc_html( $this->id ); ?></th>
								<th  class="user-id" ><?php if ( is_user_logged_in() ) {
									echo get_avatar( get_current_user_id(), 40 );
								}

								 echo esc_html( $this->user_id );
								 ?></th>
								<td class="note-title"><?php echo esc_html( $this->title ); ?></td>
								<td class="note-description"><?php echo esc_html( $this->description ); ?></td>
								<td>
									<a onclick="wp_fn_notes_update_note(<?php echo esc_html( $this->id ); ?>)">
									<i class="fas fa-rotate"></i>
									</a>
									<a onclick="wp_fn_notes_delete_note(<?php echo esc_html( $this->id ); ?>)">
									<i class="fas fa-trash-can"> </i>
									</a>
								</td>
							</tr>
							</tbody>
			</table>

<div id="add-note-wrap">				
				<form class="form" method="post">
					<div class="form-group">
						<label for="title"><?php esc_html_e( 'Title', 'wp-fn-notes' ); ?></label>
						<input class="form-control" name="title" id="title" required>
						<p class="text-danger" id="title-warning" display=' hidden'><?php esc_html_e( 'This field is required', 'wp-fn-notes' ); ?></p>
					</div>
					<div class="form-group">
						<label for="description"><?php esc_html_e( 'Description', 'wp-fn-notes' ); ?></label>
						<textarea class="form-control" name='description' rows="4" id="description"><?php// wp_editor($description , 'description' ); ?></textarea>

					</div>
					<input class="form-control" type="hidden" name="note_id" id="note_id" value="">
					<input class="form-control" type="hidden" name="user_id" id="user_id"value="<?php echo esc_html( $this->user_id ); ?>"/>
					
					<button onclick="wp_fn_notes_insert_note()"  class="btn btn-primary" type="button" name="save"><?php esc_html_e( 'Save', 'wp-fn-notes' ); ?></button>
				</form>
			</div>
		</div> 