<?php

/******************************************************************************
 * Exercise: Creating a MaiFish Subscription Block
 *****************************************************************************/
  
/**
 * Implements hook_block_info().
 */
function mailfish_block_info() {
  $blocks = array();
  $blocks['mailfish_subscribe'] = array(
    'info' => t('MailFish Signup Form'),
    'cache' => DRUPAL_NO_CACHE,
  );
  return $blocks;
} 

/**
 * Implements hook_block_view().
 */
function mailfish_block_view($delta = 'mailfish_subscribe') {
  $block = array();
  switch ($delta) {
    case 'mailfish_subscribe':
      $block['subject'] = t('Sign up for %site', array('%site' => variable_get('site_name', 'Drupal')));
      $block['content'] = drupal_render(drupal_get_form('mailfish_email_block_form'));
      break;
  }
  return $block;
}

/**
 * Provide the form for the block content.
 *
 * This form is the same as the node form,
 * but with a different form_id to prevent 
 * conflict.
 */
function mailfish_email_block_form($form, $form_state) {
  $form = mailfish_email_form($form, $form_state);
  $form['#validate'][] = 'mailfish_email_form_validate';
  $form['#submit'][] = 'mailfish_email_form_submit';
  return $form;
}

/******************************************************************************
 * Exercise: Theming the MailFish Subscription Block
 *****************************************************************************/

/**
 * Implementation of hook_theme().
 */
function mailfish_theme() {
  $theme = array();
  $theme['mailfish_block'] = array(
    'variables' => array(
      'rendered_form' => NULL,
    ),
    'template' => 'mailfish-block',
  );
  return $theme;
}

/**
 * Implements hook_block_view().
 */
function mailfish_block_view($delta = 'mailfish_subscribe') {
  $block = array();
  switch ($delta) {
    case 'mailfish_subscribe':
      $block['subject'] = t('Sign up for %site', array('%site' => variable_get('site_name', 'Drupal')));
      $block['content'] = theme('mailfish_block', array('rendered_form' => drupal_render(drupal_get_form('mailfish_email_block_form'))));
      break;
  }
  return $block;
}

?>
<?php
/**
 * @file
 *   Themes the mailfish block.  
 */
?>
<div id='mailfish-rocks'>
  Check it out:
  <?php print $rendered_form; ?>
</div>