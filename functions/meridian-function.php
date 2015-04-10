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

define('IsMobile', wp_is_mobile());

define('THEMEVER', "1.0");

define("TPLDIR", get_bloginfo('template_directory'));

// Theme functions
if( is_admin() ) :
  get_template_part('functions/meridian-widget');
  get_template_part('functions/meridian-admin');
else :
  get_template_part('functions/meridian-meta');
  get_template_part('functions/meridian-comment');
  get_template_part('functions/meridian-page');
  get_template_part('functions/meridian-widget');
endif;

// Add rss feed
add_theme_support( 'automatic-feed-links' );

//Reomve wordpress none use header
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// Register wordpress menu
register_nav_menus(array(
  'topMenu' => '主菜单'
));

// Register wordpress sidebar
register_sidebar(array(
	'name'=>'sidebar',
	'before_widget' => '<div class="widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

// Enqueue style-file, if it exists.
add_action('wp_enqueue_scripts', 'meridian_script');
function meridian_script() {
  wp_enqueue_style('style', TPLDIR . '/public/dist/css/main.min.css', array(), THEMEVER, 'screen');
  wp_enqueue_script('script', TPLDIR . '/public/dist/js/main.min.js', array(), THEMEVER, false);
}

// Pagenavi of archive and index part
function pagenavi( $p = 5 ) {
  if ( is_singular() ) return;
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ( $max_page == 1 ) return;
  if ( empty( $paged ) ) $paged = 1;
  if ( $paged > 1 ) p_link( $paged - 1, '« Previous', '«' );
  if ( $paged > $p + 2 ) echo '<span class="page-numbers">...</span>';
  for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
    if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<span class='page-numbers current'>{$i}</span> " : p_link( $i );
  }
  if ( $paged < $max_page - $p - 1 ) echo '<span class="page-numbers">...</span>';
  if ( $paged < $max_page ) p_link( $paged + 1,'Next »', '»' );
}

function p_link( $i, $title = '', $linktype = '' ) {
  if ( $title == '' ) $title = "第 {$i} 页";
  if ( $linktype == '' ) { $linktext = $i; } else { $linktext = $linktype; }
  echo "<a class='page-numbers' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$linktext}</a> ";
}

function time_since($older_date,$comment_date = false) {
  $chunks = array(
    array(86400 , '天前'),
    array(3600 , '小时前'),
    array(60 , '分钟前'),
    array(1 , '秒前'),
  );
  $newer_date = time();
  $since = abs($newer_date - $older_date);
  if($since < 2592000){
    for ($i = 0, $j = count($chunks); $i < $j; $i++){
      $seconds = $chunks[$i][0];
      $name = $chunks[$i][1];
      if (($count = floor($since / $seconds)) != 0) break;
    }
    $output = $count.$name;
  }else{
    $output = !$comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
  }
  return $output;
}

// Count words in post
function count_words ($text) {
  global $post;
  if ( '' == $text ) {
    $text = $post->post_content;
    if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8'))
      $output .= mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8'). ' words';
    return $output;
  }
}

// Post thumbnail
add_theme_support( 'post-thumbnails' );
function meridian_thumbnail($width=620, $height=180){
  global $post;
  $title = $post->post_title;
  if( has_post_thumbnail() ){
    $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
    $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
    echo $post_timthumb;
  }else{
    $content = $post->post_content;
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0){
			echo '<img src="'.$strResult[1][0].'?imageView2/1/w/'.$width.'/h/'.$height.'" />';
		}
	}
}

add_filter( 'widget_tag_cloud_args', 'theme_tag_cloud_args' );
function theme_tag_cloud_args( $args ){
	$newargs = array(
		'smallest'    => 0.8,  //最小字号
		'largest'     => 0.8, //最大字号
		'unit'        => 'em',   //字号单位，可以是pt、px、em或%
		'number'      => 20,     //显示个数
		'format'      => 'flat',//列表格式，可以是flat、list或array
		'separator'   => "\n",   //分隔每一项的分隔符
		'orderby'     => 'name',//排序字段，可以是name或count
		'order'       => 'DESC', //升序或降序，ASC或DESC
		'exclude'     => null,   //结果中排除某些标签
		'include'     => null,  //结果中只包含这些标签
		'link'        => 'view', //taxonomy链接，view或edit
		'taxonomy'    => 'post_tag', //调用哪些分类法作为标签云
	);
	$return = array_merge( $args, $newargs);
	return $return;
}

