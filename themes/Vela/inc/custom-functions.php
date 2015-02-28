<?php
require_once get_template_directory() . '/inc/class-onepage-nav-walker.php';

function wyde_primary_menu(){
    global $wyde_options;
    if($wyde_options['onepage']){
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 5, 'container' => false, 'walker'=> new Wyde_OnePage_Walker_Nav, 'items_wrap' => '%3$s', 'fallback_cb'  => false) );                
    }else{
        wp_nav_menu(array('theme_location' => 'primary', 'depth' => 5, 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false));
    }
}

function wyde_menu($location, $depth){
    wp_nav_menu(array('theme_location' => $location, 'depth' => $depth, 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false));
}

function wyde_footer_content(){
    global $wyde_options;
    if( !empty($wyde_options['footer_script']) ){
        /**
        *Echo extra HTML/JavaScript/Stylesheet from theme options > advanced - body content
        */        
        echo balanceTags( $wyde_options['footer_script'], true );
    }
}

function wyde_ajax_loader($loader='1'){
     switch($loader){
            case '2':
            ?>
            <div id="ajax-loader" class="loader-2">
                <div class="spinner">
                  <div class="double-bounce1"></div>
                  <div class="double-bounce2"></div>
                </div>
            </div>
            <?php
            break;
            case '3':
            ?>
            <div id="ajax-loader" class="loader-3">
                <div class="spinner">
                  <div class="rect1"></div>
                  <div class="rect2"></div>
                  <div class="rect3"></div>
                  <div class="rect4"></div>
                  <div class="rect5"></div>
                </div>
            </div>
            <?php
            break;
            case '4':
            ?>
            <div id="ajax-loader" class="loader-4">
                <div class="spinner"></div>
            </div>
            <?php
            break;
            case '5':
            ?>
            <div id="ajax-loader" class="loader-5">
                <div class="spinner">
                  <div class="dot1"></div>
                  <div class="dot2"></div>
                </div>
            </div>
            <?php
            break;
            case '6':
            ?>
            <div id="ajax-loader" class="loader-6">
               <div class="spinner">
                  <div class="bounce1"></div>
                  <div class="bounce2"></div>
                  <div class="bounce3"></div>
                </div>
            </div>
            <?php
            break;
            case '7':
            ?>
            <div id="ajax-loader" class="loader-7">
              <div class="spinner">
                  <div class="spinner-container container1">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                  </div>
                  <div class="spinner-container container2">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                  </div>
                  <div class="spinner-container container3">
                    <div class="circle1"></div>
                    <div class="circle2"></div>
                    <div class="circle3"></div>
                    <div class="circle4"></div>
                  </div>
                </div>
            </div>
            <?php
            break;
            default:
            ?>
            <div id="ajax-loader" class="loader-1">
                <div class="spinner">
                  <div class="cube1"></div>
                  <div class="cube2"></div>
                </div>
            </div>
            <?php
            break;
        }
}

function wyde_set_post_views($post_id) {
    $count_key = 'wyde_post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    }else{
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}


function wyde_get_attachments_from_url($urls) {
 
	global $wpdb;
	$attachment_id = false;
 
	// If there is no url, return.
	if (count($urls) == 0 ) return;
 
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

    $images = array();

    foreach($urls as $url){

	    // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	    if ( false !== strpos($url, $upload_dir_paths['baseurl'] ) ) {
 
		    // If this is the URL of an auto-generated thumbnail, get the URL of the original image
		    $url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );
 
		    // Remove the upload path base directory from the attachment URL
		    $images[] ="'". str_replace( $upload_dir_paths['baseurl'] . '/', '', $url ) ."'";
            
	    }
    }
    // Finally, run a custom database query to get the attachment ID from the modified attachment URL
    return  $wpdb->get_col( $wpdb->prepare( "SELECT p.post_id FROM $wpdb->postmeta p WHERE p.meta_key = '_wp_attached_file' AND p.meta_value "). "IN(". implode(',', $images).")" );
}

function wyde_predifined_colors(){

    return array(
        '1' => '#FA5C5D',
        '2' => '#73B895',
        '3' => '#507FD6',
        '4' => '#7AB3BF',
        '5' => '#A5824B',
        '6' => '#FB9670',
        '7' => '#A57FAF',
        '8' => '#8C6E5D',
        '9' => '#FF780F',
        '10' => '#5BBBA6',
    );

}

