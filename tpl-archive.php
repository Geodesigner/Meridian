<?php
/**
 * Template Name: 归档
 */
get_header();?>

    <div id="main">
      <div class="container yue">
        <div id="content">
          <div id="entry-gallery">
            <?php if(have_posts()):while (have_posts()) : the_post();?>
              <div class="entry page" id="post-<?php the_ID();?>">
                <div class="entry-header">
                  <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
                </div>
                <div class="entry-body">
                  <?php echo meridian_archives_list(); ?>
                </div>
              </div>
            <?php endwhile; endif;?>
          </div>
          <div id="pagenavi"><?php pagenavi();?></div>
        </div>
      </div>
    </div><!-- #main -->

<?php get_footer(); ?>
