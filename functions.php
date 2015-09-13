<?php
/*
 * Meridian's functions file
 *
 * @package      Meridian
 * @version      1.1
 * @author       Geladon <me@gaodaojing.com>
 * @copyright    2014 all rights reserved
 * @license:     New BSD License
 *
 */

// Meridian's core function
require( dirname(__FILE__) . '/functions/meridian-function.php' );

//Disable google fonts
function remove_open_sans_from_wp_core() {
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );
  wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans_from_wp_core' );

?>
