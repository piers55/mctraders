<?php
    global $wyde_options;
    
?>
<div class="header">
    <div class="container">
        <div class="mobile-nav-icon">
            <i class="fa fa-bars"></i>
        </div>            
        <span id="logo">
            <?php
            if( isset( $wyde_options['logo_image']['url'] )){
                
                $logo = $wyde_options['logo_image']['url'];
                $logo_retina =  $wyde_options['logo_image_retina']['url'];

                $sticky_logo =  $wyde_options['logo_image_sticky']['url']? $wyde_options['logo_image_sticky']['url']:$wyde_options['logo_image']['url'];
                $sticky_logo_retina = $wyde_options['logo_image_sticky_retina']['url']?$wyde_options['logo_image_sticky_retina']['url']:$wyde_options['logo_image_retina']['url'];

                $logo_height = isset( $wyde_options['logo_dimensions']['height'] )? $wyde_options['logo_dimensions']['height']:'25';
                $sticky_logo_height = isset( $wyde_options['logo_sticky_dimensions']['height'] )? $wyde_options['logo_sticky_dimensions']['height']:'16';
            ?>
            <a href="<?php echo esc_url( site_url() ); ?>">
                <img class="normal-logo" src="<?php echo esc_url( $logo ); ?>"<?php echo $logo_retina? ' data-retina="'.esc_url( $logo_retina ).'"':'';?> alt="Logo" style="height: <?php echo esc_attr( $logo_height ); ?>" />
                <img class="sticky-logo" src="<?php echo esc_url( $sticky_logo ); ?>"<?php echo $sticky_logo_retina? ' data-retina="'.esc_url( $sticky_logo_retina ).'"':'';?> alt="Logo" style="height: <?php echo esc_attr( $sticky_logo_height ); ?>" />
            </a>
            <?php
            }
            ?>
        </span>
        <div class="nav-wrapper">
            <nav id="nav" class="nav <?php echo wp_is_mobile()?'mobile-nav':'dropdown-nav'?>">
                <ul class="menu">
                    <?php wyde_primary_menu(); ?>
                </ul>
            </nav>
            <?php if( $wyde_options['menu_shop_cart'] && function_exists('wyde_woocommerce_menu')){ ?>
            <ul id="shop-menu">
            <?php 
            echo wyde_woocommerce_menu(); 
            ?>
            </ul>
            <?php } ?>
            <?php if($wyde_options['menu_search_icon']){ ?>
            <div id="search">
                <?php get_template_part('/inc/ajax-search');?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>