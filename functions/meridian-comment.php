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

// Comment mail notify
function comment_mail_notify($comment_id) {
  $comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;

  $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
  $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
  $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '你在 [' . get_option("blogname") . '] 的留言有了新回复';
    $message = '
        <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
        <p><strong>' . trim(get_comment($parent_id)->comment_author) . ', 你好!</strong></p>
        <p><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言为:</strong><br />'
                                            . trim(get_comment($parent_id)->comment_content) . '</p>
        <p><strong>' . trim($comment->comment_author) . ' 给你的回复是:</strong><br />'
                           . trim($comment->comment_content) . '<br /></p>
        <p>你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看完整内容</a></p><br />
        <p>欢迎再次来访<a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
        <p>(此邮件为系统自动发送，请勿直接回复.)</p>
        </div>';

    wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');

// Meridian coment part
function meridian_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  global $commentcount;
  if(!$commentcount) {
    $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
    $cpp = get_option('comments_per_page');
    $commentcount = $cpp * $page;
  }
  if(!$comment->comment_parent){
  ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
      <div id="comment-<?php comment_ID(); ?>" class="comment-body">
        <div class="comment-avatar"><?php echo get_avatar( $comment, $size = '40'); ?></div>
        <div class="comment-data">
          <span class="comment-span"><?php printf(__('%s'), get_comment_author_link()) ?> /</span>
          <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('reply')))) ?>
          <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
        </div>
        <div class="comment-text"><?php comment_text() ?></div>
      </div>
  <?php }else{?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
      <div id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body">
        <div class="comment-avatar"><?php echo get_avatar( $comment, $size = '30'); ?></div>
        <div class="comment-data">
          <span class="comment-span"><?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf(__('%s'), get_comment_author_link()) ?> /</span>
          <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('reply')))) ?>
          <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
        </div>
        <div class="comment-text">
          <span class="comment-to"><a href="<?php echo "#comment-".$parent_id;?>" title="<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $comment_parent->comment_content)), 0, 100,"..."); ?>">@<?php echo $comment_parent->comment_author;?></a>：</span>
          <?php echo get_comment_text(); ?>
        </div>
      </div>
  <?php }
}

