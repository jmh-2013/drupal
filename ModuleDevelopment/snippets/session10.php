<?php

/******************************************************************************
 * Exercise: Reporting results
 *****************************************************************************/

/**
 * Menu callback.
 *
 * Displays mailfish signups.
 */
function mailfish_signups() {
  $output = '';
  $rows = array();
  $header = array(
   'User',
   'Node',
   'Email',
   'Created',
  );

  // Note - for a static query, as actually requred here, (where the SQL is
  // never going to change) the method below is recommended.
  /*
  $sql = "SELECT m.*, n.title FROM {mailfish} m
          LEFT JOIN {node} n ON n.nid = m.nid
          WHERE m.nid = :nid
          ORDER BY m.created ASC";
  $result = db_query($sql, array('nid' => $nid));
  */

  // Note - here we use the object oriented DB api for demonstrative purposes only.

  // Dynamically load the schema for this table (which could be modified by
  // other modules using hook_schema alter).
  $fields = drupal_get_schema('mailfish');
  // Intantiate a query object by using the db_select wrapper (db_update,
  // db_insert and db_delete are also available).
  $query = db_select('mailfish', 'm');
  // Add a join on the node table.
  $table_alias = $query->innerJoin('node', 'n', 'n.nid = m.nid', array());
  // Add our desired fields to the query, loading the fields for our table dynamically.
  $results = $query->fields('m', array_keys($fields['fields']))
    ->fields($table_alias, array('title'))
    // Add a sort to our query
    ->orderBy('m.created', $direction = 'ASC')
    // Execute our query, triggering it to run against the database.
    ->execute()
    // Return an array of stdClass objects representing our results.
    ->fetchAll();
  foreach ($results as $value) {
    $account = $value->uid ? user_load($value->uid) : '';
    $rows[] = array(
      $value->uid ? theme('username', array('account' => $account)) : '',
      $value->nid ? l($value->title, 'node/' . $value->nid) : '',
      $value->mail,
      date('F j, Y g:i A', $value->created),
    );
  }
  $output .= theme('table', array('header' => $header, 'rows' => $rows));
  return $output;
}
