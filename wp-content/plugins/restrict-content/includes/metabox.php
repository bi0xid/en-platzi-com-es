<?php

/*******************************************
* Restrict Content Meta Box
*******************************************/

function rc_get_metabox() {
	$fields = array(
		'id' => 'rcMetaBox',
		'title' => __( 'Restrict this content', 'restrict-content' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => __( 'User Level', 'restrict-content' ),
				'id' => 'rcUserLevel',
				'type' => 'select',
				'desc' => __('Choose the user level that can see this page / post', 'restrict-content'),
				'options' => array(
					'None' => __( 'None', 'restrict-content' ),
					'Administrator' => __( 'Administrator', 'restrict-content' ),
					'Editor' => __( 'Editor', 'restrict-content' ),
					'Author' => __( 'Author', 'restrict-content' ),
					'Contributor' => __( 'Contributor', 'restrict-content' ),
					'Subscriber' => __( 'Subscriber', 'restrict-content' )
				),
				'std' => 'None'
			),
			array(
				'name' => __( 'Hide from Feed?', 'restrict-content' ),
				'id' => 'rcFeedHide',
				'type' => 'checkbox',
				'desc' => __( 'Hide the excerpt of this post / page from the Feed?', 'restrict-content' ),
				'std' => ''
			)
		)
	);

	return apply_filters( 'rc_metabox_fields', $fields );

}

// Add meta box
function rcAddMetaBoxes() {

	$metabox = rc_get_metabox();

	$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), 'objects' );
	foreach ( $post_types as $page ) {

		$exclude = apply_filters( 'rcp_metabox_excluded_post_types', array( 'forum', 'topic', 'reply', 'product', 'attachment' ) );

		if( ! in_array( $page->name, $exclude ) ) {
			add_meta_box( $metabox['id'], $metabox['title'], 'rcShowMetaBox', $page->name, $metabox['context'], $metabox['priority'] );
		}
	}
}
add_action('admin_menu', 'rcAddMetaBoxes');


// Callback function to show fields in meta box
function rcShowMetaBox() {

	global $post;

	$metabox = rc_get_metabox();

	// Use nonce for verification
	echo '<input type="hidden" name="rcMetaNonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';

	echo '<table class="form-table">';

	echo '<tr><td colspan="3">' . __( 'Use these options to restrict this entire entry, or the [restrict ...] ... [/restrict] short code to restrict partial content.', 'restrict-content' ) . '</td></tr>';

	foreach ( $metabox['fields'] as $field ) {

		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);

		echo '<tr>';
			echo '<th style="width:20%"><label for="' . $field['id'] . '">' . $field['name'] . '</label></th>';
			echo '<td>';
				switch ( $field['type'] ) {
					case 'select':
						echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
						foreach ( $field['options'] as $option => $label ) {
							echo '<option' . selected( $meta, $option, false ) . ' value="' . $option . '">' . $label . '</option>';
						}
						echo '</select>';
						break;
					case 'checkbox':
						echo '<input type="checkbox" name="' . $field['id'], '" id="' . $field['id'] . '"' . checked( 'on', $meta, false ) . ' />';
						break;
				}
			echo '<td>' . $field['desc'] . '</td><td>';
		echo '</tr>';
	}

	echo '</table>';
}

// Save data from meta box
function rcSaveData( $post_id ) {

	if( empty( $_POST['rcMetaNonce'] ) ) {
		return $post_id;
	}

	// verify nonce
	if ( ! wp_verify_nonce( $_POST['rcMetaNonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ( 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}

	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {

		return $post_id;

	}

	$metabox = rc_get_metabox();

	foreach ( $metabox['fields'] as $field ) {

		$old = get_post_meta( $post_id, $field['id'], true) ;
		$new = sanitize_text_field( $_POST[ $field['id'] ] );

		if ( $new && $new != $old ) {

			update_post_meta( $post_id, $field['id'], $new );

		} elseif ( '' == $new && $old ) {

			delete_post_meta( $post_id, $field['id'], $old );

		}
	}
}
add_action( 'save_post', 'rcSaveData' );