<?php

function anps_color_add_term_fields( $taxonomy ) {

	echo '<div class="form-field">
	<label for="anps_hex_color_attr">Color Hex</label>
	<input type="text" name="anps_hex_color_attr" id="anps_hex_color_attr" />
	<p>Enter hex decimal value for color.</p>
	</div>';

}



function anps_color_edit_term_fields( $term, $taxonomy ) {

	$value = get_term_meta( $term->term_id, 'anps_hex_color_attr', true );

	echo '<tr class="form-field">
	<th>
		<label for="anps_hex_color_attr">Color Hex</label>
	</th>
	<td>
		<input name="anps_hex_color_attr" id="anps_hex_color_attr" type="text" value="' . esc_attr( $value ) . '" />
		<p class="description">Enter hex decimal value for color.</p>
	</td>
	</tr>';

}



function anps_color_save_term_fields( $term_id ) {

	update_term_meta(
		$term_id,
		'anps_hex_color_attr',
		sanitize_text_field( $_POST['anps_hex_color_attr'] )
	);

}