function wyde_get_color_scheme(){
        global $wyde_options, $wyde_color_scheme;

        if(!$wyde_options['custom_color']){
            $colors = wyde_predifined_colors();
            $selected_color = $wyde_options['predefined_color'];
            if(isset($colors[$selected_color])) $wyde_color_scheme = $colors[$selected_color];
            else $wyde_color_scheme = $colors[1];
        }else{
            $wyde_color_scheme = $wyde_options['color_scheme'];
        }

        return $wyde_color_scheme;
}

function wyde_get_slider($id){
    wyde_revslider($id);
}

function wyde_layerslider($id){
    echo do_shortcode('[layerslider id="' . $id . '"]');
}

function wyde_revslider($id){
    echo do_shortcode('[rev_slider '.$id.']');
}

function wyde_sidebar($name=''){
    global $wyde_sidebar_position;
    if($name=='') $name='blog';
    ?>
    <div class="sidebar col-md-3<?php echo ($wyde_sidebar_position == '3'? ' col-md-offset-1':'');?>">
        <div class="content">
        <?php
        dynamic_sidebar($name);
        ?>
        </div>
    </div>
    <?php
}

function wyde_get_layout_name($wyde_sidebar_position=''){
    $page_sidebar;
    switch ($wyde_sidebar_position) {
        case '2':
            $page_sidebar = 'one-left';
            break;
        case '3':
            $page_sidebar = 'one-right';
            break;
        default:
            $page_sidebar = 'no-sidebar';
            break;
    }
    return $page_sidebar;
}


/* 
* Blog
* -----------------------------------------------*/
function wyde_get_post_masonry_layout(){
    return array('width2 height2', '', 'height2', 'height2', '', 'width2', 'item-h', '', 'width2 height2', 'height2', 'width2', 'height2', '', 'item-h', '', 'height2', 'width2 height2', 'height2', 'width2', '', 'item-h');
}

function wyde_get_post_preview($view, $item_index = 0, $class_name = ''){
    global $wyde_blog_layout;

    $item_classes = array();

    if($view != 'masonry'){
        $item_classes[] = 'clear';
    }

    if(!empty($class_name)) $item_classes[] = $class_name;

    $item_html = '';
    $item_html .= sprintf('<li class="item %s">', esc_attr( implode(' ', $item_classes) ) );
                
    ob_start();
    get_template_part( 'content', get_post_format());
    $item_html .= ob_get_clean();

    $item_html .= '</li>';

    return $item_html;
}


function wyde_get_type_icon($post_id=''){
    if($post_id =='') $post_id = get_the_ID();
    $thumbnail = '';
    switch(get_post_type($post_id)){
        case 'page':
            $thumbnail = '<i class="fa fa-file-text-o"></i>';
            break;
        case 'portfolio':
            $thumbnail = '<i class="fa fa-folder-open-o"></i>';
            break;
        case 'product':
            $thumbnail = '<i class="fa fa-shopping-cart"></i>';
            break;
        default:
            $thumbnail = '<i class="fa fa-file-text-o"></i>';
            break;
    }
    return $thumbnail;
}

function wyde_get_post_thumbnail($post_id='', $size='', $link=''){
    $thumbnail = '';
    if($post_id =='') $post_id = get_the_ID();
    if($size =='') $size = 'thumbnail';
    if(has_post_thumbnail($post_id)){ 
           $thumbnail =  get_the_post_thumbnail($post_id, $size);
    }else{
        switch(get_post_format($post_id)){
            case 'link':
                $thumbnail = '<i class="fa fa-link"></i>';
                break;
            case 'quote':
                $thumbnail = '<i class="fa fa-quote-right"></i>';
                break;
            case 'video':
                $thumbnail = '<i class="fa fa-film"></i>';
                break;
            case 'audio':
                $thumbnail = '<i class="fa fa-volume-up"></i>';
                break;
            case 'gallery':
                $thumbnail = '<i class="fa fa-image"></i>';
                break;
            default:
                $thumbnail = '<i class="fa fa-newspaper-o"></i>';
                break;
        }
    }
    if($link == '')
        return $thumbnail;
    else
        return sprintf('<a href="%s" title="">%s</a>', esc_url( $link ), $thumbnail);
}

