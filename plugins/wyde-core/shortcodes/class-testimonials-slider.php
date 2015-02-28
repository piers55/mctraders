<?php
class Wyde_Testimonials_Slider {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('testimonials_slider', array( $this, 'render' ) );

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
            'count' => 10,
            'auto_play' => '',
            'visible_items' => 1,
            'show_navigation' => '',
            'show_pagination' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
       ), $args );


        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ));

        if( $this->settings['title'] || $content){
            $html .= '<div class="content-header">';
            if($this->settings['title']) $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ));
            if($content) $html .= sprintf('<div class="post-desc">%s</div>', wpb_js_remove_wpautop($content, true));
            $html .= '</div>';
        }
               
        $html .= sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->slider_attributes() ));
        $html .= '<ul class="slides">';
        
        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = - 1;
        
        $query_args = array(
            'post_type' => 'testimonial',
            'posts_per_page'  => intval( $this->settings['count'] ),
            'has_password' => false
        );

        $posts = new WP_Query( $query_args );
        while ($posts->have_posts() ) : $posts->the_post();
             
        $item_html[] = $this->get_content();
                           
        endwhile;
       
        $html .= implode("\n", $item_html);   
                        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
       
        return $html;


    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'testimonials-slider';

        if($this->settings['show_navigation'] == 'true') $classes[] = 'show-navigation';    

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

        $attrs['class'] = 'flexslider';

        $attrs['data-items'] = intval( $this->settings['visible_items'] );
        $attrs['data-auto-play'] = ($this->settings['auto_play']=='true'?'true':'false');
        $attrs['data-auto-height'] = 'false';
        $attrs['data-navigation'] = ($this->settings['show_navigation']=='true'?'true':'false');
        $attrs['data-pagination'] = ($this->settings['show_pagination']=='true'?'true':'false');
        $attrs['data-effect'] = "fade";

        return $attrs;

    }

    function get_content(){
    
        $item_html = '<li>';
        $item_html .= '<div class="testimonial">';
        $item_html .= '<div class="testimonial-content">';
        $item_html .= '<i class="post-format-icon fa fa-quote-right"></i>';
        $item_html .= '<p>'.esc_html( get_the_content() ).'</p>';
        $item_html .= '</div>';
        $item_html .= '<div class="testimonial-meta">';
        $item_html .= '<div class="testimonial-name">';
        $item_html .= '<h4>';
        $item_html .= esc_html( get_the_title() );
        $item_html .= '</h4>';
        $item_html .= '<p>';
    
        $position =  get_post_meta( get_the_ID(), '_meta_testimonial_position', true );
        $company =  get_post_meta( get_the_ID(), '_meta_testimonial_company', true );
        $website =  get_post_meta( get_the_ID(), '_meta_testimonial_website', true );


        if($position) $item_html .= '<span>'.esc_html( $position ).'</span>';
    
        if($company){
            $item_html .= ' — ';
            if($website) $item_html .= '<a href="'.esc_url( $website ).'" target="_blank">';
            $item_html .= esc_html( $company );
            if($website) $item_html .= '</a>';
        }

        $item_html .= '</p>';
        $item_html .= '</div>';

        $image = get_post_meta( get_the_ID(), '_meta_testimonial_image', true);
    
        if($image){
            $image_id = get_post_meta( get_the_ID(), '_meta_testimonial_image_id', true);
            $image_attr = wp_get_attachment_image_src($image_id, array(150, 150));
            if($image_attr[0]) $image = $image_attr[0];
        } 
    
        if($image){
            $item_html .= sprintf('<div class="image-border"><img src="%s" alt="%s" /></div>', esc_url( $image ), esc_attr( get_the_title() ));
        }

        $item_html .= '</div>';
        $item_html .= '</div>';
        $item_html .= '</li>';
    
        return $item_html;
    }

}

new Wyde_Testimonials_Slider();