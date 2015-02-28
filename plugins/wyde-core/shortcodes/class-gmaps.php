<?php
class Wyde_GMaps {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('gmaps', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
        $this->settings = shortcode_atts( array(
            'height' => '300',
            'gmaps' => '',
            'icon'  => '',
            'color'  => '',
            'el_class' => ''
        ), $args );

        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ) );
        $html .= '<div class="map-canvas"></div>';
        $html .='</div>';

        return $html;

    }

	function attributes() {
        
        global $wyde_color_scheme;

        $attrs = array();

        $classes = array();

        $classes[] = 'gmaps';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }
        
		$attrs['class'] = implode(' ', $classes);

        $icon_id = preg_replace( '/[^\d]/', '', $this->settings['icon'] );

        $icon_url = wp_get_attachment_url($icon_id);

        if(!$icon_url) $icon_url = get_template_directory_uri().'/images/pin.png';


        if(!empty($icon_url)) $attrs['data-icon'] = esc_url( $icon_url );

        $attrs['data-maps'] = $this->settings['gmaps'];

        if ( !isset($this->settings['color'])) $this->settings['color'] = $wyde_color_scheme;
        
        $attrs['data-color'] = $this->settings['color'];

        $this->settings['height'] = str_replace( array( 'px', ' ' ), array( '', '' ), $this->settings['height'] );
        if ( is_numeric( $this->settings['height'] ) ){
            $attrs['data-height'] = absint( $this->settings['height'] );
        } 
        
        return $attrs;

    }

}

new Wyde_GMaps();