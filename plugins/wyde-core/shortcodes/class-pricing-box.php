<?php
class Wyde_Pricing_Box {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('pricing_box', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
        $this->settings = shortcode_atts( array(
            'heading' => '',
            'sub_heading' => '',
            'price' =>  '',
            'price_unit' =>  '',
            'button_text' =>  '',
            'link' =>  '',
            'bg_color' =>  '',
            'text_color' =>  '',
            'featured' =>  '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
       ), $args );
       

       $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ) );
       
       if($this->settings['heading']){
           $html .= '<div class="price-heading">';
           $html .= sprintf('<h3>%s</h3>', esc_html( $this->settings['heading'] ));
           $html .= sprintf('<h5>%s</h5>', esc_html( $this->settings['sub_heading'] ));
           $html .= '</div>';
       }
       
       if($this->settings['price']){ 
           $html .= '<div class="price-block">';
           $html .= sprintf('<h4 class="price-value">%s</h4>', esc_html( $this->settings['price'] ));
           $html .= sprintf('<span class="price-unit">%s</span>', esc_html( $this->settings['price_unit'] ));
           $html .= '</div>';
       }

       $html .= '<div class="price-content">';
       if($content) $html .= wpb_js_remove_wpautop($content, true); 
       $html .= '</div>';

       $html .= '<div class="price-button">';
       if($this->settings['button_text']){
            
            $link = ( $this->settings['link'] == '||' ) ? '' : $this->settings['link'];
            $link = vc_build_link( $link );

            $html .= sprintf('<a href="%s" title="%s" target="%s">%s</a>', esc_url( $link['url'] ), esc_attr( $link['title'] ), esc_attr( $link['target'] ), esc_html( $this->settings['button_text'] ));

       }
       $html .= '</div>';
       $html .= '</div>';

       return $html;


    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'pricing-box';

        if($this->settings['featured'] == 'true') $classes[] = 'featured';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }


        $attrs['class'] = implode(' ', $classes);

        $styles = array();
        if($this->settings['bg_color']) $styles[] = 'background-color:'.$this->settings['bg_color'];
        if($this->settings['text_color']) $styles[] = 'color:'.$this->settings['text_color'];

        $attrs['style'] = implode(';', $styles);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

}

new Wyde_Pricing_Box();