<?php get_header();?>

    <div id="main">
      <div class="container yue">
        <div id="content">
          <div class="entry-list">
            <?php if(have_posts()):while (have_posts()) : the_post();?>
              <div class="entry" id="post-<?php the_ID();?>">
                <div class="entry-meta clearfix">
                  <div class="alignleft">
                    <div class="meta-avatar">
                      <?php echo get_avatar($post->post_author, 36); ?>
                    </div>
                    <div class="meta-feedSummary">
                      <span><?php the_author() ?></span>
                      <span class="meta-supplemental"><?php echo time_diff(get_the_time('U'), current_time('timestamp')); ?></span>
                    </div>
                  </div>
                  <div class="alignright">
                    <?php comments_popup_link('',' 1 Reply',' % Replies');?>
                  </div>
                </div>
                <div class="entry-header">
                  <?php meridian_thumbnail(600, 180); ?>
                  <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a>
                  </h2>
                </div>
                <div class="entry-body">
                  <?php echo "<p>" . mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 200,"..."). "</p>";?>
                </div>
                <div class="entry-footer">
                  <a href="<?php the_permalink(); ?>" title="<?php the_title();?>">Continue reading</a>
                  <span class="middotDivider"></span>
                  <span><?php if(function_exists('the_views')) the_views();?></span>
                </div>
              </div>
            <?php endwhile; endif;?>
          </div>
          <div id="pagenavi"><?php pagenavi();?></div>
        </div>
        <?php get_sidebar(); ?>
        </div>
      </div>
    </div><!-- #main -->

<?php get_footer(); ?>