function wyde_post_thumbnails($size = '', $link = '') {
	
    if ( post_password_required() ) {
		return;
	}

	if ( is_single() && get_post_format() != 'link' && !$link) {
        $thumb_id = get_post_thumbnail_id(get_the_ID());
        $image_full =  wp_get_attachment_image_src($thumb_id, 'blog-full' );
        ?>
        <a href="<?php echo esc_url($image_full[0] );?>" rel="prettyPhoto[blog]">
            <img src="<?php echo esc_url( $image_full[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
        </a>
    <?php
	}else{

        if(!$link) $link = get_permalink();

        ?>
	    <a href="<?php echo esc_url( $link ); ?>">
	        <?php
	        the_post_thumbnail($size);
	        ?>
	    </a>
	    <?php
    }

}

function wyde_post_title($link = ''){

    switch (get_post_format()) {
        case 'link':

            if($link == ''){
                
                $link = get_permalink();
 
                if(is_single())
                    the_title( '<h2 class="post-title">', '</h2>');
                else
                    the_title( '<h3 class="post-title"><a href="' . esc_url( $link ) . '">', '</a></h3>');

            }else{

                the_title( '<div class="post-title"><a href="' . esc_url( $link ).'"><i class="post-format-icon fa fa-link"></i>', '</a></div>');

            }

            break;
        case 'quote':

            if($link == '') $link = get_permalink();

            $quote = get_post_meta(get_the_ID(), '_meta_post_quote', true );

            if( empty($quote) ) $quote = get_the_title();

            $author = get_post_meta(get_the_ID(), '_meta_post_quote_author', true );

            if( !empty( $author ) ) $author = '<span class="quote-author"> &#8211; ' . esc_html( $author ) . '</span>';


             if(is_single())
                echo '<div class="post-title"><i class="post-format-icon fa fa-quote-right"></i>'. esc_html( $quote ) . $author .'</div>';
            else
                echo '<div class="post-title"><a href="' . esc_url( $link ) .'"><i class="post-format-icon fa fa-quote-right"></i>'. esc_html( $quote ). '</a>'. $author .'</div>';

            break;
        default:

            if(!$link) $link = get_permalink();

            if(is_single())
                the_title( '<h2 class="post-title">', '</h2>');
            else
                the_title( '<h3 class="post-title"><a href="' . esc_url( $link ) . '">', '</a></h3>');
            
            break;
    }
    
}

function get_single_category(){
    $categories = get_the_category(); 

    $category_names = array();
    if($categories){
	    foreach($categories as $category) {
            $category_names[] = esc_html($category->name);
	    }

        if($categories[0]){
            return '<a href="'. esc_url( get_category_link($categories[0]->term_id ) ) .'" title="'. esc_attr( implode(', ', $category_names) ) .'">'. esc_html( $categories[0]->name ) .'</a>';
        }
    }
    return '';
}

