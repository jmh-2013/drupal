<?php

/******************************************************************************
 * Exercise: Create your first page callback
 ***************************************************************************** /

/**
 * Implements hook_menu().
 */
function mailfish_menu() {
  $items = array();
  $items['admin/config/content/mailfish'] = array(
    'title' => 'MailFish Settings',
    'description' => 'Administer MailFish Settings.',
    'page callback' => 'mailfish_admin_settings_callback',
    'access arguments' => array('manage mailfish settings'),
  );
  return $items;
}

/**
 * Menu Callback; Allows admins to pick which content types should be enabled for signups.
 */
function mailfish_admin_settings_callback() {
  return 'Settings form should go here.';
}


/******************************************************************************
 * Exercise: Define menu items with hook_menu
 *****************************************************************************/
  
/**
 * Implements hook_menu().
 */
function mailfish_menu() {
  $items = array();
  $items['admin/config/content/mailfish'] = array(
    'title' => 'MailFish Settings',
    'description' => 'Administer MailFish Settings.',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('mailfish_admin_settings_form'),
    'access arguments' => array('manage mailfish settings'),
    'file' => 'mailfish.admin.inc',
  );
  $items['admin/reports/mailfish'] = array(
    'title' => 'MailFish Signups',
    'description' => 'View MailFish Signups',
    'page callback' => 'mailfish_signups',
    'access arguments' => array('view mailfish subscriptions'),
    'file' => 'mailfish.admin.inc',
  );
  return $items;
}

/******************************************************************************
 * Exercise: Define menu items with hook_menu
 *****************************************************************************/

/**
 * Implements hook_permission().
 */
function mailfish_permission() {
  $perm = array(
    'view mailfish subscriptions' => array(
      'title' => t('View Mailfish subscriptions'),
    ),  
    'create mailfish subscriptions' => array(
      'title' => t('Create Mailfish subscriptions'),
    ),  
    'manage mailfish settings' => array(
      'title' => t('Manage Mailfish settings'),
    )   
  );  
  return $perm;
}

/******************************************************************************
 * Exercise: Creating the mailfish.admin.inc file
 *****************************************************************************/

<?php

/**
 * @file
 * Provide the admin related functions for the MailFish module.
 */
