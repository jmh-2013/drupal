<?php
// Wordpess Hook Example:
class emailer {
  function send($post_ID) {
    $friends = 'bob@example.org,susie@example.org';
    mail($friends,"sally's blog updated",'I just put something on my blog: http://blog.example.com');
    return $post_ID;
  }
}
add_action('publish_post', array('emailer', 'send'));


// Drupal Hook Example:
function mail_node_insert($node) {
  if($node->type == "blog") {
    $friends = 'bob@example.org,susie@example.org';
    mail($friends,"sally's blog updated",'I just put something on my blog:
    http://blog.example.com/node/'. $node->nid);
  }
}

// Red button module

/**
 * Implements hook_node_view().
 * 
 * This hook gets called everytime a node is being rendered.
 * @param $node The node object being rendered.
 * @param $view_mode How the node is being viewed.
 */
function redbutton_node_view($node, $view_mode) {
  if ($node->type == 'article') {
    $link_text = t('Delete this node');
    $link_options = array(
      'attributes' => array('style' => 'color:#ff0000'),
    );
    $link_markup = l($link_text, "node/$node->nid/delete", $link_options);
    $node->content['redbutton'] = array();
    $node->content['redbutton']['#markup'] = $link_markup;
  }
}