// Ajax comment
add_action('init', 'meridian_ajax_comment');
function meridian_ajax_comment(){
/**
 * WordPress jQuery-Ajax-Comments v1.3 by Willin Kan.
 * URI: http://kan.willin.org/?p=1271
 * for WP3.5+ | modified version URI: http://mufeng.me/wordpress3-5-willin-ajax-comment.html
 */
  if($_POST['action'] == 'meridian_ajax_comment' && 'POST' == $_SERVER['REQUEST_METHOD']){
    global $wpdb;
    nocache_headers();
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;

    $post = get_post($comment_post_ID);

    if ( empty($post->comment_status) ) {
      do_action('comment_id_not_found', $comment_post_ID);
      ajax_comment_err(__('Invalid comment status.')); // 將 exit 改為錯誤提示
    }

    // get_post_status() will get the parent status for attachments.
    $status = get_post_status($post);

    $status_obj = get_post_status_object($status);

    if ( !comments_open($comment_post_ID) ) {
      do_action('comment_closed', $comment_post_ID);
      ajax_comment_err(__('评论已关闭!')); // 將 wp_die 改為錯誤提示
    } elseif ( 'trash' == $status ) {
      do_action('comment_on_trash', $comment_post_ID);
      ajax_comment_err(__('Invalid comment status.')); // 將 exit 改為錯誤提示
    } elseif ( !$status_obj->public && !$status_obj->private ) {
      do_action('comment_on_draft', $comment_post_ID);
      ajax_comment_err(__('Invalid comment status.')); // 將 exit 改為錯誤提示
    } elseif ( post_password_required($comment_post_ID) ) {
      do_action('comment_on_password_protected', $comment_post_ID);
      ajax_comment_err(__('Password Protected')); // 將 exit 改為錯誤提示
    } else {
      do_action('pre_comment_on_post', $comment_post_ID);
    }

    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
    $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
    $edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null; // 提取 edit_id

    // If the user is logged in
    $user = wp_get_current_user();
    if ( $user->exists() ) {
      if ( empty( $user->display_name ) )
        $user->display_name=$user->user_login;
      $comment_author       = $wpdb->escape($user->display_name);
      $comment_author_email = $wpdb->escape($user->user_email);
      $comment_author_url   = $wpdb->escape($user->user_url);
      if ( current_user_can('unfiltered_html') ) {
        if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
          kses_remove_filters(); // start with a clean slate
          kses_init_filters(); // set up the filters
        }
      }
    } else {
      if ( get_option('comment_registration') || 'private' == $status )
        ajax_comment_err(__('你必须要登陆之后才可以发表评论.')); // 將 wp_die 改為錯誤提示
    }

    $comment_type = '';

    if ( get_option('require_name_email') && !$user->exists() ) {
      if ( 6 > strlen($comment_author_email) || '' == $comment_author )
        ajax_comment_err( __('请填写昵称和邮箱.') ); // 將 wp_die 改為錯誤提示
      elseif ( !is_email($comment_author_email))
        ajax_comment_err( __('请填写一个有效的邮箱.') ); // 將 wp_die 改為錯誤提示
    }

    if ( '' == $comment_content )
      ajax_comment_err( __('请输入评论.') ); // 將 wp_die 改為錯誤提示


    // 增加: 檢查重覆評論功能
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
      ajax_comment_err(__('您已经发布过一条相同的评论!'));
    }

    // 增加: 檢查評論太快功能
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
    $time_lastcomment = mysql2date('U', $lasttime, false);
    $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
    $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
    if ( $flood_die ) {
      ajax_comment_err(__('请过一会再发表评论.'));
      }
    }

    $comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;

    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

    // 增加: 檢查評論是否正被編輯, 更新或新建評論
    if ( $edit_id ){

      // 判断当前用户是否具有编辑该评论权限
      if(current_user_can("edit_comment",$edit_id)){

        // 判断当前用户的邮箱是否与评论邮箱相同
        if($current_user->user_email==$comment_author_email){
          $comment_id = $commentdata['comment_ID'] = $edit_id;
          wp_update_comment( $commentdata );
        }else{
          ajax_comment_err("您不能修改为他人评论！");
        }

      }else{
        ajax_comment_err("您没有权限编辑该评论！");
      }
    } else {
      $comment_id = wp_new_comment( $commentdata );
    }

    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);

    $comment_depth = 1;   //为评论的 class 属性准备的
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
      $comment_depth++;
      $tmp_c = get_comment($tmp_c->comment_parent);
    }

    //此处非常必要，无此处下面的评论无法输出
    $GLOBALS['comment'] = $comment;

    //以下是評論式樣, 不含 "回覆". 要用你模板的式樣 copy 覆蓋.
    if(!$comment->comment_parent){
    ?>
      <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment-body">
          <div class="comment-avatar"><?php echo get_avatar( $comment, $size = '40'); ?></div>
          <div class="comment-data">
            <span class="comment-span"><?php printf(__('%s'), get_comment_author_link()) ?> /</span>
            <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
          </div>
          <div class="comment-text"><?php comment_text() ?></div>
        </div>
    <?php }else{?>
       <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
         <div id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body">
           <div class="comment-avatar"><?php echo get_avatar( $comment, $size = '30'); ?></div>
           <div class="comment-data">
             <span class="comment-span"><?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf(__('%s'), get_comment_author_link()) ?> /</span>
             <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
           </div>
           <div class="comment-text">
             <span class="comment-to"><a href="<?php echo "#comment-".$parent_id;?>" title="<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $comment_parent->comment_content)), 0, 100,"..."); ?>">@<?php echo $comment_parent->comment_author;?></a>：</span>
             <?php echo get_comment_text(); ?>
           </div>
         </div>
    <?php }
    die(); //以上是評論式樣, 不含 "回覆". 要用你模板的式樣 copy 覆蓋.
  }else{return;}
}

/**
 * Ajax comments pagenavi in the theme.
 *
 */
add_action('init', 'meridian_ajax_pagenavi');
function meridian_ajax_pagenavi(){ // pagenavi
  if( isset( $_GET['action'] ) && $_GET['action']== 'meridian_ajax_pagenavi' ){
    global $post,$wp_query, $wp_rewrite;
    $postid = isset($_GET['post']) ? $_GET['post'] : null;
    $pageid = isset($_GET['page']) ? $_GET['page'] : null;
    if(!$postid || !$pageid){
      ajax_comment_err(__('Error post id or comment page id.'));
    }
    // get comments
    $comments = get_comments('post_id='.$postid);

    $post = get_post($postid);

    if(!$comments){
      ajax_comment_err(__('Error! can\'t find the comments'));
    }

    if( 'desc' != get_option('comment_order') ){
      $comments = array_reverse($comments);
    }

    // set as singular (is_single || is_page || is_attachment)
    $wp_query->is_singular = true;

    // base url of page links
    $baseLink = '';
    if ($wp_rewrite->using_permalinks()) {
      $baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
    }

    echo '<ol class="commentlist">';
    wp_list_comments('callback=meridian_comment&type=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
    echo '</ol><div class="comments-data comments-data-footer clearfix"><h2 class="comments-title">Leave a reply</h2><div class="comment-topnav">';
    paginate_comments_links('prev_text=«&next_text=»&current=' . $pageid);
    echo '</div></div>';

    die;
  }else{return;}
}

// 增加: 錯誤提示功能
function ajax_comment_err($a) {
  header('HTTP/1.0 500 Internal Server Error');
  header('Content-Type: text/plain;charset=UTF-8');
  echo $a;
  exit;
}

?>