function wyde_post_meta(){
    global $wyde_options, $wyde_blog_layout;
    if(!$wyde_blog_layout) $wyde_blog_layout = $wyde_options['blog_layout'];
    
    ?>
    <div class="post-meta">
            <span class="post-datetime">
                <?php if(is_single()){ ?>
                <span class="date"><a href="<?php echo esc_url( get_day_link( get_the_date('Y'), get_the_date('m'), get_the_date('d') ) );?>"><?php echo get_the_date();?></a></span>
                <?php } ?>
                <?php if($wyde_options['blog_meta_time']){ ?>
                <span class="time"><?php echo get_post_time('g:i A'); ?></span>
                <?php } ?>
            </span>
            <?php if($wyde_options['blog_meta_author']){?>
            <span class="post-author">
                <strong><?php echo __('By', 'Vela');?></strong><?php echo the_author_posts_link();?>
            </span>
            <?php }?>
            <?php if($wyde_options['blog_meta_category']){?>
            <span class="post-category">
                <strong><?php echo __('In', 'Vela');?></strong><?php echo get_single_category(); ?>
            </span>  
            <?php }?>
            <?php
            edit_post_link('<i class="fa fa-edit"></i>' );
		    ?>
            <div class="meta-right">
            <?php
		    if ($wyde_options['blog_meta_comment']  && ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
		    ?>
		    <span class="comments-link"><?php comments_popup_link( '<i class="fa fa-comment-o"></i>0', '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%'); ?></span>
		    <?php
		    endif;
            ?>
            <?php if($wyde_options['blog_meta_share']){ ?>
            <div class="share-icons navbar-right">
                <a href="#"><i class="fa fa-share-alt"></i></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( esc_url( get_the_permalink() ) );?>" target="_blank"><i class="fa fa-facebook"></i>Facebook</a>
                    </li>
                    <li>
                        <a href="https://twitter.com/intent/tweet?source=webclient&amp;url=<?php echo urlencode( esc_url( get_the_permalink() ) );?>&amp;text=<?php echo urlencode( get_the_title() );?>" target="_blank"><i class="fa fa-twitter"></i>Twitter</a>
                    </li>
                    <li>
                        <a href="https://plus.google.com/share?url=<?php echo urlencode( esc_url( get_the_permalink() ) );?>" target="_blank"><i class="fa fa-google-plus"></i>Google+</a>
                    </li>
                </ul>
            </div>
            <?php }?>            
            </div>
	</div>
    <?php
}
function wyde_search_meta(){
    global $wyde_options;
    
    ?>
    <div class="post-meta">
            <?php if($wyde_options['search_show_date']){?>
            <span class="post-datetime">
                <?php echo get_the_date('M j, Y');?>
            </span>
            <?php }?>
            <?php if($wyde_options['search_show_author']){?>
            <span class="post-author">
                <i class="fa fa-pencil"></i><?php echo the_author_posts_link();?>
            </span>
            <?php }?>
	</div>
    <?php
}

function wyde_related_posts(){

    global $wyde_options, $post, $wyde_sidebar_position;

    $orig_post = $post;

    $tags = get_the_tags();
    
    if ($tags) {
        
        $tag_ids = array();

        foreach($tags as $tag){
            $tag_ids[] = $tag->term_id;
        } 
    
        $args=array(
            'tag__in' => $tag_ids,
            'post__not_in' => array($post->ID),
            'posts_per_page'    => intval( $wyde_options['blog_single_related_posts'] ),
            'ignore_sticky_posts'   => 1
        );

        $posts = new WP_Query( $args );
         
        if( $posts->have_posts() ) {
        ?>
        <div class="related-posts">
            <h3><?php echo esc_html( $wyde_options['blog_single_related_title'] );?></h3>
            <ul class="row">
            <?php
            while( $posts->have_posts() ) {
	            $posts->the_post();
	        ?>
	            <li class="col-sm-<?php echo ($wyde_sidebar_position == '1' ? '3':'4');?>">
                    <span class="thumb">
                    <?php echo wyde_get_post_thumbnail(get_the_ID(), 'blog-medium', get_the_permalink());?>
                    </span>
                    <h4>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <span class="date"><?php echo get_the_date(); ?></span>
			    </li>
	        <?php
	        }
            ?>
            </ul>
        </div>
        <?php
	    }

    }
	$post = $orig_post;
	wp_reset_query();
}

function wyde_pagination($type = '', $pages = '', $range = 2){
    
    global $wyde_options;

    if($type == ''){
        $type = $wyde_options['blog_pagination'];
    }

    if($type == '1'){
        wyde_numberic_pagination($pages, $range);
    }else if($type == '2'){
        wyde_infinitescroll($pages, $range);
    }else{

        if($pages == '')
        {
             global $wp_query;
             $pages = $wp_query->max_num_pages;
             if(!$pages)
             {
                 $pages = 1;
             }
        }   

        if($pages != 1)
        {
            echo '<div class="pagination">';
            echo '<span class="previous">';
            previous_posts_link( __('Newer Entries', 'Vela') );
            echo '</span>';
            echo '<span class="next">';
            next_posts_link( __('Older Entries', 'Vela'), $pages );
            echo '</span>';
            echo '</div>';
        }
    }

    
}

function wyde_numberic_pagination($pages = '', $range = 2)
{   
     global $paged;
     $show_items = ($range * 2)+1;  
    
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
    
     if($pages != 1)
     {
         echo '<div class="pagination numberic">';
         echo '<ul>';
         if($paged==1){
            echo '<li class="first disabled"><span><i class="fa fa-angle-double-left"></i></span></li>';         
            echo '<li class="prev disabled"><span><i class="fa fa-angle-left"></i></span></li>';         
         }else{
            echo '<li class="first"><a href="'. get_pagenum_link(1).'"><i class="fa fa-angle-double-left"></i></a></li>';
            echo '<li class="prev"><a href="'. get_pagenum_link($paged - 1) .'"><i class="fa fa-angle-left"></i></a></li>';
         } 


         for ($i = 1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= ($paged + $range + 1) || $i <= ( $paged - $range - 1) ) || $pages <= $show_items ))
             {
                 echo ($paged == $i)? '<li><span class="current">'.$i.'</span></li>' : '<li><a href="'. get_pagenum_link($i).'">'.$i.'</a></li>';
             }
         }
         if($paged==$pages){
            echo '<li class="next disabled"><span><i class="fa fa-angle-right"></i></span></li>';
            echo '<li class="last disabled"><span><i class="fa fa-angle-double-right"></i></span></li>';
         }else{
            echo '<li class="next"><a href="'. get_pagenum_link($paged + 1) .'"><i class="fa fa-angle-right"></i></a></li>';
            echo '<li class="last"><a href="'. get_pagenum_link($pages) .'"><i class="fa fa-angle-double-right"></i></a></li>';
         } 
         echo '</ul>';
         echo '</div>';
     }
}


