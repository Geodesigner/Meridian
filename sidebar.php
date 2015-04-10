<div id="sidebar">
  <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar')) : ?>
    <p><?php _e('请到后台设置小工具!');?></p>
  <?php endif; ?>
  <div class="sidebar-footer">
    <div class="container yue">
      <p>© 2012-2014 <?php bloginfo('name'); ?> / Theme <a href="http://gaodaojing.com">Meridian</a></p>
    </div>
  </div>
</div><!-- end #sidebar -->
