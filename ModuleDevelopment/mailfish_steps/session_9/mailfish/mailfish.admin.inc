<?php

/**
 * @file
 * Provide the admin related functions for the MailFish module.
 */

/**
 * Defines the MailFish admin settings form.
 */
function mailfish_admin_settings_form() {
  $form = array();
  $form['mailfish_types'] = array(
    '#title' => t('The content types to enable MailFish subscriptions for'),
    '#description' => t('On the specified node types, a MailFish subscription option will be available and can be enabled while that node is being edited.'),
    '#type' => 'checkboxes',
    '#options' => node_type_get_names(),
    '#default_value' => variable_get('mailfish_types', array()),
  );
  return system_settings_form($form);
}