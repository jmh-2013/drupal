<?php

/**
 * Menu Callback; Allows admins to pick which content types should be   enabled for signups.
 */
function mailfish_admin_settings_callback() {
  return drupal_get_form('mailfish_admin_settings_form');
}

/******************************************************************************
 * Exercise: Defining The System Settings Form in mailfish.admin.inc
 *****************************************************************************/

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

/******************************************************************************
 * Exercise: Add a per-node setting with hook_form_alter().
 *****************************************************************************/

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Adds a checkbox to allow email address collection per node for
 * enabled content types.
 */
function mailfish_form_node_form_alter(&$form, $form_state) {
  $node = $form['#node'];
  // Perform our check to see if we should be performing an action as the very first action.
  $types = variable_get('mailfish_types', array());
  // Check if this node type is enabled for mailfish
  // and that the user has access to the per-node settings.
  if (!empty($types[$node->type]) && user_access('manage mailfish settings')) {
    // Add a new fieldset with a checkbox for per-node mailfish setting.
    $form['mailfish'] = array(
      '#title' => t('MailFish'),
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#group' => 'additional_settings',
    );
    $form['mailfish']['mailfish_enabled'] = array(
      '#title' => t('Collect e-mail addresses for this node.'),
      '#type' => 'checkbox',
      '#default_value' => isset($node->mailfish_enabled) ? $node->mailfish_enabled : FALSE,
    );
  }
}
