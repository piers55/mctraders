<?php
class Wyde_Heading {

    public $settings = array();

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('heading', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
		$this->settings = shortcode_atts( array(
            'title'   => '',
            'sub_title'   => '',
            'style'  => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
       ), $args );


       $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ));
       if($this->settings['title']) $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ));
       if($this->settings['sub_title']) $html .= sprintf('<span class="sub-title">%s</span>', esc_html( $this->settings['sub_title'] ));
       $html .='</div>';

       return $html;


    }

	function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'heading';

        if($this->settings['style']){
            $classes[] = 'title-'.$this->settings['style'];
        }

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }
        
		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

}

new Wyde_Heading();