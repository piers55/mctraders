<?php 
class Wyde_Counter_Box {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('counter_box', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
	    $this->settings = shortcode_atts( array(
            'title' =>  '',
            'icon_set' => '',
            'icon' => '',
	        'icon_openiconic' => '',
	        'icon_typicons' => '',
	        'icon_entypoicons' => '',
	        'icon_linecons' => '',
	        'icon_entypo' => '',
            'start' => '0',
            'value' => '100',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
        ), $args );

        if(!empty($this->settings['icon_set'])){
            vc_icon_element_fonts_enqueue( $this->settings['icon_set'] );
            $this->settings['icon'] = $this->settings['icon_' . $this->settings['icon_set']];
        } 

        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ) );
        $html .= sprintf('<p data-value="%s">%s</p>', esc_attr(  intval( $this->settings['value'] ) ), intval( $this->settings['start'] ) );
       
        if($this->settings['icon']) $html .= sprintf('<span><i class="fa %s"></i></span>', esc_attr( $this->settings['icon'] ));
        if($this->settings['title']) $html .= sprintf('<h4>%s</h4>', esc_html( $this->settings['title'] ));

        $html .= '</div>';

        return $html;

    }

	function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'counter-box';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }

		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

}

new Wyde_Counter_Box();