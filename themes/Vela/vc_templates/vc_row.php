<?php

global $wyde_sidebar_position, $vc_is_inline;
    
extract(shortcode_atts(array(
    'el_class'          => '',
    'alt_color'         => '',
    'alt_bg_color'      => '',
    'parallax'          => '',
    'background_overlay'   => '',
    'overlay_color'   => '',
    'full_width'        => '',
    'full_screen'        => '',
    'mask'        => '',
    'mask_style'        => '',
    'mask_color'        => '',
    'padding_size'      => '',
    'vertical_align'      => '',
    'font_color'      => '',
    'css'            => '',
), $atts));
    
wp_enqueue_script( 'wpb_composer_front_js' );
    
$output = '';
$css_class = array();
if(!empty($el_class)) $css_class[] =  $el_class;
if(!empty($alt_color)) $css_class[] = 'alt-color';    
if(!empty($alt_bg_color)) $css_class[] = 'alt-background-color';    
if(!empty($padding_size)) $css_class[] = $padding_size;    
if(!empty($parallax)) $css_class[] = 'parallax';    
if(!empty($full_width)) $css_class[] = 'full';    
if(!empty($full_screen)) $css_class[] = 'fullscreen';    
if(!empty($vertical_align)) $css_class[] = 'v-align v-'.$vertical_align;

$style = ''; 
if($vc_is_inline){
    //Generate inline CSS   
    $style = Wyde_VCExtend::vc_inline_css($css);
}else{
    if( $css ) $css_class[] = vc_shortcode_custom_css_class( $css, '' );
}

if ( ! empty( $font_color ) ) {
	$style .= vc_get_css_color( 'color', $font_color ); // 'color: '.$font_color.';';
}

if( ! empty( $style )) $style = ' style="' . esc_attr( $style ) . '"';


$overlay = '';
if($background_overlay){
    if($background_overlay == 'pattern'){
        $overlay = '<div class="section-overlay pattern-overlay"></div>';
    }else{
        $overlay = sprintf('<div class="section-overlay"%s></div>', (!empty($overlay_color)?' style="background-color:'.esc_attr( $overlay_color ).';"':''));
    }
}

$mask_shape = '';
if(!empty($mask)){
    $mask_left = intval($mask_style);
    $mask_right = 100 - $mask_left;
    $mask_shape = sprintf('<span class="mask mask-%s" style="border-color:%s;border-left-width:%svw;border-right-width:%svw;"></span>', esc_attr( $mask ), esc_attr( $mask_color ), esc_attr( $mask_left ), esc_attr( $mask_right ));  
} 

$inline_class = esc_attr( implode(' ', $css_class) );
if(! empty($inline_class)) $inline_class = ' '.$inline_class;

if(is_single() || ($wyde_sidebar_position && $wyde_sidebar_position != '0') || $this->settings('base')=='vc_row_inner') $output =  sprintf('<div class="row%s"%s>%s%s%s</div>', $inline_class, $style, $overlay, wpb_js_remove_wpautop($content), $mask_shape);  
else{
    if($full_screen)
        $output = sprintf('<section class="section%s"%s>%s<div class="container"><div class="row"><div class="row-inner">%s</div></div></div>%s</section>', $inline_class, $style, $overlay, wpb_js_remove_wpautop($content), $mask_shape);  
    else
        $output = sprintf('<section class="section%s"%s>%s<div class="container"><div class="row">%s</div></div>%s</section>', $inline_class, $style, $overlay, wpb_js_remove_wpautop($content), $mask_shape);  
}
echo $output;