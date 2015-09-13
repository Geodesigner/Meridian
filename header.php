<!DOCTYPE html>
<html lang="zh_CN">
  <head>
    <meta charset="utf-8">
    <?php meridian_meta();?>
    <?php wp_head(); ?>
    <script>
     if(/windows/i.test(navigator.userAgent)){
       document.getElementsByTagName('html')[0].className += 'windows'
     }
    </script>
  </head>
  <body <?php body_class(); ?>>
    <div id="header">
      <div class="container yue clearfix">
        <h1 class="logo alignleft">
          <a href="<?php bloginfo('url');?>" title="<?php bloginfo('name'); ?>" rel="home">
            <?php bloginfo('name'); ?>
          </a>
        </h1>
        <?php wp_nav_menu(array( 'theme_location'=>'topMenu','container_class' => 'main-menu')); ?>
      </div>
    </div><!-- #header -->
