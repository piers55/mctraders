<?php
class Wyde_Dropcap {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('dropcap', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
		$this->settings = shortcode_atts( array(
          'color'  => '',
       ), $args );

       return sprintf('<span%s>%s</span>', WydeCore_Plugin::get_attributes( $this->attributes() ), wpb_js_remove_wpautop($content, true));
       
    }

	function attributes() {

        $attrs = array();

		$attrs['class'] = 'dropcap';

        if($this->settings['color']){
            $attrs['style'] = 'color:'. $this->settings['color'];
        }

        return $attrs;

    }

}

new Wyde_Dropcap();