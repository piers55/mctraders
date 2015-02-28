<?php
extract( shortcode_atts( array(
	'el_width' => '',
	'style' => '',
	'color' => 'transparent',
	'accent_color' => '',
	'el_class' => ''
), $atts ) );

$inline_css = '';

if($style == 'theme'){
    if(!empty($color)){
        $inline_css = sprintf(' style="border-color:%1$s;color:%1$s;"', esc_attr( $color ) );
    }
    echo sprintf('<div class="separator"%s></div>', $inline_css);
}else{
    echo do_shortcode( '[vc_text_separator style="' . esc_attr( $style ) . '" color="' . esc_attr( $color ) . '" accent_color="' . esc_attr( $accent_color ) . '" el_width="' . esc_attr( $el_width ) . '" el_class="' . esc_attr( $el_class ) . '"]' );
}