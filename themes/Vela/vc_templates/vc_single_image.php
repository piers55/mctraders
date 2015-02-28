<?php

$output = $el_class = $image = $img_size = $img_link = $img_link_target = $img_link_large = $title = $alignment = $animation = $css = '';

extract( shortcode_atts( array(
	'title' => '',
	'image' => $image,
	'img_size' => 'thumbnail',
	'img_link_large' => false,
	'img_link_target' => '_self',
	'alignment' => 'left',
	'el_class' => '',
	'animation' => '',
	'animation_delay' => 0,
	'style' => '',
	'border_color' => '',
	'css' => ''
), $atts ) );

$style = ( $style != '' ) ? $style : '';

$border_color = ( $border_color != '' ) ? ' style="border-color:' . esc_attr( $border_color ).';"' : '';


$img_id = preg_replace( '/[^\d]/', '', $image );
$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size  ) );
if ( $img == NULL ) $img['thumbnail'] = '<img src="' . esc_url( vc_asset_url( 'vc/no_image.png' ) ) . '" />'; //' <small>'.__('This is image placeholder, edit your page to replace it.', 'js_composer').'</small>';

$el_class = $this->getExtraClass( $el_class );

$a_attr = '';
$link_to = '';
if ( $img_link_large == true ) {
	
    if ($img_link_target == "prettyphoto") {
	    $a_attr = ' rel="prettyPhoto[single]"';
    }

    $link_to = wp_get_attachment_image_src( $img_id, 'large' );
	$link_to = $link_to[0];

}

$img_output = '<span>' . $img['thumbnail'] . '</span>';

$image_string = ! empty( $link_to ) ? '<a' . $a_attr . ' href="' . esc_url( $link_to ) . '"' . ' target="' . esc_attr( $img_link_target ) . '"'. '>' . $img_output . '</a>' : $img_output;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$data_attr = '';
if($animation) $data_attr = ' data-animation="'. esc_attr( $animation ) .'"';
if($animation_delay) $data_attr .= ' data-animation-delay="'. intval( $animation_delay ) .'"';

$css_class .= ' vc_align_' . $alignment;

$output .= "\n\t" . '<div class="' . esc_attr( $css_class ) . '"'.$data_attr.'>';
$output .= "\n\t\t" . '<div class="vc-image-border '. ($style == 'vc_box_shadow_3d'? 'vc_box_shadow_3d_wrap': $style) .'"'.$border_color.'>';
$output .= "\n\t\t\t" . wpb_widget_title( array( 'title' => esc_html( $title ), 'extraclass' => 'wpb_singleimage_heading' ) );
$output .= "\n\t\t\t" . $image_string;
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_single_image' );

echo $output;