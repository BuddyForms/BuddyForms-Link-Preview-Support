<?php

/*
 Plugin Name: BuddyForms Link Preview Support
 Plugin URI: http://themekraft.com/store/wordpress-front-end-editor-and-form-builder-buddyforms/
 Description: BuddyForms Link Preview Support
 Version: 1.1
 Author: Sven Lehnert
 Author URI: http://themekraft.com/members/svenl77/
 License: GPLv2 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

function buddyforms_at_preview_add_tinymce($args) {
    global $buddyforms;

    if(!isset($buddyforms[$args['form_slug']]['link_preview']))
        return $args;

    add_filter( 'mce_external_plugins', 'at_preview_add_tinymce_plugin' );
    // Add to line 1 form WP TinyMCE
    add_filter( 'mce_buttons', 'at_preview_add_tinymce_button' );

    return $args;
}
add_action( 'buddyforms_create_edit_form_args', 'buddyforms_at_preview_add_tinymce' );

function buddyforms_at_preview_add_tinymce_sidebar_metabox(){
    add_meta_box('buddyforms_at_preview', __('Link Preview','buddyforms'), 'buddyforms_at_preview_add_tinymce_sidebar_metabox_html', 'buddyforms', 'side', 'low');
}
add_filter('add_meta_boxes','buddyforms_at_preview_add_tinymce_sidebar_metabox');

function buddyforms_at_preview_add_tinymce_sidebar_metabox_html($form, $selected_form_slug){
  global $post, $buddyforms;

  if($post->post_type != 'buddyforms')
      return;

  $buddyform = get_post_meta(get_the_ID(), '_buddyforms_options', true);

  $form_setup = array();

  $link_preview = '';
  if(isset($buddyform['link_preview']))
      $link_preview = $buddyform['link_preview'];

  $form_setup[] = new Element_Checkbox("<b>" . __('Add Link Preview to TinyMCE', 'buddyforms') . "</b>", "buddyforms_options[link_preview]", array("integrate" => "Add to TinyMCE"), array('value' => $link_preview));

  foreach($form_setup as $key => $field){
      echo '<div class="buddyforms_field_label">' . $field->getLabel() . '</div>';
      echo '<div class="buddyforms_field_description">' . $field->getShortDesc() . '</div>';
      echo '<div class="buddyforms_form_field">' . $field->render() . '</div>';
  }
}
