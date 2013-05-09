<?php

/******************************************************************************
 * Exercise: Building The Email Submission Form
 *****************************************************************************/

/**
 * Implements hook_node_view().
 */
function mailfish_node_view($node, $view_mode, $langcode) {
  $node->content['mailfish'] = array(
    '#markup' => drupal_render(drupal_get_form('mailfish_email_form', $node->nid)),
    '#weight' => 100,
  );
}

/**
 * Provide the form to add an email address.
 */
function mailfish_email_form($form, $form_state, $nid = 0) {
  global $user;
  $form['email'] = array(
    '#title' => t('Email address'),
    '#type' => 'textfield',
    '#size' => 20,
    '#description' => t('Join our mailing list'),
    '#default_value' => isset($user->mail) ? $user->mail : '',
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Sign Up'),
  );
  $form['nid'] = array(
    '#type' => 'hidden',
    '#value' => $nid,
  );
  return $form;
}

/******************************************************************************
 * Exercise: Build the Validation Handler
 *****************************************************************************/

/**
 * Validation handler for mailfish_email_form.
 */
function mailfish_email_form_validate($form, &$form_state) {

  $email = $form_state['values']['email'];
  if (!$email) {
    form_set_error('email', t('You must provide an email address in order to join a mailing list.'));
  }
  elseif (!valid_email_address($email)) {
    $message = t('The address %email is not a valid email address. Please re-enter your address.', array('%email' => $email));
    form_set_error('email', $message);
  }

  $nid = isset($form_state['values']['nid']) ? $form_state['values']['nid'] : 0;
  if (!mailfish_get_node_enabled($nid) && $nid != 0) {
    form_set_error('', t('MailFish subscriptions are not available for this node.'));
  }

  // Do not allow multiple signups for the same node and email address.
  $previous_signup = db_query("SELECT mail FROM {mailfish} WHERE nid = :nid AND mail = :mail", array('nid' => $nid, 'mail' => $email))->fetchField();
  if ($previous_signup) {
    form_set_error('email', t('The address %email is already subscribed to this list.', array('%email' => $email)));
  }
}

/**
 * Determine if a node is set to display an email address form.
 *
 * @param int $nid
 *   The node id of the node in question.
 *
 * @return boolean
 */
function mailfish_get_node_enabled($nid) {
  // @TODO: This function is just a stub.
  return TRUE;
}

/******************************************************************************
 * Exercise: Build the Submit Handler
 *****************************************************************************/

/**
 * Submission handler for mailfish_email_form.
 */
function mailfish_email_form_submit($form, &$form_state) {
  // The sitewide signup form will not have a set $form['#node'].
  $nid = isset($form_state['values']['nid']) ? $form_state['values']['nid'] : 0;
  if ($nid) { // Comment: it might be a good idea to add an extra is_numeric($nid) check here

    $node = node_load($nid);
  }
  // The sitewide signup form will not have a title, retrieve and use the site's name.
  $title = isset($node) ? $node->title : variable_get('site_name', 'Drupal');

  // Signup the user.
  mailfish_signup($form_state['values']['email'], $nid);
  // Provide the user with a translated confirmation message.
  drupal_set_message(t('Thank you for joining the mailing list for %title. You have been added as %email.', array('%title' => $title, '%email' => $form_state['values']['email'])));
}

/**
 * Store a mailfish email signup.
 */
function mailfish_signup($email, $nid, $account = NULL) {
  // @TODO: Finish this function so it stores to the database.
  drupal_set_message(t('Pretending to signup %email to %nid', array('%email' => $email, '%nid' => $nid)));
}