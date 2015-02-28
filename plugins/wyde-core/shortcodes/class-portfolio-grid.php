<?php
  
class Wyde_Portfolio_Grid extends WPBakeryShortCode{

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('portfolio_grid', array( $this, 'render' ) );

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
            'view' => '',
            'hover_effect' => 'apollo',
            'hide_filter' => '',
            'show_more'   => '',
            'columns' => 4,
            'posts_query' => '',
            'count' => 12,
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

        if( $this->settings['hide_filter'] != 'true' ){
            
            $html .='<div class="post-filter clear">';
            $html .='<ul class="filter">';
            $html .='<li class="selected"><a href="#all" title="">All</a></li>';

            $terms = get_terms('portfolio_category');

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

        $html .= sprintf('<ul class="view effect-%s row">', esc_attr( $this->settings['hover_effect'] ));


        global $paged;
        
        if( is_front_page() || is_home() ) {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}    
           
        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = 12;

        list( $query_args, $loop ) = vc_build_loop_query( $this->settings['posts_query'] );

        $query_args['post_type'] = 'portfolio';
        $query_args['paged'] = intval( $paged );
        $query_args['has_password'] = false;
        $query_args['posts_per_page'] = intval( $this->settings['count'] );

        $portfolio_posts = new WP_Query( $query_args );

        $item_index = ($paged-1) * intval( $this->settings['count'] );

        $item_html = array();
        

        $class_name = '';

        if($this->settings['view'] != 'masonry') $class_name = 'col-sm-'.  absint( floor(12/ intval( $this->settings['columns'] ) ) );

        while ($portfolio_posts->have_posts() ) : $portfolio_posts->the_post();
            
            $item_html[] = $this->get_content($this->settings['view'], $item_index, $class_name);
            
            $item_index++;
                    
        endwhile;

        wp_reset_postdata();

        $html .= implode("\n", $item_html);   
                        
        $html .= '</ul>';
        $html .= '</div>';
        if( $this->settings['show_more'] == 'true' ){
            $html .= $this->get_pagination('infinitescroll', $portfolio_posts->max_num_pages);;
        }
        $html .= '</div>';

        return $html;

    }


	public function attributes() {

		$attrs = array();

        $classes = array();

        $classes[] = 'portfolio-grid portfolio grid';

        $classes[] = $this->settings['view'];

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

    public function get_content($view, $item_index = 0, $class_name = ''){
        return wyde_get_portfolio_preview($view, $item_index, $class_name);
    }

    public function get_pagination($pagination, $max_pages){
        ob_start();
        wyde_infinitescroll($max_pages);
        return ob_get_clean();
    }

    public function get_masonry_layout(){
        return wyde_get_portfolio_masonry_layout();
    }
}

new Wyde_Portfolio_Grid();