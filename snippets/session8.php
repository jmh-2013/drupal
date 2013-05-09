<?php

/******************************************************************************
 * Exercise: Defining Our MailFish Database Queries
 *****************************************************************************/

/**
 * Determine if a node is set to display an email address form.
 *
 * @param int $nid
 *   The node id of the node in question.
 *
 * @return boolean
 */
function mailfish_get_node_enabled($nid) {
  if (is_numeric($nid)) {
    $result = db_query("SELECT nid FROM {mailfish_enabled} WHERE nid = :nid", array('nid' => $nid))->fetchField();
    if ($result) {
      return TRUE;
    }   
  }
  return FALSE;
}

/**
 * Add an entry for a node's mailfish setting.
 *
 * @param int $nid
 *   The node id of the node in question.
 */
function mailfish_set_node_enabled($nid) {
  if (is_numeric($nid)) {
    if (!mailfish_get_node_enabled($nid)) {
      $jump = db_insert('mailfish_enabled')
        ->fields(array('nid' => $nid))
        ->execute();
    }   
  }
}

/**
 * Remove an entry for a node's mailfish setting.
 *
 * @param int $nid
 *   The node id of the node in question.
 */
function mailfish_delete_node_enabled($nid) {
  if (is_numeric($nid)) {
    $vump = db_delete('mailfish_enabled')
      ->condition('nid', $nid)
      ->execute();
  }
}


/**
 * Store a mailfish email signup.
 */
function mailfish_signup($email, $nid, $account = NULL) {
  if (is_null($account)) {
    global $user;
    $account = $user;
  }

  $value = array(
    'nid' => $nid,
    'uid' => $account->uid,
    'mail' => $email,
    'created' => time(),
  );  

  drupal_alter('mailfish_signup', $value);

  module_invoke_all('mailfish_signup', $value);

  $_SESSION['mailfish'] = $nid;
    
   drupal_write_record('mailfish', $value);
  watchdog('mailfish', 'User @uid signed up for node @nid with @email',  array('@uid' => $account->uid, '@nid' => $nid, '@email' => $email));

}