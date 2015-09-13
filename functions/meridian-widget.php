<?php

class Popular_articles_widget extends WP_Widget {
    function Popular_articles_widget() {
        $widget_ops = array('description' => 'Meridian主题：热门文章（需要WP-PostViews插件）');
        $this->WP_Widget('Popular_articles_widget', 'Meridian：热门文章', $widget_ops);
    }
    function widget($args, $instance) {
        extract($args);
        $limit = strip_tags($instance['limit']);
		$limit = $limit ? $limit : 10;
?>
	<div class="widget widget-populars">
		<h3>Popular Articles</h3>
		<ul class="list">
			<?php $args = array(
					'paged' => 1,
					'meta_key' => 'views',
					'orderby' => 'meta_value_num',
					'ignore_sticky_posts' => 1,
					'post_type' => 'post',
					'post_status' => 'publish',
					'showposts' => $limit
				);
				$posts = query_posts($args); ?>
			<?php while(have_posts()) : the_post(); ?>
			<li class="widget-popular">
                <p>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                    <span>[<?php if(function_exists('the_views')) the_views();?>]</span>
                </p>
            </li>
			<?php endwhile;wp_reset_query();$posts=null;?>
		</ul>
	</div>
<?php
    }

    function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }

    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('limit' => ''));
        $limit = strip_tags($instance['limit']);
?>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：
                <input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
            </label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php
    }
}

add_action('widgets_init', 'Popular_articles_widget_init');
function Popular_articles_widget_init() {
    register_widget('Popular_articles_widget');
}

?>