function wyde_infinitescroll($pages = '', $range = 2)
{  
     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if($pages != 1 && $paged != $pages)
     {
         echo '<div class="showmore">';
         echo '<a href="'. get_pagenum_link($paged + 1).'" class="next">Show More</a>';
         echo '</div>';
     }
}

function wyde_post_nav($pages = 1, $range = 2)
{  
     // Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="post-nav clear">
        <div class="prev-post">
        <?php
			if($previous){
                $prev_thumbnail = wyde_get_post_thumbnail($previous->ID);
                previous_post_link('%link', '<i class="fa fa-angle-left"></i>');
                echo '<div class="post-link clear">';
                previous_post_link('<span class="thumb">%link</span>', $prev_thumbnail);
                previous_post_link('<div class="nav-text"><span>'.__('Prevoius Post', 'Vela').'</span><h5>%link</h5></div>');
                echo '</div>';
			} 
        ?>
        </div>
        <div class="next-post">
        <?php
		    if($next){
                $next_thumbnail = wyde_get_post_thumbnail($next->ID);
                next_post_link('%link', '<i class="fa fa-angle-right"></i>');
                echo '<div class="post-link clear">';
                next_post_link('<span class="thumb">%link</span>', $next_thumbnail);
                next_post_link('<div class="nav-text"><span>'.__('Next Post', 'Vela').'</span><h5>%link</h5></div>');
                echo '</div>';
		    } 
        ?>
        </div>
	</nav>
<?php
}

function wyde_comment($comment, $args, $depth) { 
	$add_below = '';
?>
	<li <?php comment_class();?>>
        <article id="comment-<?php comment_ID() ?>" class="clear">
            <div class="avatar">
			    <?php echo get_avatar($comment, $args['avatar_size']); ?>
		    </div>
		    <div class="comment-box">
			    <h4 class="name"><?php echo get_comment_author_link(); ?></h4>
                <div class="post-meta"><span class="comment-date"><i class="fa fa-clock-o"></i><?php printf('%1$s at %2$s', get_comment_date(),  get_comment_time()) ?></span><?php edit_comment_link(__('Edit', 'Vela'),'  ','') ?><?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply', 'Vela'), 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
			    <div class="post-content">
				    <?php if ($comment->comment_approved == '0') : ?>
				    <em><?php echo __('Your comment is awaiting moderation.', 'Vela') ?></em>
				    <br />
				    <?php endif; ?>
				    <?php echo wp_kses_data( get_comment_text() ); ?>
			    </div>
		    </div>
        </article>
<?php 
}

/*
* Portfolio
* ---------------------------------------*/
function wyde_get_portfolio_masonry_layout(){
    return array('width2 height2', '', 'height2', '', '', '', '', '', 'width2', 'width2 height2', '', '');
}

