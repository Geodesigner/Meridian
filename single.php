<?php get_header(); ?>

    <div id="main">
      <div class="container yue">
        <div id="content">
          <?php if(have_posts()):while (have_posts()) : the_post();?>
            <div class="entry-detail" id="post-<?php the_ID(); ?>">
              <div class="entry-header">
                <h2 class="entry-title"><?php the_title();?></h2>
                <div class="entry-meta"></div>
              </div>
              <div class="entry-body">
                <?php the_content(''); ?>
              </div>
              <div class="entry-extra">
                <div class="entry-tags">
                  <?php if ( get_the_tags() ) { the_tags("关键词：", ", "); } else{ echo "";  } ?>
                </div>
              </div>
            </div>
          <?php endwhile; endif;?>
          <?php comments_template(); ?>
        </div>
      </div>
    </div><!-- #main -->

<?php get_footer(); ?>
