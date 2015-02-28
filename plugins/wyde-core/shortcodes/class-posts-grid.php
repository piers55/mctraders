<?php
class Wyde_Posts_Grid {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('posts_grid', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
        global $wyde_blog_layout, $paged;

        $this->settings = shortcode_atts( array(
            'title' => '',
            'posts_query' => '',
            'count' => 10,
            'columns' => 3,
            'hide_filter' => '',
            'show_more' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
        ), $args );

        $wyde_blog_layout = 'grid';

        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes( $this->attributes() ));

        if( $this->settings['title'] || $content){
            $html .= '<div class="content-header">';
            if($this->settings['title']) $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ));
            if($content) $html .= sprintf('<div class="post-desc">%s</div>', wpb_js_remove_wpautop($content, true));
            $html .= '</div>';
        }

        if($this->settings['hide_filter'] != 'true'){
 
            $html .= '<div class="post-filter">';
            $html .= '<ul class="filter clear">';
            $html .= '<li><a href="#all" title="" class="selected">All</a></li>';
            
            $terms = get_terms("category");

            if (count($terms))
            {   
                foreach ( $terms as $term ) {
                    $term_link = urldecode($term->slug);
                    $html .= sprintf('<li><a href="#%s" title="">%s</a></li>', esc_attr( $term_link ), esc_html( $term->name ));

                }
            }
            $html .='</ul>';
            $html .='</div>';
        }

        $html .= '<div class="item-wrapper">';
        $html .= '<ul class="view row">';
                     
        
        if( is_front_page() || is_home() ) {
	        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
        } else {
	        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }     
           
        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = - 1;

        list( $query_args, $loop ) = vc_build_loop_query( $this->settings['posts_query'] );
                
        $query_args['paged'] = intval( $paged );
        $query_args['has_password'] = false;
        $query_args['ignore_sticky_posts'] = 1;
        $query_args['posts_per_page'] = intval( $this->settings['count'] );       
            
        $posts = new WP_Query( $query_args );
        while ($posts->have_posts() ) : $posts->the_post();

            $html .= $this->get_content();

        endwhile;
        wp_reset_postdata();
           
        $html .= '</ul>';
        $html .='</div>';
        if($this->settings['show_more'] == 'true'){
            $html .= $this->get_pagination();
        }
        $html .='</div>';

        return $html;

    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'posts-grid grid';

        $classes[] = 'scrollmore';

        if($this->settings['hide_filter'] != 'true'){
            $classes[] = 'filterable';
        } 
       

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }
        
		$attrs['class'] = implode(' ', $classes);


        if($this->settings['show_more'] == 'true'){
            $attrs['data-trigger'] = "false";
        } 

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

    public function get_content($item_index = 0, $class_name = ''){
        $categories = get_the_category();
        $item_classes = array();     
        $item_classes[] = 'col-sm-'.  absint( floor(12/ intval( $this->settings['columns'] ) ) );
        if ( is_array( $categories ) ) { 
            foreach ( $categories as $item ) 
            {
                $item_classes[] = urldecode($item->slug);
            }
        }

        return wyde_get_post_preview($item_index, implode(' ', $item_classes) );
    }

    public function get_pagination($pagination, $max_pages){
        ob_start();
        wyde_pagination($pagination, $max_pages);
        return ob_get_clean();
    }
}

new Wyde_Posts_Grid();