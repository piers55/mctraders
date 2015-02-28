<?php 
class Wyde_Icon_Block {

    public $settings;
    public $attrs;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('icon_block', array( $this, 'render' ) );

    }

    /**
     * Render the shortcode
     * @param  array $args     Shortcode paramters
     * @param  string $content Content between shortcode
     * @return string          HTML output
     */
    function render( $args, $content = '' ) {
        
		$this->settings = shortcode_atts( array(
            'type' => '',
            'icon_set' => '',
            'icon' => '',
	        'icon_openiconic' => '',
	        'icon_typicons' => '',
	        'icon_entypoicons' => '',
	        'icon_linecons' => '',
	        'icon_entypo' => '',
            'style' => '',
            'size' => '',
            'color' => '',
            'hover' =>  '',
            'link'    => '',
            'animation' =>  '',
            'animation_delay' =>  0,
            'el_class' =>  '',
            'css' => ''
       ), $args );

        if(!empty($this->settings['icon_set'])){
            vc_icon_element_fonts_enqueue( $this->settings['icon_set'] );
            $this->settings['icon'] = $this->settings['icon_' . $this->settings['icon_set']];
        } 

       $link = ( $this->settings['link'] == '||' ) ? '' : $this->settings['link'];
       
       $link = vc_build_link( $link );

       $link_attr = '';
       if($link['target'] != '') $link_attr .= ' target="'.esc_attr( $link['target'] ).'"';
       
       if($link['title'] != '') $this->settings['tooltip'] = $link['title'];

       $this->attrs = $this->attributes();

       $inline_css = '';
       if( isset( $this->attrs['style'] )){
           $inline_css = ' style="'.esc_attr( $this->attrs['style'] ).'"';
       }

       $html = sprintf('<span%s>', WydeCore_Plugin::get_attributes( $this->attrs ));
       if($link['url'])  $html .= sprintf('<a href="%s"%s>', esc_url( $link['url'] ), $link_attr);
       if($this->settings['icon']) $html .= sprintf('<i class="%s"></i>', esc_attr( $this->settings['icon'] ));
       if($link['url']) $html .= '</a>';
       $html .= sprintf('<span class="border"%s></span>', $inline_css);
       $html .= '</span>';

       return $html;


    }
    public function attributes(){
        
        $attrs = array();

        $classes = array();

        $classes[] = 'icon-block';

        $classes[] = 'effect-'.$this->settings['hover'];
        $classes[] = 'icon-'.$this->settings['size'];
        $classes[] = 'icon-'.$this->settings['style'];

        if($this->settings['el_class']){
            $classes[] = $this->settings['el_class'];
        }

        if( $this->settings['css'] ) $classes[] = vc_shortcode_custom_css_class( $this->settings['css'], '' );

		$attrs['class'] = implode(' ', $classes);

        if( ! empty( $this->settings['color'] ) ){
            $attrs['style'] = 'background-color:'.$this->settings['color'].';border-color:'.$this->settings['color'].';';
        }

        if( isset( $this->settings['tooltip'] )) $attrs['title'] = $this->settings['tooltip'];

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }
}

new Wyde_Icon_Block();