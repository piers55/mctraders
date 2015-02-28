<?php
class Wyde_Donut_Chart {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('donut_chart', array( $this, 'render' ) );

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
            'type'  => '',
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
        
        $html .= '</div>';

        return $html;


    }


	public function attributes() {

        global $wyde_color_scheme, $vc_is_inline;

		$attrs = array();

        $classes = array();

        $classes[] = 'donut-chart';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }

        if( $this->settings['css'] ) $classes[] = vc_shortcode_custom_css_class( $this->settings['css'], '' );

		$attrs['class'] = implode(' ', $classes);

        
        if($this->settings['value']){
            $attrs['data-value'] =  intval( $this->settings['value'] );
        } 
        
        if($this->settings['label_mode'] == 'icon'){
            $attrs['data-icon'] = $this->settings['icon'];
        }else{
            if($this->settings['label']){
                $attrs['data-label'] = $this->settings['label'];
            }
        }

        if($this->settings['title']){
            $attrs['data-title'] = $this->settings['title'];
        } 

        if($this->settings['style']){
            $attrs['data-border'] = $this->settings['style'];
        } 

        if($this->settings['bar_color']){
            $attrs['data-color'] = $this->settings['bar_color'];
        }else{
            $attrs['data-color'] = $wyde_color_scheme;            
        } 

        if($this->settings['bar_border_color']){
            $attrs['data-bgcolor'] = $this->settings['bar_border_color'];
        } 

        if($this->settings['fill_color']){
            $attrs['data-fill'] = $this->settings['fill_color'];
        } 

        if($this->settings['start']){
            $attrs['data-startdegree'] = $this->settings['start'];
        } 

        if($this->settings['type']){
            $attrs['data-type'] = $this->settings['type'];
        } 

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

}

new Wyde_Donut_Chart();