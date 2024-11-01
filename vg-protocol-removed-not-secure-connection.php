<?php

/**
* Plugin Name: VG Protocol Removed:Not Secure Connection
* Plugin URI: http://guptavishal.in/works/vg-protocol-removednot-secure-connection/
* Description: Remove Protocol from URL for resolve the Not Secure Connection issue in https protocol.
* Version: 1.0.0
* Author: Vishal Gupta
* Author URI: http://guptavishal.in
* License: GPLv2
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

Class HTML_Protocol_Remove_vgrp{

// Apply Plugin on the whole Site

  public function __construct(){
    add_action('wp_loaded', array(
      $this,
      'vgrhc_letsGo'
      ) , 99, 1);
  }

  public function vgrp_letsGo(){
    ob_start(array(
      $this,
      'vgrp_remove_protocol_comments'
      ));
  }

  /**
   * remove html comment
   * @param html code / html DOM
   * @return html DOM.
   */

  function vgrp_remove_protocol_comments($buffer) {
  $content_type = NULL;
  foreach(headers_list() as $header) {
    if (strpos(strtolower($header) , 'content-type:') === 0) {
      $pieces = explode(':', strtolower($header));
      $content_type = trim($pieces[1]);
      break;
    }
  }
  if (is_null($content_type) || substr($content_type, 0, 9) === 'text/html') {
    $buffer = str_replace(array('http://'.$_SERVER['HTTP_HOST'],'https://'.$_SERVER['HTTP_HOST']), '//'.$_SERVER['HTTP_HOST'], $buffer);
    $buffer = str_replace('content="//'.$_SERVER['HTTP_HOST'], 'content="https://'.$_SERVER['HTTP_HOST'], $buffer);
    $buffer = str_replace('> //'.$_SERVER['HTTP_HOST'], '> https://'.$_SERVER['HTTP_HOST'], $buffer);
    $buffer = str_replace('"url" : "//', '"url" : "https://', $buffer);
    $buffer = str_replace('"url": "//', '"url": "https://', $buffer);
    $buffer = preg_replace(array('|http://(.*?).googleapis.com|','|https://(.*?).googleapis.com|'), '//$1.googleapis.com', $buffer);
    $buffer = preg_replace(array('|http://(.*?).google.com|','|https://(.*?).google.com|'), '//$1.google.com', $buffer);
    $buffer = preg_replace(array('|http://(.*?).gravatar.com|','|https://(.*?).gravatar.com|'), '//$1.gravatar.com', $buffer);
    $buffer = preg_replace(array('|http://(.*?).w.org|','|https://(.*?).w.org|'), '//$1.w.org', $buffer);  
  }
  return $buffer;
  }
}

new HTML_Protocol_Remove_vgrp();
?>