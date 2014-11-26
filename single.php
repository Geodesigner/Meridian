<?php get_header(); ?>

    <div id="main">
      <div class="container yue">
        <div id="content">
          <?php if(have_posts()):while (have_posts()) : the_post();?>
            <div class="entry single" id="post-<?php the_ID(); ?>">
              <div class="entry-header">
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
                <div class="entry-meta">
                  <time class="published"><?php echo date('M d, Y', get_the_time('U'));?></time>
                  <em><?php echo count_words ($text); ?></em>
                </div>
              </div>
              <div class="entry-body">
                <?php the_content(''); ?>
              </div>
              <div class="entry-extra clearfix">
                <ul class="post-share clearfix">
                  <li class=""><a href="http://service.weibo.com/share/share.php?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>" class="share-item item-weibo" target="_blank" rel="nofollow" title="分享到新浪微博">新浪微博</a></li>
                  <li class=""><a href="http://v.t.qq.com/share/share.php?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>&site=<?php bloginfo('url');?>" class="share-item item-qqweibo" target="_blank" rel="nofollow" title="分享到腾讯微博">腾讯微博</a></li>
                  <li class=""><a href="http://www.douban.com/recommend/?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" class="share-item item-douban" target="_blank" rel="nofollow" title="分享到豆瓣">豆瓣网</a></li>
                  <li class=""><a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" class="share-item item-qzone" target="_blank" rel="nofollow" title="分享到QQ空间">QQ空间</a></li>
                  <li class=""><a href="http://share.renren.com/share/buttonshare?link=<?php the_permalink(); ?>&title=<?php the_title(); ?>" class="share-item item-renren" target="_blank" rel="nofollow" title="分享到人人网">人人网</a></li>
                  <li class=""><a href="http://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" class="share-item item-twitter" target="_blank" rel="nofollow" title="分享到Twitter">Twitter</a></li>
                </ul>
                <div class="post-tags">
                  <?php if ( get_the_tags() ) { the_tags(); } else{ echo "暂无关键词！";  } ?>
                </div>
              </div>
            </div>
          <?php endwhile; endif;?>
          <?php comments_template(); ?>
        </div>
      </div>
    </div><!-- #main -->

<?php get_footer(); ?>
