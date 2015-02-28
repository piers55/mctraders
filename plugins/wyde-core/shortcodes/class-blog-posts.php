<?php   
class Wyde_Blog_Posts {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('blog_posts', array( $this, 'render' ) );

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
            'count' => 10,
            'view' => 'large',
            'columns' => 3,
            'pagination' => '1',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
        ), $args );
        
        $wyde_blog_layout = $this->settings['view'];
        
        $html = sprintf('<div%s>', WydeCore_Plugin::get_attributes($this->attributes()));

        if( $this->settings['title'] || $content){
            $html .= '<div class="content-header">';
            if($this->settings['title']) $html .= sprintf('<h2>%s</h2>', esc_html( $this->settings['title'] ) );
            if($content) $html .= sprintf('<div class="post-desc">%s</div>', wpb_js_remove_wpautop($content, true));
            $html .= '</div>';
        }

        $html .= '<div class="item-wrapper">';

        $html .= '<ul class="view row">';


        if(is_front_page()) {
	        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
	        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        } 

        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = - 1;

        $query_args = array(
            'posts_per_page'  => intval( $this->settings['count'] ),
            'paged' =>  intval( $paged )
        );

        $posts = new WP_Query( $query_args );
       
        $item_index = ($paged-1) * intval( $this->settings['count'] );

        $item_html = array();         
          
        $class_name = '';

        if($this->settings['view'] == 'masonry') $class_name = 'col-sm-'.  absint( floor(12/ intval( $this->settings['columns'] ) ) );

        while ($posts->have_posts() ) : $posts->the_post();

            $item_html[] = $this->get_content($this->settings['view'], $item_index, $class_name);
            
            $item_index++;

        endwhile;
                   
        wp_reset_postdata();

        $html .= implode("\n", $item_html);   
                        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= $this->get_pagination($this->settings['pagination'], $posts->max_num_pages);
        $html .= '</div>';
       
        return $html;

    }

	function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'blog-posts';

        if($this->settings['view']=='masonry'){
            $classes[] = 'grid';
        }

        $classes[] = $this->settings['view'];

        if($this->settings['pagination']=='2'){
            $classes[] = 'scrollmore';
        }

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }
        
		$attrs['class'] = implode(' ', $classes);

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }

    public function get_content($view, $item_index = 0, $class_name = ''){
        return wyde_get_post_preview($view, $item_index, $class_name);
    }

    public function get_pagination($pagination, $max_pages){
        ob_start();
        wyde_pagination($pagination, $max_pages);
        return ob_get_clean();
    }

    public function get_masonry_layout(){
        return wyde_get_post_masonry_layout();
    }

}

new Wyde_Blog_Posts();