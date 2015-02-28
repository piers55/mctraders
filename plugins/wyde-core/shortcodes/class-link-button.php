<?php
class Wyde_Link_Button {

    public $settings;
    public $attrs;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('link_button', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
		$this->settings = shortcode_atts( array(
	        'link' => '',
	        'title' => '',
	        'color' => '',
	        'hover_color' => '',
            'icon_set' => '',
            'icon' => '',
	        'icon_openiconic' => '',
	        'icon_typicons' => '',
	        'icon_entypoicons' => '',
	        'icon_linecons' => '',
	        'icon_entypo' => '',
	        'size' => '',
	        'style' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
	        'el_class' => ''
        ), $args );

        if(!empty($this->settings['icon_set'])){
            vc_icon_element_fonts_enqueue( $this->settings['icon_set'] );
            $this->settings['icon'] = $this->settings['icon_' . $this->settings['icon_set']];
        } 

        $link = ( $this->settings['link'] == '||' ) ? '' : $this->settings['link'];
       
        $link = vc_build_link( $link );

        $link_attr = '';
        $link_target = trim( $link['target'] );
        if($link_target != '') $link_attr .= ' target="'.esc_attr( $link_target ).'"';

        $inline_css = '';
        if( !empty( $this->settings['color'] )){
            $inline_css = ' style="background-color:'.esc_attr( $this->settings['color'] ).';"';
        }

        $html = sprintf('<a href="%s"%s%s>', esc_url( $link['url'] ), $link_attr, WydeCore_Plugin::get_attributes( $this->attributes() ));
        $html .= sprintf('<span%s></span>', $inline_css);
        if($this->settings['icon']) $html .= sprintf('<i class="%s"></i>', esc_attr( $this->settings['icon'] ));
        if($this->settings['title']) $html .= esc_html( $this->settings['title'] );
        $html .= '</a>';

        return $html;


    }
    public function attributes(){
        
        $attrs = array();

        $classes = array();

        $classes[] = 'link-button';

        if( !empty( $this->settings['size'] ) ){
            $classes[] = $this->settings['size'];
        } 

        if( !empty( $this->settings['style'] ) ){
            $classes[] = $this->settings['style'];
        } 

        if( !empty( $this->settings['el_class'] ) ){
            $classes[] = $this->settings['el_class'];
        }


		$attrs['class'] = implode(' ', $classes);

        if( !empty( $this->settings['color'] ) ){
            $attrs['style'] = 'color:'.$this->settings['color'].';border-color:'.$this->settings['color'].';';
        }

        if( !empty( $this->settings['hover_color'] ) ){
            $attrs['data-hover-color'] = $this->settings['hover_color'];
        }

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }
}

new Wyde_Link_Button();