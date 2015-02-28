<?php
$output = $font_color = $el_class = $width = $offset = '';
extract(shortcode_atts(array(
	'font_color'      => '',
    'alt_color'         => '',
    'alt_bg_color'      => '',
    'el_class' => '',
    'width' => '1/1',
    'padding_size'  => '',
    'text_align'  => '',
    'css' => '',
	'offset' => ''
), $atts));

$col_name = wpb_translateColumnWidthToSpan($width);
$col_name = vc_column_offset_class_merge($offset, $col_name);


$css_class[] =  'column';
if(!empty($el_class)) $css_class[] = $el_class;
if(!empty($alt_color)) $css_class[] = 'alt-color';    
if(!empty($alt_bg_color)) $css_class[] = 'alt-background-color';    
if(!empty($padding_size)) $css_class[] = $padding_size;    
if(!empty($text_align)) $css_class[] = 'text-'. $text_align;    


$style = '';
if ( ! empty( $font_color ) ) {
	$style .= vc_get_css_color( 'color', $font_color );
}

if ($this->settings('base') == 'vc_column_inner' && preg_match('/([\d]+)/', $col_name, $match) ) {
	if(isset($match[0]) && ( intval( $match[0] ) <= 4 || intval( $match[0] ) >= 8 ) ){
        $col_name = str_replace('vc_col-sm', 'vc_col-md', $col_name);
	}
}

$item_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $col_name . ' '. implode(' ', $css_class) . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$output .= "\n\t".'<div class="'.esc_attr( $item_class ).'"'. (empty( $style ) ? $style : ' style="' . esc_attr( $style ) . '"') .'>';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment($el_class) . "\n";

echo $output;