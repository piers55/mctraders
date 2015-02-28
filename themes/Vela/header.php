<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8" />     
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><?php wp_title(''); ?></title>
        <?php global $wyde_options; ?>
        <?php if( !empty($wyde_options['favicon_image']['url']) ): ?>
        <link rel="icon" href="<?php echo esc_url( $wyde_options['favicon_image']['url'] ); ?>" type="image/png" />
	    <?php endif; ?>
        <?php if( !empty($wyde_options['favicon']['url']) ): ?>
	    <link rel="shortcut icon" href="<?php echo esc_url( $wyde_options['favicon']['url'] ); ?>" type="image/x-icon" />
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_iphone']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( $wyde_options['favicon_iphone']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_iphone_retina']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url( $wyde_options['favicon_iphone_retina']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_ipad']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url( $wyde_options['favicon_ipad']['url'] ); ?>">
	    <?php endif; ?>
	    <?php if( !empty($wyde_options['favicon_ipad_retina']['url']) ): ?>
	    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo esc_url( $wyde_options['favicon_ipad_retina']['url'] ); ?>">
	    <?php endif; ?>
        <?php
        $body_classes = array($wyde_options['layout']=='boxed'? 'boxed':'wide');

        if($wyde_options['onepage']) $body_classes[] = 'onepage';
        
        if($wyde_options['boxed_shadow']) $body_classes[] = 'boxed-shadow';
        
        if($wyde_options['background_mode'] == 'pattern' && isset( $wyde_options['background_pattern'] ) ) $body_classes[] = 'pattern-'. $wyde_options['background_pattern'];

        if($wyde_options['background_mode'] == 'pattern' && $wyde_options['background_pattern_fixed']) $body_classes[] = 'background-fixed';

        if($wyde_options['background_mode'] != 'pattern' && $wyde_options['background_pattern_overlay']) $body_classes[] = 'background-overlay';

        ?>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class( esc_attr( implode(' ', $body_classes) ) ); ?>>
        <div id="container" class="container">
            <div id="page">
                <?php 
                global $wyde_page_id;

                $wyde_page_id = get_the_ID();

                if(is_home() || is_search() || ( (is_single() || is_archive() || is_author() ) && get_post_type(get_the_ID()) == 'post') ){
                    $blog_page_id = get_option('page_for_posts');
                    if($blog_page_id) $wyde_page_id = $blog_page_id;
                }
                if(is_woocommerce()){
                   if(is_shop() || is_single() || is_archive()) $wyde_page_id = get_option('woocommerce_shop_page_id');
                }   

                ?>
                <?php get_template_part('page', 'background');?>
                <?php
                if( get_post_meta( $wyde_page_id, '_meta_page_header', true ) != 'hide'){    
                    get_template_part( 'inc/header' );
                
                    if($wyde_options['header_position'] == '1'){
                        wyde_get_header(true);
                    }
                }
                ?>
                <?php
                $slider = get_post_meta( $wyde_page_id, '_meta_slider_show', true )=='on';
                if($slider == true) {
                ?>
                <div id="slider">
                <?php
                    wyde_get_slider(get_post_meta( $wyde_page_id, '_meta_slider_item', true ));
                ?>
                </div>
                <?php
                }
                if(get_post_meta( $wyde_page_id, '_meta_page_header', true ) != 'hide' && $wyde_options['header_position'] == '2'){    
                    wyde_get_header($slider == false);
                }
                ?>
                <div id="content">
                <?php
                get_template_part('page', 'title');