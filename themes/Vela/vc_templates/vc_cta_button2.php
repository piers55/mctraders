<?php

extract(shortcode_atts(array(
    'h2' => '',
    'h4' => '',
    'position' => '',
    'txt_align' => '',
    'accent_color' => '',
    'link' => '',
    'title' => '',
    'type' => '',
    'icon' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',
    'color' => '',
    'el_class' => '',
    'animation' => '',
    'animation_delay' => 0
), $atts));

if( !empty($type) ){
    vc_icon_element_fonts_enqueue( $type );
    $icon = ${"icon_" . $type};
} 


$class = "call-to-action";
// $position = 'left';
// $width = '90';
// $style = '';
// $txt_align = 'right';
$link = ($link=='||') ? '' : $link;

$class .= ($position!='') ? ' button-'. $position : '';
$class .= ($txt_align!='') ? ' text-'. $txt_align : '';

$inline_css = ($accent_color!='') ? ' style="'.esc_attr( vc_get_css_color('background-color', $accent_color).vc_get_css_color('border-color', $accent_color) ).'"' : '';


$class .= $this->getExtraClass($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );

$data_attr = '';
if($animation) $data_attr = ' data-animation="'. esc_attr( $animation ) .'"';
if($animation_delay) $data_attr .= ' data-animation-delay="'. intval( $animation_delay ) .'"';

?>
<div<?php echo $inline_css; ?> class="<?php echo esc_attr( $css_class ); ?>"<?php echo $data_attr;?>>
    <?php if ($link!='' && $position!='bottom') echo do_shortcode('[link_button link="'.esc_url( $link ).'" title="'.esc_attr( $title ).'" color="'. esc_attr( $color ).'" icon="'.esc_attr( $icon ).'" style="square" size="large"]'); ?>
<?php if ($h2!='' || $h4!=''): ?>
    <hgroup>
        <?php if ($h2!=''): ?><h2 class="wpb_heading"><?php echo esc_html( $h2 ); ?></h2><?php endif; ?>
        <?php if ($h4!=''): ?><h4 class="wpb_heading"><?php echo esc_html( $h4 ); ?></h4><?php endif; ?>
    </hgroup>
<?php endif; ?>
    <?php echo wpb_js_remove_wpautop($content, true); ?>
    <?php if ($link!='' && $position=='bottom') echo do_shortcode('[link_button link="'.esc_url( $link ).'" title="'.esc_attr( $title ).'" color="'.esc_attr( $color ).'" icon="'. esc_attr( $icon ).'" style="square" size="large"]'); ?>
</div>
<?php $this->endBlockComment('.vc_call_to_action') . "\n";