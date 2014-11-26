<?php
/*
 * @package      Meridian-function
 * @version      1.0
 * @author       DaoJing Gao <me@gaodaojing.com>
 * @copyright    2014 all rights reserved
 * @license:     GNU General Public License v2 or later
 * @license URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

add_action('admin_menu', 'meridian_admin_menu');
function meridian_admin_menu() {
  add_theme_page('主题设置', '主题设置', 'edit_themes', basename(__FILE__), 'meridian_settings_page');
  add_action( 'admin_init', 'meridian_settings' );
}


function meridian_settings() {
  register_setting( 'meridian-settings-group', 'meridian_options' );
}

function meridian_settings_page() {
  if ( isset($_REQUEST['settings-updated']) ) echo '<div id="message" class="updated fade"><p><strong>主题设置已保存.</strong></p></div>';
  if( 'reset' == isset($_REQUEST['reset']) ) {
    delete_option('meridian_options');
    echo '<div id="message" class="updated fade"><p><strong>主题设置已重置!</strong></p></div>';
  }
?>

<div class="wrap">
  <div id="icon-options-general" class="icon32"><br></div><h2>主题设置</h2><br>
  <form method="post" action="options.php">
    <?php settings_fields('meridian-settings-group'); ?>
    <?php $options = get_option('meridian_options');?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"><label>网站描述</label></th>
          <td>
            <p>用简洁凝练的话对你的网站进行描述</p>
            <p><textarea type="textarea" class="large-text" name="meridian_options[description]"><?php echo $options['description']; ?></textarea></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label>网站关键词</label></th>
          <td>
            <p>多个关键词请用英文逗号隔开</p>
            <p><textarea type="textarea" class="large-text" name="meridian_options[keywords]"><?php echo $options['keywords']; ?></textarea></p>
          </td>
        </tr>        
      </tbody>
    </table>
    <div class="meridian_submit_form">
      <input type="submit" class="button-primary meridian_submit_form_btn" name="save" value="<?php _e('Save Changes') ?>"/>
    </div>
  </form>
  <form method="post">
    <div class="meridian_reset_form">
      <input type="submit" name="reset" value="<?php _e('Reset') ?>" class="button-secondary meridian_reset_form_btn"/> 重置有风险，操作需谨慎！
      <input type="hidden" name="reset" value="reset" />
    </div>
  </form>
</div>
<?php }?>
