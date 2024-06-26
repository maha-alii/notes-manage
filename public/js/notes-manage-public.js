(function ($) {
    'use strict';
    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    //$(document).ready(function () {
    //});
})(jQuery);
function wp_fn_notes_update_note(id) {
    var title = jQuery('#note-' + id)
        .find('.note-title')
        .text()
    var description = jQuery('#note-' + id)
        .find('.note-description ')
        .text()

    jQuery('#list-notes-wrap').hide()
    jQuery('#title-warning').hide()
    jQuery('#add-note-wrap').show()
    jQuery('#note_id').val(id)
    jQuery('#title').val(title)
    jQuery('#description').val(description)
}

function wp_fn_notes_insert_note() {
    var note_id = jQuery('#note_id').val()
    var title = jQuery('#title').val()
    var description = jQuery('#description').val()
    var user_id = jQuery('#user_id').val()

    /*Check if title is empty */
    if (title == '') {
        jQuery('#title-warning').show()
        return false
    }
    /* When Updating a Note Via AJAX */
    if (note_id) {
        jQuery.ajax({
            url: notes_manage_public_ajax.ajaxurl,
            type: 'post',
            data: {
                action: "update_note",
                nonce: notes_manage_public_ajax.nonce,
                id: note_id,
                title: title,
                description: description,
            },
            success: function (response) {
                if (!isNaN(response)) {
                    jQuery('#list-notes-wrap').show()
                    jQuery('#add-note-wrap').hide()

                    jQuery('#note-' + note_id)
                        .find('.note-title')
                        .text(title)
                    jQuery('#note-' + note_id)
                        .find('.note-description')
                        .text(description)
                } else {
                    // show error if not deleted
                    console.log(response)
                }
                return true
            },
        })
    } else if (title == '') {
        jQuery('#title-warning').show()
        return false
    } else {
        /* When Inserting a Note Via AJAX */
        jQuery.ajax({
            url: notes_manage_public_ajax.ajaxurl,
            type: 'post',
            data: {
                action: "insert_note",
                nonce: notes_manage_public_ajax.nonce,
                user_id: user_id,
                title: title,
                description: description,
            },
            success: function (response) {
                // You will get response from your PHP page (what you echo or print)
                if (!isNaN(response)) {
                    var note_id = response
                    jQuery('#list-notes-body').append(
                        
                        '<th class="user-id">' +
                        user_id +
                        '</th>\
                        <td class="note-title">' +
                        title +
                        '</td>\
                        <td class="note-description">' +
                        description +
                        '</td>\
                        <td> \
                            <a onclick="wp_fn_notes_update_note(' +
                        note_id +
                        ')">\
                        <i class="fas fa-pen-to-square fa-2x"></i>\
                            </a>\
                            <a onclick="wp_fn_notes_delete_note(' +
                        note_id +
                        ')">\
                        <i class="fas fa-trash-can fa-2x"> </i>\
                            </a>\
                        </td>\
                    </tr>'
                    )
                    jQuery('#list-notes-wrap').show()
                    jQuery('#add-note-wrap').hide()
                } else {
                    // show error if not deleted
                    console.log(response)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error in AJAX request: ' + errorThrown)
            },
        })
    }
}

function wp_fn_notes_show_list_note() {
    jQuery('#list-notes-wrap').show()
    jQuery('#add-note-wrap').hide()
}

function wp_fn_notes_show_insert_note() {
    // Empty the note id, title and desc before insert form display
    jQuery('#title').val('')
    jQuery('#description').val('')
    jQuery('#note_id').val('')


    jQuery('#list-notes-wrap').hide()
    jQuery('#title-warning').hide()
    jQuery('#add-note-wrap').show()
}

function wp_fn_notes_delete_note(id) {

    jQuery.ajax({
        url: notes_manage_public_ajax.ajaxurl,
        type: 'get',
        data: {

            action: "delete_note",
            id: id,
            nonce: notes_manage_public_ajax.nonce,
        },
        success: function (response) {
            // You will get response from your PHP page (what you echo or print)
            if (response == 1) {

                jQuery('#note-' + id).remove()
            } else {
                // show error if not deleted
                console.log(response)
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('error in AJAX request: ' + errorThrown)
        },
    })
}
