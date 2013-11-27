<?php
/*
 Plugin Name: bbPress Support Topic Fields
 Plugin URI: http://wordpress.org/plugins/bbpress-support-topic-fields
 Description: Transform bbPress' Create Topic form into a better bug submission form
 Version: 0.1
 Author: Jonathan Christopher
 Author URI: http://mondaybynoon.com/
*/

/*  Copyright 2012 Jonathan Christopher (email : jonathan@mondaybynoon.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

function bbpstf_extra_fields() {
	?>
	<h5><label for="bbpstf_did"><?php _e( 'This is what I did (required)', 'bbpstf' ); ?></label></h5>
	<div class="bbp-the-content-wrapper">
		<div id="wp-bbp_bbpstf_did-wrap" class="wp-core-ui wp-editor-wrap html-active">
			<div id="wp-bbp_bbpstf_did-editor-container" class="wp-editor-container">
				<textarea class="bbp-the-content wp-editor-area" name="bbpstf_did" id="bbpstf_did" cols="30" rows="5"><?php if( isset( $_POST['bbpstf_did'] ) ) echo esc_textarea( $_POST['bbpstf_did'] ); ?></textarea>
			</div>
		</div>
	</div>

	<h5><label for="bbpstf_expected"><?php _e( 'This is what I expected to happen (required)', 'bbpstf' ); ?></label></h5>
	<div class="bbp-the-content-wrapper">
		<div id="wp-bbp_bbpstf_expected-wrap" class="wp-core-ui wp-editor-wrap html-active">
			<div id="wp-bbp_bbpstf_expected-editor-container" class="wp-editor-container">
				<textarea class="bbp-the-content wp-editor-area" name="bbpstf_expected" id="bbpstf_expected" cols="30" rows="5"><?php if( isset( $_POST['bbpstf_did'] ) ) echo esc_textarea( $_POST['bbpstf_expected'] ); ?></textarea>
			</div>
		</div>
	</div>

	<h5><label for="bbpstf_happened"><?php _e( 'This is what actually happened (required)', 'bbpstf' ); ?></label></h5>
	<div class="bbp-the-content-wrapper">
		<div id="wp-bbp_bbpstf_happened-wrap" class="wp-core-ui wp-editor-wrap html-active">
			<div id="wp-bbp_bbpstf_happened-editor-container" class="wp-editor-container">
				<textarea class="bbp-the-content wp-editor-area" name="bbpstf_happened" id="bbpstf_happened" cols="30" rows="5"><?php if( isset( $_POST['bbpstf_did'] ) ) echo esc_textarea( $_POST['bbpstf_happened'] ); ?></textarea>
			</div>
		</div>
	</div>

	<h5><label for="bbpstf_hypothesis"><?php _e( 'This is what I think is broken', 'bbpstf' ); ?></label></h5>
	<div class="bbp-the-content-wrapper">
		<div id="wp-bbp_bbpstf_hypothesis-wrap" class="wp-core-ui wp-editor-wrap html-active">
			<div id="wp-bbp_bbpstf_hypothesis-editor-container" class="wp-editor-container">
				<textarea class="bbp-the-content wp-editor-area" name="bbpstf_hypothesis" id="bbpstf_hypothesis" cols="30" rows="5"><?php if( isset( $_POST['bbpstf_did'] ) ) echo esc_textarea( $_POST['bbpstf_hypothesis'] ); ?></textarea>
			</div>
		</div>
	</div>

	<h5><?php _e( 'Here is some additional information (required)', 'bbpstf' ); ?></h5>
	<p><?php _e( 'Please include relevant code snippets, active plugins, etc.', 'bbpstf' ); ?></p>
	<?php
}

add_action ( 'bbp_theme_before_topic_form_content', 'bbpstf_extra_fields');

function bbpstf_concat_topic( $topic ) {

	$hasError = false;

	// check to make sure all required fields have been populated
	if( empty( $_POST['bbpstf_did'] ) ) {
		$hasError = true;
		bbp_add_error( 'bbpstf_did', __( '<strong>ERROR</strong>: You did not indicate what you did!', 'bbpstf' ) );
	}

	if( empty( $_POST['bbpstf_expected'] ) ) {
		$hasError = true;
		bbp_add_error( 'bbpstf_expected', __( '<strong>ERROR</strong>: You did not indicate what you expected to happen!', 'bbpstf' ) );
	}

	if( empty( $_POST['bbpstf_happened'] ) ) {
		$hasError = true;
		bbp_add_error( 'bbpstf_happened', __( '<strong>ERROR</strong>: You did not indicate what actually happened!', 'bbpstf' ) );
	}

	if( $hasError )
		add_filter( 'bbp_has_errors', '__return_true' );

	if( isset( $topic['post_content'] ) ) {
		$did = !empty( $_POST['bbpstf_did'] ) ? "<strong>" . __( 'This is what I did', 'bbpstf' ) . "</strong>\n\n" . $_POST['bbpstf_did'] . "\n\n" : '';
		$expected = !empty( $_POST['bbpstf_expected'] ) ? "<strong>" . __( 'This is what I expected to happen', 'bbpstf' ) . "</strong>\n\n" . $_POST['bbpstf_expected'] . "\n\n" : '';
		$actually = !empty( $_POST['bbpstf_happened'] ) ? "<strong>" . __( 'This is what actually happened', 'bbpstf' ) . "</strong>\n\n" . $_POST['bbpstf_happened'] . "\n\n" : '';
		$hypothesis = !empty( $_POST['bbpstf_hypothesis'] ) ? "<strong>" . __( 'This is what I think is broken', 'bbpstf' ) . "</strong>\n\n" . $_POST['bbpstf_hypothesis'] . "\n\n" : '';

		$topic['post_content'] = $did . $expected . $actually . $hypothesis . $topic['post_content'];
	}

	if( !$hasError )
		return $topic;
}

add_filter( 'bbp_new_topic_pre_insert', 'bbpstf_concat_topic' );
