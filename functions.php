<?php
/*
 * Meridian's functions file
 *
 * @package      Meridian
 * @version      1.0
 * @author       DaoJing Gao <me@gaodaojing.com>
 * @copyright    2014 all rights reserved
 * @license:     GNU General Public License v2 or later
 * @license URI: http://www.gnu.org/licenses/gpl-2.0.html
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
