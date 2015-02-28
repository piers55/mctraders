<?php
class Wyde_Posts_Slider {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('posts_slider', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
        global $wyde_blog_layout;

        $this->settings = shortcode_atts( array(
            'title' => '',
            'posts_query' => '',
            'count' => 10,
            'auto_play' => '',
            'auto_height' => '',
            'visible_items' => 1,
            'show_navigation' => '',
            'show_pagination' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
       ), $args );
       
        $wyde_blog_layout = 'medium';

        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ));

        if( $this->settings['title'] || $content){
            $html .= '<div class="content-header">';
            if($this->settings['title']) $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ));
            if($content) $html .= sprintf('<div class="post-desc">%s</div>', wpb_js_remove_wpautop($content, true));
            $html .= '</div>';
        }
       
        $html .= '<div class="view clear">';
        $html .= sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->slider_attributes() ));
                     
        
       
        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = - 1;

        list( $query_args, $loop ) = vc_build_loop_query( $this->settings['posts_query'] );
                    
        $query_args['has_password'] = false;
        $query_args['ignore_sticky_posts'] = 1;
        $query_args['posts_per_page'] = intval( $this->settings['count'] );

        $posts = new WP_Query( $query_args );
        while ($posts->have_posts() ) : $posts->the_post();
            $html .= '<div class="slide">';
            ob_start();
            get_template_part( 'content', get_post_format());
            $html .= ob_get_clean();
            $html .= '</div>';
                    
        endwhile;
       
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;

    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'posts-slider medium';


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

        $attrs['data-items'] = intval( $this->settings['visible_items'] );
        $attrs['data-auto-play'] = ($this->settings['auto_play']=='true'?'true':'false');
        $attrs['data-auto-height'] = ($this->settings['auto_height'] == 'true'?'true':'false');
        $attrs['data-navigation'] = ($this->settings['show_navigation']=='true'?'true':'false');
        $attrs['data-pagination'] = ($this->settings['show_pagination']=='true'?'true':'false');

        return $attrs;

    }
}

new Wyde_Posts_Slider();