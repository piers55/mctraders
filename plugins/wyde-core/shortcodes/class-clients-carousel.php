<?php
class Wyde_Clients_Carousel {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('clients_carousel', array( $this, 'render' ) );

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
            'image_size' => 'thumbnail',
	        'images' => array(),
            'auto_play' => '',
            'auto_height' => '',
            'visible_items' => 1,
            'loop' => '',
            'show_navigation' => '',
            'show_pagination' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
        ), $args );


        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ));
        if($this->settings['title']){
            $html .= '<div class="content-header">';
            $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ));
            $html .= '</div>';
        }

        $images = explode(',', $this->settings['images'] );

        $html .= sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->slider_attributes() ));

        foreach ($images as $image_id ){
            $html .= '<div>';
            $image_attrs = wp_get_attachment_image_src($image_id, $this->settings['image_size']);
            if($image_attrs[0]) $html .='<img src="'. esc_url( $image_attrs[0] ).'" alt="'. esc_attr( $this->settings['title'] ).'" />';
            $html .= '</div>';
        }
                        
       $html .= '</div>';
       $html .= '</div>';
       
       return $html;


    }

	function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'clients-carousel';

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }

		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

	function slider_attributes() {

        $attrs = array();

        $attrs['class'] = 'owl-carousel';

        $attrs['data-items'] =  intval( $this->settings['visible_items'] );
        $attrs['data-auto-play'] = ($this->settings['auto_play']=='true'?'true':'false');
        $attrs['data-auto-height'] = ($this->settings['auto_height']=='true'?'true':'false');
        $attrs['data-navigation'] = ($this->settings['show_navigation']=='true'?'true':'false');
        $attrs['data-pagination'] = ($this->settings['show_pagination']=='true'?'true':'false');
        $attrs['data-loop'] = ($this->settings['loop']=='true'?'true':'false');
        $attrs['data-item-margin'] = 5;

        return $attrs;

    }

}

new Wyde_Clients_Carousel();