function wyde_get_portfolio_preview($view, $item_index = 0, $class_name = ''){
    global $post;

    if( $view == 'masonry' ){
        $masonry_layout = wyde_get_portfolio_masonry_layout();
        $layout_count = count($masonry_layout);
        $key = ($item_index % $layout_count);
        if(!empty($masonry_layout[$key])) $class_name = $masonry_layout[$key];
    }
   
    $item_classes = array();                 
    $cate_names = array();   
      
    $categories = get_the_terms( $post->ID, 'portfolio_category' );
            
    if (is_array( $categories )) { 
        foreach ( $categories as $item ) 
        {
            $item_classes[] = urldecode($item->slug);
            $cate_names[] = $item->name;
        }
    }

    if(!empty($class_name)) $item_classes[] = $class_name;

    $item_html = sprintf('<li class="item %s">', esc_attr( implode(' ', $item_classes) ));
    $item_html .= '<figure>';
    
    if($view == 'masonry'){
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'blog-full' ); 
        if($thumb){ 
            $item_html .= sprintf('<div class="cover-image" style="background-image:url(%s);"></div>', esc_url( $thumb[0] ));
        }
    }else{
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'blog-large' ); 
        if($thumb){ 
            $item_html .= sprintf('<img class="cover-image" src="%s" alt="%s" />', esc_url( $thumb[0] ), esc_attr( get_the_title() ));
        }
    }

    $item_html .=  '<figcaption>';
    $item_html .=  sprintf('<h3>%s</h3>', esc_html( get_the_title() ));
    $item_html .=  '<p>';
    $item_html .=  esc_html( implode(', ', $cate_names) );
    $item_html .=  '</p>';
    $item_html .=  sprintf('<a href="%s"></a>', esc_url( get_permalink() ));
    $item_html .=  '</figcaption>';		
    $item_html .=  '</figure>';
    $item_html .=  '</li>';

    return $item_html;
}

function wyde_related_portfolio(){

    global $wyde_options, $post, $wyde_sidebar_position;

    $orig_post = $post;

    $categories = get_the_terms( $post->ID, 'portfolio_category' );
    
    if (is_array( $categories )) {
        
        $cate_ids = array();
        foreach($categories as $category){
            $cate_ids[] = $category->term_id;
        } 

        $skills = get_the_terms( $post->ID, 'portfolio_skill' );
        $skill_ids = array();
        foreach($skills as $skill){
            $skill_ids[] = $skill->term_id;
        } 
    
        $args = array(
            'post_type' => 'portfolio',
	        'tax_query' => array(
                'relation' => 'AND',
		        array(
			        'taxonomy' => 'portfolio_category',
			        'field'    => 'id',
			        'terms'    => $cate_ids,
		        ),
                array(
			        'taxonomy' => 'portfolio_skill',
			        'field'    => 'id',
			        'terms'    => $skill_ids,
		        ),
	        ),
            'post__not_in' => array($post->ID),
            'posts_per_page'    => intval( $wyde_options['portfolio_related_posts'] ),
            'ignore_sticky_posts'   => 1
        );

        $posts = new WP_Query( $args );
         
        if( $posts->have_posts() ) {
        ?>
        <div class="related-posts">
            <h3><?php echo esc_html( $wyde_options['portfolio_related_title'] );?></h3>
            <ul class="row">
            <?php
            while( $posts->have_posts() ) {
	            $posts->the_post();
	        ?>
	            <li class="col-sm-<?php echo ($wyde_sidebar_position == '1' ? '3':'4');?>">
                    <span class="thumb">
                    <?php 
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'blog-medium' ); 
                    if($thumb){ 
                        echo sprintf('<a href="%s"><img class="cover-image" src="%s" alt="%s" /></a>', esc_url(get_the_permalink()), esc_url( $thumb[0] ), esc_attr( get_the_title() ));
                    }
                    ?>
                    </span>
                    <h4>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
			    </li>
	        <?php
	        }
            ?>
            </ul>
        </div>
        <?php
	    }

    }
	$post = $orig_post;
	wp_reset_query();
}

