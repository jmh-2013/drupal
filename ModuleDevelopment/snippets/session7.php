<?php

/******************************************************************************
 * Exercise: Adding node hooks to mailfish.module
 *****************************************************************************/

/**
 * Implements hook_node_load().
 */
function mailfish_node_load($nodes, $types) {
  foreach ($nodes as $nid => $node) {
    // Add mailfish data to the node object when it is loaded.
    $node->mailfish_enabled = mailfish_get_node_enabled($node->nid);
  }
}

/**
 * Implements hook_node_insert().
 */
function mailfish_node_insert($node) {
  if ($node->mailfish_enabled) {
    // If MailFish is enabled, store the record.
    mailfish_set_node_enabled($node->nid);
  }
}

/**
 * Implements hook_node_update().
 */
function mailfish_node_update($node) {
  // Delete the old record, if one exists.
  mailfish_delete_node_enabled($node->nid);
  if ($node->mailfish_enabled) {
    // If MailFish is enabled, store the record.
    mailfish_set_node_enabled($node->nid);
  }
}

/**
 * Implements hook_node_delete().
 */
function mailfish_node_delete($node) {
  // Delete the mailfish_enabled record when the node is deleted.
  mailfish_delete_node_enabled($node->nid);
}

/**
 * Implements hook_node_view().
 */
function mailfish_node_view($node, $view_mode, $langcode) {
  // If appropriate, add the mailfish email form to the node's display.
  if (!empty($node->mailfish_enabled) && user_access('create mailfish subscription')) {
    $node->content['mailfish'] = array(
      '#markup' => drupal_render(drupal_get_form('mailfish_email_form', $node->nid)),
      '#weight' => 100,
    );
  }
}