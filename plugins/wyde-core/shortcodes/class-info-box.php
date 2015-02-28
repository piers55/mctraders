<?php
class Wyde_Info_Box {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('info_box', array( $this, 'render' ) );

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
            'icon_set' => '',
            'icon' => '',
	        'icon_openiconic' => '',
	        'icon_typicons' => '',
	        'icon_entypoicons' => '',
	        'icon_linecons' => '',
	        'icon_entypo' => '',
            'style' => 'square',
            'icon_size' => '',
            'icon_position' => '',
            'color'   => '',
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
       
        $icon_col = $content_col = ' col-sm-12';
       
        if($this->settings['icon_position'] != 'top'){
            $icon_col = ' col-sm-3';
            $content_col = ' col-sm-9';
        }

       $inline_css = '';
       if( !empty( $this->settings['color'] ) ){
           $inline_css = sprintf(' style="border-color:%1$s;color:%1$s;"', esc_attr( $this->settings['color'] ));
       }

       $html .= sprintf('<div class="box-icon%s"%s>', $icon_col, $inline_css);
       if( !empty( $this->settings['icon'] ) ) $html .= sprintf('<span class="icon-wrapper"><i class="%s"></i></span>', esc_attr( $this->settings['icon'] ));
       $html .= '</div>';
       $html .= sprintf('<div class="box-content%s">',  $content_col);
       if($this->settings['title']) $html .= sprintf('<h3>%s</h3>', esc_html( $this->settings['title'] ));
       if($content) $html .= wpb_js_remove_wpautop($content, true); 
       $html .= '</div>';

       $html .= '</div>';

       return $html;

    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'info-box clear';

        if( !empty($this->settings['icon_position'])) $classes[] = 'icon-'.$this->settings['icon_position'];
        if( !empty($this->settings['icon_size'])) $classes[] = 'icon-'.$this->settings['icon_size'];        
        if( !empty($this->settings['style'])) $classes[] = 'border-'.$this->settings['style'];

        if(!empty( $this->settings['el_class'] )){
            $classes[] = $this->settings['el_class'];
        }

        if( $this->settings['css'] ) $classes[] = vc_shortcode_custom_css_class( $this->settings['css'], '' );

		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

}

new Wyde_Info_Box();