function wyde_social_icons($tooltip_pos='top'){
    global $wyde_options;

?>
    <ul class="social-icons">
    <?php if( !empty( $wyde_options['social_facebook'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_facebook'] );?>" target="_blank" title="Follow us on Facebook" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-facebook"></i></a></li>
    <?php } ?>
    <?php if( !empty( $wyde_options['social_twitter'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_twitter'] );?>" target="_blank" title="Follow us on Twitter" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-twitter"></i></a></li>
    <?php } ?>
    <?php if( !empty( $wyde_options['social_youtube'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_youtube'] );?>" target="_blank" title="Follow us on Youtube" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-youtube"></i></a></li>
    <?php } ?>
    <?php if( !empty( $wyde_options['social_instagram'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_instagram'] );?>" target="_blank" title="Follow us on Instagram" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-instagram"></i></a></li>
    <?php } ?>
    <?php if( !empty( $wyde_options['social_pinterest'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_pinterest'] );?>" target="_blank" title="Follow us on Pinterest" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-pinterest"></i></a></li>
    <?php } ?>
    <?php if( !empty( $wyde_options['social_googleplus'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_googleplus'] );?>" target="_blank" title="Follow us on Google+" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-google-plus"></i></a></li>
    <?php } ?>
    <?php if( !empty(  $wyde_options['social_flickr'] ) ){ ?>
        <li><a href="<?php echo esc_url( $wyde_options['social_flickr'] );?>" target="_blank" title="Follow us on Flickr" data-placement="<?php echo esc_attr( $tooltip_pos );?>"><i class="fa fa-flickr"></i></a></li>
    <?php } ?>
</ul><?php
}


function wyde_get_font_awesome_icons_from_css( $css = '' ) {

        if($css == ''){
            ob_start();
            include_once get_template_directory().'/css/font-awesome.min.css';
            $css = ob_get_clean();
        }
		
		$icons = array();
		$hex_codes = array();

		preg_match_all( '/\.(icon-|fa-)([^,}]*)\s*:before\s*{\s*(content:)\s*"(\\\\[^"]+)"/s', $css, $matches );
		$icons = $matches[2];
		$hex_codes = $matches[4];

		$icons = array_combine( $hex_codes, $icons );

		asort( $icons );

		return $icons;

}

function wyde_get_font_awesome_icons(){
    
    $lastversion = 4.3;

    $cache_version = get_transient( 'font_awesome_icons_current_version' );

    $icons = get_transient( 'font_awesome_icons' );
   
    if($cache_version == false || $cache_version < $lastversion || $icons == false){
	    $icons = wyde_get_font_awesome_icons_from_css();
	    set_transient( 'font_awesome_icons', $icons, 4 * WEEK_IN_SECONDS );
	    set_transient( 'font_awesome_icons_current_version', $lastversion, 4 * WEEK_IN_SECONDS );
    }

    return $icons;
}

function wyde_get_animations(){
   return array(
            '' 										=> 'No Animation',
            'bounceIn'   			  				=> 'Bounce In',
            'bounceInUp'   		  					=> 'Bounce In Up',
            'bounceInDown'   	  					=> 'Bounce In Down',
            'bounceInLeft'   	  					=> 'Bounce In Left',
            'bounceInRight'     					=> 'Bounce In Right',
            'fadeIn'   				  			  	=> 'Fade In',
            'fadeInUp' 				  			  	=> 'Fade In Up',
            'fadeInDown'   		  					=> 'Fade In Down',
            'fadeInLeft'   		  					=> 'Fade In Left',
            'fadeInRight'   	  					=> 'Fade In Right',
            'fadeInUpBig'   						=> 'Fade In Up Long',
            'fadeInDownBig'   						=> 'Fade In Down Long',
            'fadeInLeftBig'   						=> 'Fade In Left Long',
            'fadeInRightBig'  						=> 'Fade In Right Long',
            'lightSpeedIn'   						=> 'Light Speed In',
            'pulse'						    		=> 'Pulse',
            'rollIn'   				        		=> 'Roll In',
            'rotateIn'   			          	    => 'Rotate In',
            'slideInUp'   		        		    => 'Slide In Up',
            'slideInDown'   	        		    => 'Slide In Down',
            'slideInLeft'   	        		    => 'Slide In Left',
            'slideInRight'   	        		    => 'Slide In Right',
            'swing'						        	=> 'Swing',
            'zoomIn'					      		=> 'Zoom In',
            );

}
?>