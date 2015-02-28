<?php
class Wyde_Half_Donut_Chart {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('half_donut_chart', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
        $this->settings = shortcode_atts( array(
            'title' => '',
            'value' => 80,
            'label_mode' => '',
            'label'  => '',
            'icon_set' => '',
            'icon' => '',
	        'icon_openiconic' => '',
	        'icon_typicons' => '',
	        'icon_entypoicons' => '',
	        'icon_linecons' => '',
	        'icon_entypo' => '',
            'style' => '',
            'bar_color'   => '',
            'bar_border_color'   => '',
            'fill_color'   => '',
            'start'   => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
            'css' =>  '',
        ), $args );


        if(!empty($this->settings['icon_set'])){
            vc_icon_element_fonts_enqueue( $this->settings['icon_set'] );
            $this->settings['icon'] = $this->settings['icon_' . $this->settings['icon_set']];
        } 

        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ) );

        $html .= do_shortcode(sprintf('[donut_chart value="%s" label_mode="%s" label="%s" icon="%s" style="%s" bar_color="%s" bar_border_color="%s" fill_color="%s" start="%s" type="half"]',
                intval( $this->settings['value'] ), 
                esc_attr( $this->settings['label_mode'] ), 
                esc_attr( $this->settings['label'] ), 
                esc_attr( $this->settings['icon'] ), 
                esc_attr( $this->settings['style'] ), 
                esc_attr( $this->settings['bar_color'] ), 
                esc_attr( $this->settings['bar_border_color'] ), 
                esc_attr( $this->settings['fill_color'] ), 
                esc_attr( $this->settings['start'] )
        ));

        if( $this->settings['title'] || $content){
            $html .= '<div class="chart-content">';
            if($this->settings['title']) $html .= sprintf('<h3>%s</h3>', esc_html( $this->settings['title'] ));
            if($content) $html .= wpb_js_remove_wpautop($content, true);
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;


    }

    public function attributes() {

		$attrs = array();

        $classes = array();

        $classes[] = 'half-donut-chart';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }

        if( $this->settings['css'] ) $classes[] = vc_shortcode_custom_css_class( $this->settings['css'], '' );

		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }


}

new Wyde_Half_Donut_Chart();