//Human time diff
function time_diff( $from, $to = '' ) {
  if ( empty( $to ) )
    $to = time();
  $diff = (int) abs( $to - $from );
  if ( $diff <= HOUR_IN_SECONDS ) {
    $mins = round( $diff / MINUTE_IN_SECONDS );
    if ( $mins <= 1 ) {
      $mins = 1;
    }

    if ( $mins == 1 ) {
      $since = sprintf('%s min ago', $mins);
    } else {
      $since = sprintf('%s mins ago', $mins);
    }
  } elseif ( ( $diff <= DAY_IN_SECONDS ) && ( $diff > HOUR_IN_SECONDS ) ) {
    $hours = round( $diff / HOUR_IN_SECONDS );
    if ( $hours <= 1 ) {
      $hours = 1;
    }

    if ( $hours == 1 ) {
      $since = sprintf('%s hour ago', $hours);
    } else {
      $since = sprintf('%s hours ago', $hours);
    }
  } elseif ( ($diff <= WEEK_IN_SECONDS ) && ( $diff > DAY_IN_SECONDS ) ) {
    $days = round( $diff / DAY_IN_SECONDS );
    if ( $days <= 1 ) {
      $days = 1;
    }

    if ( $days == 1 ) {
      $since = sprintf('%s day ago', $days);
    } else {
      $since = sprintf('%s days ago', $days);
    }
  } elseif ( $diff > WEEK_IN_SECONDS ) {
    $weeks = round( $diff / WEEK_IN_SECONDS );
    if ( $weeks <= 1 ) {
      $weeks = 1;
    }

    if ( $weeks == 1 ) {
      $since = sprintf('%s week ago', $weeks);
    } elseif ( ($weeks > 1) && ($weeks <= 4) )  {
      $since = sprintf('%s weeks ago', $weeks);
    } else {
      $since = date('M d, Y', $from);
    }
  }
  return $since;
}

// Avatar
function local_avatar($avatar) {
  $tmp = strpos($avatar, 'http');
  $g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
  $tmp = strpos($g, 'avatar/') + 7;
  $f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
  $w = get_bloginfo('wpurl');
  $e = ABSPATH .'avatar/'. $f .'.jpg';
  $t = 1209600; //設定14天, 單位:秒
  if ( !is_file($e) || (time() - filemtime($e)) > $t ) { //當頭像不存在或文件超過14天才更新
    copy(htmlspecialchars_decode($g), $e);
  } else  $avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));
  if (filesize($e) < 500) copy($w.'/avatar/default.jpg', $e);
  return $avatar;
}
add_filter('get_avatar', 'local_avatar');

function get_v2ex_avatar($avatar) {
  $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://cdn.v2ex.com/gravatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
  return $avatar;
}
/* add_filter('get_avatar', 'get_v2ex_avatar'); */

/*
 * Escape special characters in pre.prettyprint into their HTML entities
 */
function meridian_esc_html($content) {

  $prettify_code = false;

  $regex = '/(<pre\s+[^>]*?class\s*?=\s*?[",\'].*?prettyprint.*?[",\'].*?>)(.*?)(<\/pre>)/si';
  $content = preg_replace_callback($regex, parse_content_pre, $content);

  $regex = '/(<code\s+[^>]*?class\s*?=\s*?[",\']\s*?prettyprint.*?[",\'].*?>)(.*?)(<\/code>)/si';
  $content = preg_replace_callback($regex, parse_content_code, $content);

  return $content;
}

function parse_content_pre($matches) {
  $tags_open = $matches[1];
  $code = $matches[2];
  $tags_close = $matches[3];

  $regex = '/(<code.*?>)(.*?)(<\/code>)/si';
  preg_match($regex, $code, $matches);
  if(!empty($matches)) {
    $tags_open .= $matches[1];
    $code = $matches[2];
    $tags_close = $matches[3].$tags_close;
  }

  $parsed_code = htmlspecialchars($code, ENT_NOQUOTES, get_bloginfo('charset'), true);

  $parsed_code = str_replace('&amp;#038;', '&amp;', $parsed_code);
  return $tags_open.$parsed_code.$tags_close;
}

function parse_content_code($matches) {
  $tags_open = $matches[1];
  $code = $matches[2];
  $tags_close = $matches[3];

  $parsed_code = htmlspecialchars($code, ENT_NOQUOTES, get_bloginfo('charset'), true);
  $parsed_code = str_replace('&amp;#038;', '&amp;', $parsed_code);
  return $tags_open.$parsed_code2.$tags_close;
}

add_filter('the_content', 'meridian_esc_html');
add_filter('comment_text', 'meridian_esc_html');


?>
