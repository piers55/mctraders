<?php
class Wyde_Button {

    public $settings;
    public $attrs;

    /**
     * Initiate the shortcode
     */
    public function __construct() {

        add_shortcode('button', array( $this, 'render' ) );

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
	        'size' => '',
	        'style' => '',
            'animation' =>  '',
            'animation_delay' =>  0,
	        'el_class' => ''
        ), $args );

        $link = ( $this->settings['link'] == '||' ) ? '' : $this->settings['link'];
       
        $link = vc_build_link( $link );

        $this->attrs = $this->attributes();

        $inline_css = '';
        if( isset( $this->attrs['style'] )){
            $inline_css = ' style="'. esc_attr( $this->attrs['style'] ).'"';
        }

        $link_attr = '';
        $link_target =  trim( $link['target'] );
        if($link_target != '') $link_attr .= ' target="'. esc_attr( $link_target ) .'"';


        $html = sprintf('<a href="%s"%s%s>', esc_url( $link['url'] ), $link_attr, WydeCore_Plugin::get_attributes( $this->attrs ));
        if($this->settings['title']) $html .= esc_html( $this->settings['title'] );
        $html .= '</a>';

        return $html;


    }
    public function attributes(){
        
        $attrs = array();

        $classes = array();

        switch($this->settings['style']){
            case 'round':
                $classes[] = 'button round';
            break;
            case 'outline':
                $classes[] = 'ghost-button';
            break;
            case 'round-outline':
                $classes[] = 'ghost-button round';
            break;
            default:
                $classes[] = 'button';
            break;
        }

        if( !empty( $this->settings['size'] ) ){
            $classes[] = $this->settings['size'];
        } 
        

        if( !empty( $this->settings['el_class'] )){
            $classes[] = $this->settings['el_class'];
        }

		$attrs['class'] = implode(' ', $classes);

        if( !empty( $this->settings['color'] ) ){
            if($this->settings['style'] == 'outline' || $this->settings['style'] == 'round-outline'){
                $attrs['style'] = 'border-color:'.$this->settings['color'].';color:'.$this->settings['color'].';';
            }else{
                $attrs['style'] = 'border-color:'.$this->settings['color'].';background-color:'.$this->settings['color'].';';
            }
        }

        if($this->settings['animation']) $attrs['data-animation'] = $this->settings['animation'];
        if($this->settings['animation_delay']) $attrs['data-animation-delay'] = floatval( $this->settings['animation_delay'] );

        return $attrs;

    }
}

new Wyde_Button();
