<?php
class Wyde_Team_Slider {

    public $settings;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('team_slider', array( $this, 'render' ) );

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
            'auto_height' => '',
            'visible_items' => 1,
            'show_navigation' => '',
            'show_pagination' => '',
            'animation' =>  '',
            'animation_delay' =>  '',
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

        if ( $this->settings['count'] != '' && ! is_numeric( $this->settings['count'] ) ) $this->settings['count'] = - 1;

        $query_args = array(
            'post_type' => 'team-member',
            'posts_per_page'  => intval( $this->settings['count'] ),
            'has_password' => false
        );

        $posts = new WP_Query( $query_args );

        $item_html = array();

        while ($posts->have_posts() ) : $posts->the_post();
             
        $item_html[] = $this->get_content();
                           
        endwhile;
       
        $html .= implode("\n", $item_html);   
                        
        $html .= '</div>';
        $html .= '</div>';
       
        return $html;


    }


	public function attributes() {

        $attrs = array();

        $classes = array();

        $classes[] = 'team-slider';


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
        $attrs['data-auto-height'] = ($this->settings['auto_height']=='true'?'true':'false');
        $attrs['data-navigation'] = ($this->settings['show_navigation']=='true'?'true':'false');
        $attrs['data-pagination'] = ($this->settings['show_pagination']=='true'?'true':'false');

        return $attrs;

    }

    function get_content(){
    
        $item_html = '<div class="team-member">';
        $item_html .= '<div class="member-image">';
    
        $size = 'medium';

        if( intval( $this->settings['visible_items'] ) < 3) $size = 'large';
        
        $item_html .= '<span>';

        $image = get_post_meta( get_the_ID(), '_meta_member_image', true);

        if($image){
            $image_id = get_post_meta( get_the_ID(), '_meta_member_image_id', true);
            $image_attr = wp_get_attachment_image_src($image_id, $size);
            if($image_attr[0]) $image = $image_attr[0];
        } 
    
        if($image){
            $item_html .= sprintf('<img src="%s" alt="%s" />', esc_url( $image ), esc_attr( get_the_title() ));
        }

        $item_html .= '</span>';

	    $item_html .= '<p class="social-link">';
    
        $email =  get_post_meta( get_the_ID(), '_meta_member_email', true );
        $website =  get_post_meta( get_the_ID(), '_meta_member_website', true );

        if($email){
            $item_html .= '<a href="mailto:'.sanitize_email( $email ).'" title="Email" target="_blank" class="tooltip-item"><i class="fa fa-envelope"></i></a>';
        }
        if($website){
            $item_html .= '<a href="'.esc_url( $website ).'" title="Website" target="_blank" class="tooltip-item"><i class="fa fa-globe"></i></a>';
        }

        $socials_icons = wyde_get_social_icons();
        $socials = get_post_meta( get_the_ID(), '_meta_member_socials', true );

        foreach ( (array) $socials as $key => $entry ) {

            if ( isset( $entry['social'] ) )
            $item_html .= '<a href="'.esc_url( $entry['url'] ).'" title="'.esc_attr( get_the_title() .' on '. $entry['social'] ).'" target="_blank" class="tooltip-item"><i class="fa '.esc_attr( array_search($entry['social'], $socials_icons) ).'"></i></a>';

        }
    
    
        $item_html .= '</p>';
        $item_html .= '</div>';
        $item_html .= '<div class="member-detail">';
        $item_html .= '<h4>';
        $item_html .= esc_html( get_the_title() );
        $item_html .= '</h4>';
        $item_html .= '<p class="member-meta">';
        $item_html .= esc_html( get_post_meta( get_the_ID(), '_meta_member_position', true ) );
        $item_html .= '</p>';


        $item_html .= '<div class="member-content">';
        $item_html .= '<p>'.esc_html( get_the_content() ).'</p>';
        $item_html .= '</div>';
        $item_html .= '</div>';
    
        $item_html .='</div>';
    
        return $item_html;
    }

}

new Wyde_Team_Slider();