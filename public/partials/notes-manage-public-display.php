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

<div class='fn-notes-show-notes-container'>			
	<a onclick="wp_fn_notes_show_insert_note()" >
		<i class="fas fa-square-plus mb-3 p-2 float-end fa-2x"></i>
	</a>
	<a onclick="wp_fn_notes_show_list_note()">
		<i class="fas fa-list p-2 mb-3 float-end fa-2x"></i>
	</a>
	<table id="list-notes-wrap" class="table align-middle mb-0 bg-white">
		<thead>
			<tr>
				<th><?php esc_html_e( 'User', 'fn-notes' ); ?></th>
				<th><?php esc_html_e( 'Title', 'fn-notes' ); ?></th>
				<th><?php esc_html_e( 'Description', 'fn-notes' ); ?></th>
				<th><?php esc_html_e( 'Action', 'fn-notes' ); ?></th>
			</tr>
		</thead>
		<tbody id="list-notes-body"><?php
			foreach ( $notes as $notes_data ) {

				$this->id          = $notes_data->id;
				$this->user_id     = $notes_data->user_info;
				$this->title       = $notes_data->title;
				$this->description = $notes_data->description;
				?>
			<tr id=note-<?php echo esc_html( $this->id ); ?>>
				<th class="user-id" >
					
					<?php
					$current_user = wp_get_current_user();
					$user_id      = $current_user->ID;
					$user_name    = $current_user->user_login;
					if($user_id) {
						echo get_avatar( get_current_user_id(), 40 );
						echo esc_html( $user_name);
					}
					elseif(!$user_id){
						?>
						<i class="far fa-circle-user fa-2x"></i>
						<?php
						echo'Guest';
					}
					
					?>
				</th>
				<td class="note-title"><?php echo esc_html( $this->title ); ?></td>
				<td class="note-description"><?php echo esc_html( $this->description ); ?></td>
				<td>
					<a onclick="wp_fn_notes_update_note(<?php echo esc_html( $this->id ); ?>)">
					<i class="fas fa-pen-to-square p-2 fa-2x"></i>
					</a>
					<a onclick="wp_fn_notes_delete_note(<?php echo esc_html( $this->id ); ?>)">
					<i class="fas fa-trash-can p-2 fa-2x"> </i>
					</a>
				</td>
			</tr> 
			<?php }
			?>
		</tbody>
	</table>

	<div id="add-note-wrap">				
			<form class="form" method="post">
				<div class="form-group">
					<input class="form-control mb-2" name="title" id="title" placeholder="<?php esc_html_e( 'Enter Your Title', 'fn-notes' ); ?>" required>
					<p class="text-danger" id="title-warning" display=' hidden'><?php esc_html_e( 'This field is required', 'fn-notes' ); ?></p>
				</div>
				<div class="form-group mb-3">
					<!-- <textarea class="form-control" name='description' rows="4" id="description"></textarea> -->
					<?php wp_editor( $this->description, 'description'  ); ?>
				</div>
				
				<input class="form-control" type="hidden" name="note_id" id="note_id" value="">
				<input class="form-control" type="hidden" name="user_id" id="user_id"value="<?php echo esc_html( $this->user_id ); ?>"/>
				
				<button onclick="wp_fn_notes_insert_note()"  class="btn btn-primary" type="button" name="save" id ="save"><?php esc_html_e( 'Save', 'fn-notes' ); ?></button>
			</form>
	</div>
</div>
