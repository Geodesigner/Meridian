<?php

function page_categories_list($hide_empty = false){
  $output = '<div class="archive-category-groups">';
  $cateargs = array(
    'hide_empty' => $hide_empty
  );
  $categories = get_categories($cateargs);
  foreach($categories as $category) {
    $output .= '<div class="archive-category-group"><h2 class="archive-category-title"><a href="' . get_category_link( $category->term_id ) . '" title="' . $category->name . '" ' . '>' . $category->name.'</a> </h2><h3 class="archive-category-description">'. $category->description . '</h3><div class="archive-category-postcount textcenter">'. $category->count . '</div>';
    $args = array(
      'category' => $category->term_id,
      'numberposts' => -1
    );
    $output .= '<div class="archive-category-posts">';
    $posts = get_posts($args);
    foreach($posts as $post){
      $output .= '<div class="archive-category-post"><a class="archive-category-post-title" href="'.get_permalink($post->ID).'">'.$post->post_title.'</a><time class="archive-category-post-time">'.human_time_diff(strtotime($post->post_date_gmt),time()).' ago </time></div>';
    }
    $output .= '</div></div>';
  }
  return $output;
}

function page_archives_list() {
  $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' );
  $year=0; $mon=0; $i=0; $j=0;
  $all = array();
  $output = '';
  while ( $the_query->have_posts() ) : $the_query->the_post();
  $year_tmp = get_the_time('Y');
  $mon_tmp = get_the_time('n');
  $y=$year; $m=$mon;
  if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';
  if ($year != $year_tmp) { // 输出年份
    $year = $year_tmp;
    $all[$year] = array();
  }
  if ($mon != $mon_tmp) { // 输出月份
    $mon = $mon_tmp;
    array_push($all[$year], $mon);
    $output .= "<div class='archive-title' id='arti-$year-$mon'><h3>$year-$mon</h3><div class='archives archives-$mon' data-date='$year-$mon'>";
  }
  $output .= '<div class="brick"><a href="'.get_permalink() .'"><span class="time">'.get_the_time('n-d').'</span>'.get_the_title() .'<em>('. get_comments_number('0', '1', '%') .')</em></a></div>';
  endwhile;
  wp_reset_postdata();
  $output .= '</div></div>';
  return $output;
}

?>
