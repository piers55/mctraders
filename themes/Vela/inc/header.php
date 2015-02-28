<?php
function wyde_get_header($allow_transparency=true){

global $wyde_options, $wyde_page_id;
    
$header_classes = array();

$header_classes[] = 'header-v'.$wyde_options['header_layout'];

$menu_style = get_post_meta( $wyde_page_id, '_meta_menu_style', true );

if($menu_style == ''){
    $menu_style = $wyde_options['menu_style'];
}

if( !empty($menu_style) ) {
   $header_classes[] = $menu_style;
}

if($wyde_options["header_sticky"]){
    $header_classes[] = 'sticky';
} 

if(!$allow_transparency && $wyde_options["header_position"] == '2'){
    $header_classes[] = 'below-slider';
}

if($allow_transparency && $wyde_options["header_transparent"]){
    $header_classes[] = 'transparent';
} 

if($wyde_options["header_fluid"] || $wyde_options["header_layout"]=='5'){
    $header_classes[] = 'full';
}

if( intval($wyde_options["header_layout"]) > 2){
    $header_classes[] = 'logo-center';
}

if(wp_is_mobile()){
    $header_classes[] = 'mobile';
} 

?>
<header id="header" class="<?php echo esc_attr(  implode(' ', $header_classes) ); ?>">
    <div class="header-wrapper">
        <?php
        get_template_part( 'inc/headers/header', 'v'. intval( $wyde_options['header_layout'] ) );
        ?> 
    </div>
</header>
<?php
}
?>