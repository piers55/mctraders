<?php

extract(shortcode_atts(array(
    'title' => __("Click to toggle", "js_composer"),
    'el_class' => '',
    'open' => 'false',
    'animation' =>  '',
    'animation_delay' =>  0,
), $atts));

$el_class = $this->getExtraClass($el_class);
$open = ( $open == 'true' ) ? ' wpb_toggle_title_active' : '';
$el_class .= ( $open == ' wpb_toggle_title_active' ) ? ' wpb_toggle_open' : '';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_toggle' . $open, $this->settings['base'], $atts );

$data_attr = '';
if($animation) $data_attr = ' data-animation="'. esc_attr( $animation ) .'"';
if($animation_delay) $data_attr .= ' data-animation-delay="'. intval( $animation_delay ) .'"';

echo apply_filters('wpb_toggle_heading', '<h4 class="'.esc_attr( $css_class ).'">'.esc_html( $title ).'</h4>', array('title'=>$title, 'open'=>$open));
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_toggle_content' . $el_class, $this->settings['base'], $atts );
echo '<div class="'.esc_attr( $css_class ).'"'. $data_attr.'>'.wpb_js_remove_wpautop($content, true).'</div>'.$this->endBlockComment('toggle')."\n";