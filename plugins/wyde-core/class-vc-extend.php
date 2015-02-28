<?php

class Wyde_VCExtend {

    function __construct() {
        
        add_action( 'init', array( $this, 'integrate_with_vc' ) );
        add_action( 'vc_before_init', array($this, 'set_as_theme') );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'load_admin_scripts' ) );

    }
 
    public function integrate_with_vc() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array($this, 'show_vc_notice'));
            return;
        }


        add_filter( 'vc_iconpicker-type-fontawesome', array($this, 'get_font_awesome_icons') );

 
        add_shortcode_param('wyde_animation', array( $this, 'animation_field'), plugin_dir_url( __FILE__ ) .'/js/wyde-animation.min.js');
        //add_shortcode_param('wyde_icons', array( $this, 'icons_field'), plugin_dir_url( __FILE__ ) .'/js/wyde-icons.min.js');
        add_shortcode_param('wyde_gmaps', array( $this, 'gmaps_field'), plugin_dir_url( __FILE__ ) .'/js/wyde-gmaps.min.js');

        $this->add_elements();
        $this->update_elements();

    }

    public function get_font_awesome_icons($icons){

        $new_fontawesome_icons_4_3 = array(
		    "Web Application Icons" => array(
                array( "fa fa-bed" => "Bed" ),
                array( "fa fa-cart-arrow-down" => "Cart Arrow Down" ),
                array( "fa fa-cart-plus" => "Cart Plus" ),
                array( "fa fa-diamond" => "Diamond" ),
                array( "fa fa-heartbeat" => "Heartbeat" ),
                array( "fa fa-motorcycle" => "Motorcycle" ),
                array( "fa fa-server" => "Server" ),
                array( "fa fa-ship" => "Ship" ),
                array( "fa fa-street-view" => "Street View" ),
                array( "fa fa-user-plus" => "User Plus" ),
                array( "fa fa-user-secret" => "User Secret" ),
                array( "fa fa-user-times" => "User Times" ),
            ),
            "Transportation Icons" => array(
                array( "fa fa-subway" => "Subway" ),
                array( "fa fa-train" => "Train" ),
            ),
            "Brand Icons" => array(
                array( "fa fa-buysellads" => "Buysellads" ),
                array( "fa fa-connectdevelop" => "Connectdevelop" ),
                array( "fa fa-dashcube" => "Dashcube" ),
                array( "fa fa-facebook-official" => "Facebook Official" ),
                array( "fa fa-forumbee" => "Forumbee" ),
                array( "fa fa-leanpub" => "Leanpub" ),
                array( "fa fa-medium" => "Medium" ),
                array( "fa fa-pinterest-p" => "Pinterest P" ),
                array( "fa fa-sellsy" => "Sellsy" ),
                array( "fa fa-shirtsinbulk" => "Shirtsinbulk" ),
                array( "fa fa-simplybuilt" => "Simplybuilt" ),
                array( "fa fa-skyatlas" => "Skyatlas" ),

            ),
            "Gender Icons" => array(
                array( "fa fa-mars" => "Mars" ),
                array( "fa fa-mars-double" => "Mars Double" ),
                array( "fa fa-mars-stroke" => "Mars Stroke" ),
                array( "fa fa-mars-stroke-h" => "Mars Stroke Horizontal" ),
                array( "fa fa-mars-stroke-v" => "Mars Stroke Vertical" ),
                array( "fa fa-mercury" => "Mercury" ),
                array( "fa fa-neuter" => "Neuter" ),
                array( "fa fa-transgender" => "Transgender" ),
                array( "fa fa-transgender-alt" => "Transgender Alt" ),
                array( "fa fa-venus" => "Venus" ),
                array( "fa fa-venus-double" => "Venus Double" ),
                array( "fa fa-venus-mars" => "Venus Mars" ),
                array( "fa fa-viacoin" => "Viacoin" ),
            )

        );

        return array_merge_recursive( $icons, $new_fontawesome_icons_4_3 );
    }

    public function add_elements(){
        /* Add new elements (Wyde Elements) */

        /* Blog Posts
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Blog Posts', 'Wyde'),
            'description' => __('Displays Blog Posts list.', 'Wyde'),
            'base' => 'blog_posts',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon blog-posts-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'value' => '',
                      'admin_label' => true,
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Layout', 'Wyde'),
                      'param_name' => 'view',
                      'admin_label' => true,
                      'value' => array(
                          'Large Image' => 'large', 
                          'Medium Image' => 'medium', 
                          'Masonry' => 'masonry'
                       ),
                      'description' => __('Select blog posts view.', 'Wyde')
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Columns', 'Wyde'),
                      'param_name' => 'columns',
                      'admin_label' => true,
                      'value' => array(
                          '1', 
                          '2', 
                          '3', 
                          '4'),
                      'std' => '3',
                      'description' => __('Select the number of columns.', 'Wyde'),
                      'dependency' => array(
				        'element' => 'view',
				        'value' => array( 'masonry')
			            )
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Pagination Type', 'Wyde'),
                      'param_name' => 'pagination',
                      'value' => array(
                          'Hide' => '0',
                          'Numeric Pagination' => '1', 
                          'Infinite Scroll' => '2',
                          'Next and Previous' => '3'
                          ),
                      'description' => __('Select the pagination type for blog page.', 'Wyde')
                  ),
                  array(
                        'type'      => 'textfield',
                        'class' => '',
                        'heading'     => __('Number of Posts per Page', 'Wyde'),
                        'param_name'    => 'count',
                        'description'  => __('Enter the number of posts per page.', 'Wyde'),
                        'value'   => '10'
                        
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );


        /* Button
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Button', 'Wyde'),
            'description' => __('Add button.', 'Wyde'),
            'base' => 'button',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon button-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Text on the button.', 'Wyde')
                    ),
                    array(
			            'type' => 'vc_link',
			            'heading' => __( 'URL (Link)', 'Wyde' ),
			            'param_name' => 'link',
			            'description' => __( 'Set a button link.', 'Wyde' )
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'Wyde'),
                        'param_name' => 'style',
                        'value' => array(
                            'Square' => '', 
                            'Round' => 'round', 
                            'Square Outline' => 'outline', 
                            'Round Outline' => 'round-outline', 
                        ),
                        'description' => __('Select button style.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Size', 'Wyde'),
                        'param_name' => 'size',
                        'value' => array(
                            'Small' => '', 
                            'Large' =>'large', 
                        ),
                        'description' => __('Select button size.', 'Wyde')
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Button Color', 'Wyde' ),
			            'param_name' => 'color',
			            'description' => __( 'Select button color.', 'Wyde' ),
                    ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
				            'element' => 'animation',
				            'not_empty' => true
			            )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    )
            )
        ));



        /* Clients Carousel
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Clients Carousel', 'Wyde'),
            'description' => __('Create beautiful responsive carousel slider.', 'Wyde'),
            'base' => 'clients_carousel',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon image-carousel-icon', 
            'category' => 'Wyde',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Title', 'Wyde'),
                    'param_name' => 'title',
                    'value' => '',
                    'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                ),
                array(
                    'type' => 'attach_images',
                    'class' => '',
                    'heading' => __('Images', 'Wyde'),
                    'param_name' => 'images',
                    'value' => '',
                    'description' => __('Upload or select images from media library.', 'Wyde')
                ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Size', 'Wyde' ),
			        'param_name' => 'image_size',
			        'value' => array(
				        'Thumbnail (150x150)' => 'thumbnail',
				        'Medium (300x300)' => 'medium',
				        'Large (640x640)'=> 'large',
                        'Full (Original)'=> 'full',
				        'Blog Medium (600x340)'=> 'blog-medium',
				        'Blog Large (800x450)'=> 'blog-large',
				        'Blog Full (1066x600)'=> 'blog-full',
			        ),
			        'description' => __( 'Select image size.', 'Wyde' )
		        ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Visible Items', 'Wyde'),
                    'param_name' => 'visible_items',
                    'value' => array('auto', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
                    'std' => '3',
                    'description' => __('The maximum amount of items displayed at a time.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_navigation',
                    'value' => array(
                            'Show Navigation' => 'true'
                    ),
                    'description' => __('Display "next" and "prev" buttons.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'show_pagination',
                    'value' => array(
                            'Show Pagination' => 'true'
                    ),
                    'description' => __('Show pagination.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'heading' => __('Auto Play', 'Wyde'),
                    'param_name' => 'auto_play',
                    'value' => array(
                            'Auto Play' => 'true'
                    ),
                    'description' => __('Auto play slide.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'loop',
                    'value' => array(
                            'Loop' => 'true'
                    ),
                    'description' => __('Inifnity loop. Duplicate last and first items to get loop illusion.', 'Wyde')
                ),
                array(
                    'type' => 'wyde_animation',
                    'class' => '',
                    'heading' => __('Animation', 'Wyde'),
                    'param_name' => 'animation',
                    'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
	                    'element' => 'animation',
	                    'not_empty' => true
                    )
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Extra CSS Class', 'Wyde'),
                    'param_name' => 'el_class',
                    'value' => '',
                    'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                )
            )
        ) );

        /* Counter Box
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Counter Box', 'Wyde'),
            'description' => __('Animated numbers.', 'Wyde'),
            'base' => 'counter_box',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon counter-box-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'admin_label' => true,
                      'value' => '',
                      'description' => __('Set counter title.', 'Wyde')
                  ),
                  /*array(
                      'type' => 'wyde_icons',
                      'class' => '',
                      'heading' => __('Icon', 'Wyde'),
                      'param_name' => 'icon',
                      'value' => '',
                      'description' => __('Set counter icon.', 'Wyde')
                  ),*/
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Start From', 'Wyde'),
                      'param_name' => 'start',
                      'value' => '0',
                      'description' => __('Set start value.', 'Wyde')
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Value', 'Wyde'),
                      'param_name' => 'value',
                      'value' => '100',
                      'description' => __('Set counter value.', 'Wyde')
                  ),
                   array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );

        /* Donut Chart
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Donut Chart', 'Wyde'),
            'description' => __('Animated donut chart.', 'Wyde'),
            'base' => 'donut_chart',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon donut-chart-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Set chart title.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Chart Value', 'Wyde'),
                        'param_name' => 'value',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Input chart value here. can be 1 to 100.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Label Mode', 'Wyde'),
                        'param_name' => 'label_mode',
                        'value' => array(
                            'Text' => '', 
                            'Icon' => 'icon', 
                            ),
                        'description' => __('Select a label mode.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Label', 'Wyde'),
                        'param_name' => 'label',
                        'value' => '',
                        'description' => __('Set chart label.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'is_empty' => true,
		                )
                    ),
                    /*array(
                        'type' => 'wyde_icons',
                        'class' => '',
                        'heading' => __('Icon', 'Wyde'),
                        'param_name' => 'icon',
                        'description' => __('Select an icon.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
                    ),*/
                     array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),                  

                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'Wyde'),
                        'param_name' => 'style',
                        'value' => array(
                            'Default' => '', 
                            'Outline' => 'outline', 
                            'Inline' => 'inline', 
                            ),
                        'description' => __('Select style.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Bar Color', 'Wyde'),
                        'param_name' => 'bar_color',
                        'value' => '',
                        'description' => __('Select bar color.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Border Color', 'Wyde'),
                        'param_name' => 'bar_border_color',
                        'value' => '',
                        'description' => __('Select border color.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Fill Color', 'Wyde'),
                        'param_name' => 'fill_color',
                        'value' => '',
                        'description' => __('Select background color of the whole circle.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Start', 'Wyde'),
                        'param_name' => 'start',
                        'value' => array(
                            'Default' => '', 
                            '90 degree' => '90', 
                            '180 degree' => '180', 
                            '270 degree' => '270', 
                            ),
                        'description' => __('Select the degree to start animate.', 'Wyde')
                    ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'animation',
		                    'not_empty' => true
	                    )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    ),
                    array(
	                    'type' => 'css_editor',
	                    'heading' => __( 'Css', 'Wyde' ),
	                    'param_name' => 'css',
	                    'group' => __( 'Design options', 'Wyde' )
                    ) 
            )
        ));

        

        /* Google Maps
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Google Maps', 'Wyde'),
            'description' => __('Google Maps block.', 'Wyde'),
            'base' => 'gmaps',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'icon-wpb-map-pin', 
            'category' => 'Wyde',
	        'params' => array(
                array(
			        'type' => 'wyde_gmaps',
			        'heading' => 'Address',
			        'param_name' => 'gmaps',
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Map Height', 'Wyde' ),
			        'param_name' => 'height',
			        'admin_label' => true,
                    'value' => '300',
			        'description' => __( 'Enter map height in pixels. Example: 300.', 'Wyde' )
		        ),
                array(
                      'type' => 'colorpicker',
                      'class' => '',
                      'heading' => __('Map Color', 'Wyde'),
                      'param_name' => 'color',
                      'value' => '',
                      'description' => __('Select map background color. If empty "Theme Color Scheme" will be used.', 'Wyde')
                ),
		        array(
			        'type' => 'attach_image',
			        'heading' => __( 'Icon', 'Wyde' ),
			        'param_name' => 'icon',
			        'description' => __( 'To custom your own marker icon, upload or select images from media library.', 'Wyde' )
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' =>  __('Extra CSS Class', 'Wyde'),
			        'param_name' => 'el_class',
			        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
		        )
	        )
         ));


         /* Half Donut Chart
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Half Donut Chart', 'Wyde'),
            'description' => __('Animated half donut chart.', 'Wyde'),
            'base' => 'half_donut_chart',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon half-donut-chart-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Set chart title.', 'Wyde')
                    ),
                    array(
                        'type' => 'textarea_html',
                        'class' => '',
                        'heading' => __('Description', 'Wyde'),
                        'param_name' => 'content',
                        'value' => '',
                        'description' => __('Enter your content.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Chart Value', 'Wyde'),
                        'param_name' => 'value',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Input chart value here. Choose range between 0 and 100.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Label Mode', 'Wyde'),
                        'param_name' => 'label_mode',
                        'value' => array(
                            'Text' => '', 
                            'Icon' => 'icon', 
                            ),
                        'description' => __('Select a label mode.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Label', 'Wyde'),
                        'param_name' => 'label',
                        'value' => '',
                        'description' => __('Set chart label.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'is_empty' => true,
		                )
                    ),
                    /*array(
                        'type' => 'wyde_icons',
                        'class' => '',
                        'heading' => __('Icon', 'Wyde'),
                        'param_name' => 'icon',
                        'description' => __('Select an icon.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
                    ),*/
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'Wyde'),
                        'param_name' => 'style',
                        'value' => array(
                            'Default' => '', 
                            'Outline' => 'outline', 
                            'Inline' => 'inline', 
                            ),
                        'description' => __('Select style.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Bar Color', 'Wyde'),
                        'param_name' => 'bar_color',
                        'value' => '',
                        'description' => __('Select bar color.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Border Color', 'Wyde'),
                        'param_name' => 'bar_border_color',
                        'value' => '',
                        'description' => __('Select border color.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Fill Color', 'Wyde'),
                        'param_name' => 'fill_color',
                        'value' => '',
                        'description' => __('Select background color of the whole circle.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Start', 'Wyde'),
                        'param_name' => 'start',
                        'value' => array(
                            'Default' => '', 
                            '45 degree' => '45', 
                            '90 degree' => '90',
                            ),
                        'description' => __('Select the degree to start animate.', 'Wyde')
                    ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'animation',
		                    'not_empty' => true
	                    )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    ),
                    array(
	                    'type' => 'css_editor',
	                    'heading' => __( 'Css', 'Wyde' ),
	                    'param_name' => 'css',
	                    'group' => __( 'Design options', 'Wyde' )
                    ) 
            )
        ));

        /* Heading
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Heading', 'Wyde'),
            'description' => __('Heading text.', 'Wyde'),
            'base' => 'heading',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon heading-icon', 
            'category' => 'Wyde',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Title', 'Wyde'),
                    'param_name' => 'title',
                    'admin_label' => true,
                    'value' => '',
                    'description' => __('Enter title text.', 'Wyde')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Sub Title', 'Wyde'),
                    'param_name' => 'sub_title',
                    'admin_label' => true,
                    'value' => '',
                    'description' => __('Enter sub title text.', 'Wyde')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Separator Style', 'Wyde'),
                    'param_name' => 'style',
                    'value' => array(
                        '1', 
                        '2', 
                        '3', 
                        '4', 
                        '5',
                        '6',
                        '7',
                        '8',
                        '9',
                        '10',
                    ),
                    'description' => __('Select a heading separator style.', 'Wyde')
                ),
                array(
                    'type' => 'wyde_animation',
                    'class' => '',
                    'heading' => __('Animation', 'Wyde'),
                    'param_name' => 'animation',
                    'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
		                'element' => 'animation',
		                'not_empty' => true
	                )
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Extra CSS Class', 'Wyde'),
                    'param_name' => 'el_class',
                    'value' => '',
                    'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                )
            )
        ) );

        

        
        /* Icon Block
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Icon Block', 'Wyde'),
            'description' => __('Add icon block.', 'Wyde'),
            'base' => 'icon_block',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon icon-block-icon', 
            'category' => 'Wyde',
            'params' => array(
                  /*array(
                      'type' => 'wyde_icons',
                      'class' => '',
                      'heading' => __('Icon', 'Wyde'),
                      'param_name' => 'icon',
                      'description' => __('Select an icon.', 'Wyde')
                  ),*/
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
                  array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Icon Size', 'Wyde'),
                    'param_name' => 'size',
                    'value' => array(
                        'Small' => 'small', 
                        'Medium' => 'medium', 
                        'Large' => 'large',
                        'Extra Large' => 'xlarge'
                        ),
                    'std' => 'small',
                    'description' => __('Select icon size.', 'Wyde')
                  ),
                  array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Icon Style', 'Wyde'),
                    'param_name' => 'style',
                    'value' => array(
                        'Circle' => 'circle', 
                        'Square' => 'square',
                        ),
                    'std' => 'circle',
                    'description' => __('Select icon style.', 'Wyde')
                  ),
                  array(
                    'type' => 'colorpicker',
                    'class' => '',
                    'heading' => __('Background Color', 'Wyde'),
                    'param_name' => 'color',
                    'description' => __('Select icon background color. If empty "Theme Color Scheme" will be used.', 'Wyde')
                  ),
                  array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Hover Effect', 'Wyde'),
                    'param_name' => 'hover',
                    'value' => array(
                        'Zoom In' => 1,
                        'Zoom Out'  => 2,
                        'Pulse'  => 3,
                        'Left to Right'  => 4,
                        'Right to Left' =>5,
                        'Bottom to Top' =>6,
                        'Top to Bottom' =>7
                    ),
                    'description' => __('Select icon hover effect.', 'Wyde')
                  ),
                  array(
                    'type' => 'vc_link',
                    'class' => '',
                    'heading' => __('URL', 'Wyde'),
                    'param_name' => 'link',
                    'description' => __('Icon link.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  ),
		          array(
			        'type' => 'css_editor',
			        'heading' => __( 'Css', 'js_composer' ),
			        'param_name' => 'css',
			        'group' => __( 'Design options', 'js_composer' )
		          )
            )
        ) );
        


        /* Info Box
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Info Box', 'Wyde'),
            'description' => __('Content box with icon.', 'Wyde'),
            'base' => 'info_box',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon info-box-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Set info box title.', 'Wyde')
                    ),
                    array(
                        'type' => 'textarea_html',
                        'class' => '',
                        'heading' => __('Content', 'Wyde'),
                        'param_name' => 'content',
                        'value' => '',
                        'description' => __('Enter your content.', 'Wyde')
                    ),
                    /*array(
                        'type' => 'wyde_icons',
                        'class' => '',
                        'heading' => __('Icon', 'Wyde'),
                        'param_name' => 'icon',
                        'description' => __('Select an icon.', 'Wyde')
                    ),*/

                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
             
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Icon Size', 'Wyde'),
                        'param_name' => 'icon_size',
                        'value' => array(
                            'Small' => 'small', 
                            'Medium' => 'medium', 
                            'Large' => 'large'
                            ),
                        'std' => 'small',
                        'description' => __('Select icon size.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Border Style', 'Wyde'),
                        'param_name' => 'style',
                        'value' => array(
                                __('Square', 'Wyde') => '', 
                                __('Circle', 'Wyde') => 'circle',
                                __('None', 'Wyde') => 'none',
                                ),
                        'description' => __('Select icon border style.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Icon Position', 'Wyde'),
                        'param_name' => 'icon_position',
                        'value' => array(
                                __('Align Left', 'Wyde') => 'left', 
                                __('Align Top', 'Wyde') => 'top',
                                __('Align Right', 'Wyde') => 'right',
                                ),
                        'std' => 'top',
                        'description' => __('Select icon alignment.', 'Wyde')
                    ),
                    array(
                            'type' => 'colorpicker',
                            'class' => '',
                            'heading' => __('Color', 'Wyde'),
                            'param_name' => 'color',
                            'value' => '',
                            'description' => __('Select an icon color.', 'Wyde')
                      ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'animation',
		                    'not_empty' => true
	                    )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    ),
                    array(
	                    'type' => 'css_editor',
	                    'heading' => __( 'Css', 'Wyde' ),
	                    'param_name' => 'css',
	                    'group' => __( 'Design options', 'Wyde' )
                    ) 
            )
        ) );



        /* Link Button
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Link Button', 'Wyde'),
            'description' => __('Add link button with icon.', 'Wyde'),
            'base' => 'link_button',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon link-button-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'title',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Text on the button.', 'Wyde')
                    ),
                    array(
			            'type' => 'vc_link',
			            'heading' => __( 'URL (Link)', 'Wyde' ),
			            'param_name' => 'link',
			            'description' => __( 'Set a button link.', 'Wyde' )
		            ),
                    /*array(
                      'type' => 'wyde_icons',
                      'class' => '',
                      'heading' => __('Icon', 'Wyde'),
                      'param_name' => 'icon',
                      'value' => '',
                      'description' => __('Select an icon.', 'Wyde')
                    ),*/
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Style', 'Wyde'),
                        'param_name' => 'style',
                        'value' => array(
                            'Round' =>'', 
                            'Square' => 'square', 
                        ),
                        'description' => __('Select button style.', 'Wyde')
                    ),
                    array(
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => __('Size', 'Wyde'),
                        'param_name' => 'size',
                        'value' => array(
                            'Small' => '', 
                            'Large' =>'large', 
                        ),
                        'description' => __('Select button size.', 'Wyde')
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Button Color', 'Wyde' ),
			            'param_name' => 'color',
			            'description' => __( 'Select button color.', 'Wyde' ),
                    ),
                    array(
			            'type' => 'colorpicker',
			            'heading' => __( 'Hover Color', 'Wyde' ),
			            'param_name' => 'hover_color',
			            'description' => __( 'Select hover text color.', 'Wyde' ),
                    ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
				            'element' => 'animation',
				            'not_empty' => true
			            )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    )
            )
        ));


        /* Portfolio
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Portfolio', 'Wyde'),
            'description' => __('Portfolio items in grid view.', 'Wyde'),
            'base' => 'portfolio_grid',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon portfolio-grid-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'admin_label' => true,
                      'value' => '',
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('View', 'Wyde'),
                      'param_name' => 'view',
                      'admin_label' => true,
                      'value' => array(
                          'Grid (Without Space)' => '', 
                          'Gallery (With Space)' => 'gallery',
                          'Masonry' => 'masonry',
                      ),
                      'description' => __('Select grid view style.', 'Wyde')
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Columns', 'Wyde'),
                      'param_name' => 'columns',
                      'value' => array(
                          '1', 
                          '2', 
                          '3', 
                          '4'
                      ),
                      'std' => '4',
                      'description' => __('Select the number of grid columns.', 'Wyde'),
                      'dependency' => array(
		                    'element' => 'view',
		                    'value' => array('', 'gallery')
		                )
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Hover Effect', 'Wyde'),
                      'param_name' => 'hover_effect',
                      'value' => array(
                            'Apollo' => 'apollo', 
                            'Bubba' => 'bubba',
                            'Duke' => 'duke',
                            'Goliath' => 'goliath',
                            'Jazz' => 'jazz',
                            'Kira' => 'kira',
                            'Selena' => 'selena',
                        ),
                      'description' => __('Select the hover effect for portfolio items.', 'Wyde')
                  ),
                  array(
			            'type' => 'loop',
			            'heading' => __( 'Custom Posts', 'Wyde' ),
			            'param_name' => 'posts_query',
			            'settings' => array(
                            'post_type'  => array('hidden' => true),
                            'categories'  => array('hidden' => true),
                            'tags'  => array('hidden' => true),
				            'size' => array( 'hidden' => true),
				            'order_by' => array( 'value' => 'date' ),
				            'order' => array( 'value' => 'DESC' ),
			            ),
			            'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
		          ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Post Count', 'Wyde'),
                      'param_name' => 'count',
                      'value' => '10',
                      'description' => __('Number of posts to show.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'hide_filter',
                      'value' => array(
                              'Hide Filter' => 'true'
                      ),
                      'description' => __('Display animated category filter to your grid.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_more',
                      'value' => array(
                              'Load More Button' => 'true'
                      ),
                      'description' => __('Display load more button.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );


        
        /* Posts Grid
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Posts Grid', 'Wyde'),
            'description' => __('Posts in grid view.', 'Wyde'),
            'base' => 'posts_grid',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'icon-wpb-application-icon-large', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
			            'type' => 'loop',
			            'heading' => __( 'Custom Posts', 'Wyde' ),
			            'param_name' => 'posts_query',
			            'settings' => array(
                            'post_type'  => array('hidden' => true),
				            'size' => array( 'hidden' => true),
				            'order_by' => array( 'value' => 'date' ),
				            'order' => array( 'value' => 'DESC' ),
			            ),
			            'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
		          ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Columns', 'Wyde'),
                      'param_name' => 'columns',
                      'admin_label' => true,
                      'value' => array(
                          '1', 
                          '2', 
                          '3', 
                          '4'
                      ),
                      'std' => '4',
                      'description' => __('Select the number of grid columns.', 'Wyde')
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Post Count', 'Wyde'),
                      'param_name' => 'count',
                      'value' => '10',
                      'description' => __('Number of posts to show.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'hide_filter',
                      'value' => array(
                              'Hide Filter' => 'true'
                      ),
                      'description' => __('Display animated category filter to your grid.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_more',
                      'value' => array(
                              'Load More Button' => 'true'
                      ),
                      'description' => __('Display load more button.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );


        /* Posts Slider
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Posts Slider', 'Wyde'),
            'description' => __('Posts in slider view.', 'Wyde'),
            'base' => 'posts_slider',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon posts-slider-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'admin_label' => true,
                      'value' => '',
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
			            'type' => 'loop',
			            'heading' => __( 'Custom Posts', 'Wyde' ),
			            'param_name' => 'posts_query',
			            'settings' => array(
                            'post_type'  => array('hidden' => true),
				            'size' => array( 'hidden' => true),
				            'order_by' => array( 'value' => 'date' ),
				            'order' => array( 'value' => 'DESC' ),
			            ),
			            'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
		          ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Post Count', 'Wyde'),
                      'param_name' => 'count',
                      'admin_label' => true,
                      'value' => '10',
                      'description' => __('Number of posts to show.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_navigation',
                      'value' => array(
                              'Show Navigation' => 'true'
                      ),
                      'description' => __('Display "next" and "prev" buttons.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_pagination',
                      'value' => array(
                              'Show Pagination' => 'true'
                      ),
                      'description' => __('Show pagination.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'heading' => __('Auto Play', 'Wyde'),
                      'param_name' => 'auto_play',
                      'value' => array(
                              'Auto Play' => 'true'
                      ),
                      'description' => __('Auto play slide.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );

        

        /* Pricing Box
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Pricing Box', 'Wyde'),
            'description' => __('Create pricing box.', 'Wyde'),
            'base' => 'pricing_box',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon pricing-box-icon', 
            'category' => 'Wyde',
            'params' => array(
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Title', 'Wyde'),
                        'param_name' => 'heading',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Enter the heading or package name.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Sub Heading', 'Wyde'),
                        'param_name' => 'sub_heading',
                        'value' => '',
                        'description' => __('Enter a short description.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Price', 'Wyde'),
                        'param_name' => 'price',
                        'admin_label' => true,
                        'value' => '',
                        'description' => __('Enter a price. Example: $100', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Price Unit', 'Wyde'),
                        'param_name' => 'price_unit',
                        'value' => '',
                        'description' => __('Enter a price unit. Example: per month', 'Wyde')
                    ),
                    array(
                        'type' => 'textarea_html',
                        'class' => '',
                        'heading' => __('Features', 'Wyde'),
                        'param_name' => 'content',
                        'value' => '',
                        'description' => __('Enter the features list or table description.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Button Text', 'Wyde'),
                        'param_name' => 'button_text',
                        'value' => '',
                        'description' => __('Enter a button text.', 'Wyde')
                    ),
                    array(
                        'type' => 'vc_link',
                        'class' => '',
                        'heading' => __('Button Link', 'Wyde'),
                        'param_name' => 'link',
                        'value' => '',
                        'description' => __('Select or enter the link for button.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Background Color', 'Wyde'),
                        'param_name' => 'bg_color',
                        'value' => '',
                        'description' => __('Select a background color.', 'Wyde')
                    ),
                    array(
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => __('Text Color', 'Wyde'),
                        'param_name' => 'text_color',
                        'value' => '',
                        'description' => __('Select a text color.', 'Wyde')
                    ),
                    array(
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => __('Featured Box', 'Wyde'),
                        'param_name' => 'featured',
                        'value' => array(
                                'Make this box as featured' => 'true'
                        )
                    ),
                    array(
                        'type' => 'wyde_animation',
                        'class' => '',
                        'heading' => __('Animation', 'Wyde'),
                        'param_name' => 'animation',
                        'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Animation Delay', 'Wyde'),
                        'param_name' => 'animation_delay',
                        'value' => '',
                        'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                        'dependency' => array(
				            'element' => 'animation',
				            'not_empty' => true
			            )
                    ),
                    array(
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => __('Extra CSS Class', 'Wyde'),
                        'param_name' => 'el_class',
                        'value' => '',
                        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                    )
            )
        ) );


        /* Team Members Slider
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Team Members Slider', 'Wyde'),
            'description' => __('Team Members in slider view.', 'Wyde'),
            'base' => 'team_slider',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon team-slider-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'admin_label' => true,
                      'value' => '',
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Post Count', 'Wyde'),
                      'param_name' => 'count',
                      'value' => '10',
                      'description' => __('Number of posts to show.', 'Wyde')
                  ),
                  array(
                      'type' => 'dropdown',
                      'class' => '',
                      'heading' => __('Visible Items', 'Wyde'),
                      'param_name' => 'visible_items',
                      'value' => array('1', '2', '3', '4', '5'),
                      'std' => '3',
                      'description' => __('The maximum amount of items displayed at a time.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_navigation',
                      'value' => array(
                              'Show Navigation' => 'true'
                      ),
                      'description' => __('Display "next" and "prev" buttons.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_pagination',
                      'value' => array(
                              'Show Pagination' => 'true'
                      ),
                      'description' => __('Show pagination.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'heading' => __('Auto Play', 'Wyde'),
                      'param_name' => 'auto_play',
                      'value' => array(
                              'Auto Play' => 'true'
                      ),
                      'description' => __('Auto play slide.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );



        /* Testimonials Slider
        ---------------------------------------------------------- */
        vc_map( array(
            'name' => __('Testimonials Slider', 'Wyde'),
            'description' => __('Testimonials in slider view.', 'Wyde'),
            'base' => 'testimonials_slider',
            'class' => '',
            'controls' => 'full',
            'icon' =>  'wyde-icon testimonials-slider-icon', 
            'category' => 'Wyde',
            'params' => array(
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Title', 'Wyde'),
                      'param_name' => 'title',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textarea_html',
                      'class' => '',
                      'heading' => __('Description', 'Wyde'),
                      'param_name' => 'content',
                      'value' => '',
                      'description' => __('Enter text which will be used as widget description. Leave blank if no description is needed.', 'Wyde')
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Post Count', 'Wyde'),
                      'param_name' => 'count',
                      'value' => '10',
                      'description' => __('Number of posts to show.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_navigation',
                      'value' => array(
                              'Show Navigation' => 'true'
                      ),
                      'description' => __('Display "next" and "prev" buttons.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'param_name' => 'show_pagination',
                      'value' => array(
                              'Show Pagination' => 'true'
                      ),
                      'description' => __('Show pagination.', 'Wyde')
                  ),
                  array(
                      'type' => 'checkbox',
                      'class' => '',
                      'heading' => __('Auto Play', 'Wyde'),
                      'param_name' => 'auto_play',
                      'value' => array(
                              'Auto Play' => 'true'
                      ),
                      'description' => __('Auto play slide.', 'Wyde')
                  ),
                  array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                  ),
                  array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                  ),
                  array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                  )
            )
        ) );
    }
    

    public function update_elements(){
        /* Remove VC Elements 
        ---------------------------------------------------------- */
        vc_remove_element('vc_button');
        //vc_remove_element('vc_button2');
        vc_remove_element('vc_carousel');
        vc_remove_element('vc_posts_grid');
        vc_remove_element('vc_posts_slider');
        vc_remove_element('vc_pie');
        vc_remove_element('vc_gmaps');
        // remove unused elements in v4.4
        //vc_remove_element('vc_icon');
        vc_remove_element('vc_media_grid');
        vc_remove_element('vc_masonry_grid');
        vc_remove_element('vc_masonry_media_grid');



        /* Update VC Elements 
        /* Row
        ---------------------------------------------------------- */
        vc_remove_param("vc_row", "el_class");

        $params = array(
        
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'alt_color',
                    'value' => array(
                            'Invert Text Color' => 'true'
                    ),
                    'description' => __('Invert text color for this section, apply white color in "Light" skin and black color in "Dark" skin.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'alt_bg_color',
                    'value' => array(
                            'Darken Background Color' => 'true'
                    ),
                    'description' => __('Apply darken background color.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'parallax',
                    'value' => array(
                            'Background Parallax' => 'true'
                    ),
                    'description' => __('Enable parallax background scrolling.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'full_width',
                    'value' => array(
                            'Full Width' => 'true'
                    ),
                    'description' => __('Full width section.', 'Wyde')
                ),
                array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'full_screen',
                    'value' => array(
                            'Full Screen' => 'true'
                    ),
                    'description' => __('Full screen section (Full height).', 'Wyde')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Background Overlay', 'Wyde'),
                    'param_name' => 'background_overlay',
                    'value' => array(
                            'None' => '',
                            'Color Overlay' => 'color',
                            'Pattern Overlay' => 'pattern'
                    ),
                    'description' => __('Apply an overlay to the background.', 'Wyde')
                ),
                array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Overlay Color', 'Wyde' ),
			        'param_name' => 'overlay_color',
			        'description' => __( 'Select background color overlay.', 'Wyde' ),
                    'dependency' => array(
				        'element' => 'background_overlay',
				        'not_empty' => true
			        )
		        ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Content Mask', 'Wyde'),
                    'param_name' => 'mask',
                    'value' => array(
                            'None' => '',
                            'Top' => 'top',
                            'Bottom' => 'bottom'
                    ),
                    'description' => __('Select content mask position.', 'Wyde')
                ),
                array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Mask Color', 'Wyde' ),
			        'param_name' => 'mask_color',
			        'description' => __( 'Select content mask color.', 'Wyde' ),
                    'dependency' => array(
				        'element' => 'mask',
				        'not_empty' => true
			        )
		        ), 
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Mask Style', 'Wyde'),
                    'param_name' => 'mask_style',
                    'value' => array(
                            '0/100' =>'0', 
                            '10/90' => '10',
                            '20/80' => '20',
                            '30/70' => '30',
                            '40/60' => '40',
                            '50/50' => '50',
                            '60/40' => '60',
                            '70/30' => '70',
                            '80/20' => '80',
                            '90/10' => '90',
                            '100/0' => '100',
                        ),
                    'description' => __('Select content mask style.', 'Wyde'),
                    'dependency' => array(
				        'element' => 'mask',
				        'not_empty' => true
			        ),
                    'std' => '50'
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Content Vertical Alignment', 'Wyde'),
                    'param_name' => 'vertical_align',
                    'value' => array(
                        'Top' => '', 
                        'Middle' =>'middle', 
                        'Bottom' => 'bottom', 
                    ),
                    'description' => __('Select content vertical alignment.', 'Wyde')
                ),
                array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Padding Size', 'Wyde'),
                    'param_name' => 'padding_size',
                    'value' => array(
                        'Default' => '', 
                        'No Padding' =>'no-padding', 
                        'Small' => 's-padding', 
                        'Medium' => 'm-padding', 
                        'Large' => 'l-padding', 
                        'Extra Large' => 'xl-padding'
                    ),
                    'description' => __('Select padding size.', 'Wyde')
                ),
                array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                )
        );

        foreach($params as $param){
            vc_add_param('vc_row', $param);
        }


        /* Column
        ---------------------------------------------------------- */
        vc_remove_param("vc_column", "el_class");
        

        vc_add_param('vc_column', array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'alt_color',
                    'value' => array(
                            'Invert Text Color' => 'true'
                    ),
                    'description' => __('Invert text color for this section, apply white color in "Light" skin and black color in "Dark" skin.', 'Wyde')
        ));
         
        vc_add_param('vc_column', array(
                    'type' => 'checkbox',
                    'class' => '',
                    'param_name' => 'alt_bg_color',
                    'value' => array(
                            'Darken Background Color' => 'true'
                    ),
                    'description' => __('Apply darken background color.', 'Wyde')
        )); 

        vc_add_param('vc_column', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Text Alignment', 'Wyde'),
                    'param_name' => 'text_align',
                    'value' => array(
                        'Left' => '', 
                        'Center' =>'center', 
                        'Right' => 'right', 
                    ),
                    'description' => __('Select text alignment.', 'Wyde')
        ));


        vc_add_param('vc_column', array(
                    'type' => 'dropdown',
                    'class' => '',
                    'heading' => __('Padding Size', 'Wyde'),
                    'param_name' => 'padding_size',
                    'value' => array(
                        'Default' => '', 
                        'No Padding' =>'no-padding', 
                        'Small' => 's-padding', 
                        'Medium' => 'm-padding', 
                        'Large' => 'l-padding', 
                        'Extra Large' => 'xl-padding'
                    ),
                    'description' => __('Select padding size.', 'Wyde')
        ));

        vc_add_param('vc_column', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));
         

        /* Text Block
        ---------------------------------------------------------- */
         vc_remove_param('vc_column_text', 'css_animation');
         vc_remove_param('vc_column_text', 'el_class');
         
         vc_add_param('vc_column_text', array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
         ));

         vc_add_param('vc_column_text', array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
        ));

         vc_add_param('vc_column_text', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));


        /* Message box
        ---------------------------------------------------------- */
        vc_remove_param('vc_message', 'css_animation');
        vc_remove_param('vc_message', 'el_class');

        vc_add_param('vc_message', array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
        ));

        vc_add_param('vc_message', array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
        ));

        vc_add_param('vc_message', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));
        

        /* Accordion Section
        ---------------------------------------------------------- */
        /*vc_add_param('vc_accordion_tab', array(
                      'type' => 'wyde_icons',
                      'class' => '',
                      'heading' => __('Icon', 'Wyde'),
                      'param_name' => 'icon',
                      'value' => '',
                      'description' => __('Select an icon.', 'Wyde')
        ));*/

        vc_add_param('vc_accordion_tab', array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde')
                        
		            ));

        vc_add_param('vc_accordion_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ));


         vc_add_param('vc_accordion_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ));

        vc_add_param('vc_accordion_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ));

         vc_add_param('vc_accordion_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ));

        vc_add_param('vc_accordion_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'type' => 'linecons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ));


        vc_add_param('vc_accordion_tab', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));


        
        /* Call to Action
        ---------------------------------------------------------- */
        vc_remove_param('vc_cta_button', 'icon');
        vc_remove_param('vc_cta_button', 'size');
        vc_remove_param('vc_cta_button', 'color');
        vc_remove_param('vc_cta_button', 'css_animation');
        vc_remove_param('vc_cta_button', 'el_class');
         
        vc_add_param('vc_cta_button',  array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Background Color', 'Wyde' ),
			        'param_name' => 'color',
			        'description' => __( 'Select button background color. If empty "Theme Color Scheme" will be used.', 'Wyde' ),
        ));

        vc_add_param('vc_cta_button', array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
        ));

        vc_add_param('vc_cta_button', array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
        ));

        vc_add_param('vc_cta_button', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));

        

        /* Call to Action 2
        ---------------------------------------------------------- */
        vc_map( array(
	        'name' => __( 'Call to Action Block', 'Wyde' ),
	        'base' => 'vc_cta_button2',
	        'icon' => 'icon-wpb-call-to-action',
	        'category' => array( __( 'Content', 'js_composer' ) ),
	        'description' => __( 'Catch visitors attention with call to action block', 'Wyde' ),
	        'params' => array(
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Heading first line', 'js_composer' ),
			        'admin_label' => true,
			        'param_name' => 'h2',
			        'value' => '',
			        'description' => __( 'Text for the first heading line.', 'js_composer' )
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Heading second line', 'js_composer' ),
			        //'holder' => 'h4',
			        //'admin_label' => true,
			        'param_name' => 'h4',
			        'value' => '',
			        'description' => __( 'Optional text for the second heading line.', 'js_composer' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Text align', 'js_composer' ),
			        'param_name' => 'txt_align',
			        'value' => getVcShared( 'text align' ),
			        'description' => __( 'Text align in call to action block.', 'js_composer' )
		        ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Custom Background Color', 'js_composer' ),
			        'param_name' => 'accent_color',
			        'description' => __( 'Select background color for your element.', 'js_composer' )
		        ),
		        array(
			        'type' => 'textarea_html',
			        //holder' => 'div',
			        //'admin_label' => true,
			        'heading' => __( 'Promotional text', 'js_composer' ),
			        'param_name' => 'content',
			        'value' => __( 'I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'js_composer' )
		        ),
		        array(
			        'type' => 'vc_link',
			        'heading' => __( 'URL (Link)', 'js_composer' ),
			        'param_name' => 'link',
			        'description' => __( 'Button link.', 'js_composer' )
		        ),
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Text on the button', 'js_composer' ),
			        'param_name' => 'title',
			        'value' => '',
			        'description' => __( 'Text on the button.', 'js_composer' )
		        ),
                /*array(
                      'type' => 'wyde_icons',
                      'class' => '',
                      'heading' => __('Icon', 'Wyde'),
                      'param_name' => 'icon',
                      'value' => '',
                      'description' => __('Select an icon.', 'Wyde')
                ),*/
                    array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'label_mode',
		                    'value' => array('icon')
		                )
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'type' => 'entypo',
				            'iconsPerPage' => 4000,
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ),
		            array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '',
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000,
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Button Background Color', 'Wyde' ),
			        'param_name' => 'color',
			        'description' => __( 'Select button background color. If empty "Theme Color Scheme" will be used.', 'Wyde' ),
                ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Button position', 'js_composer' ),
			        'param_name' => 'position',
			        'value' => array(
				        __( 'Align right', 'js_composer' ) => 'right',
				        __( 'Align left', 'js_composer' ) => 'left',
				        __( 'Align bottom', 'js_composer' ) => 'bottom'
			        ),
			        'description' => __( 'Select button alignment.', 'js_composer' )
		        ),
		        array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                ),
		        array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Extra CSS Class', 'Wyde'),
                    'param_name' => 'el_class',
                    'value' => '',
                    'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
                )
	        )
        ) );
        

        /* Separator
        ---------------------------------------------------------- */
        vc_remove_param('vc_separator', 'style');
        vc_remove_param('vc_separator', 'el_width');
        vc_remove_param('vc_separator', 'color');
        vc_remove_param('vc_separator', 'accent_color');
        vc_remove_param('vc_separator', 'el_class');
        
        vc_add_param('vc_separator', array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => array(
            	'Border' => '',
		        'Dashed' => 'dashed',
		        'Dotted' => 'dotted',
		        'Double' => 'double',
                'Wyde Theme' => 'theme',
	        ),
			'description' => __( 'Separator Style', 'Wyde' )
		));

            
        vc_add_param('vc_separator', array(
			'type' => 'dropdown',
			'heading' => __( 'Element width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => array(
                '10%',
                '20%',
                '30%',
                '40%',
                '50%',
                '60%',
                '70%',
                '80%',
                '90%',
                '100%',
            ),
			'description' => __( 'Separator element width in percents.', 'js_composer' ),
            'dependency' => array(
		        'element' => 'style',
		        'value' => array('', 'dashed', 'dotted', 'double')
		    )
		));

        vc_add_param('vc_separator',  array(
			    'type' => 'colorpicker',
			    'heading' => __( 'Border Color', 'Wyde' ),
			    'param_name' => 'color',
			    'description' => __( 'Select border color.', 'Wyde' ),
		));

           
        vc_add_param('vc_separator', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));


        /* Text Separator
        ---------------------------------------------------------- */
        vc_remove_param('vc_text_separator', 'color');
        vc_remove_param('vc_text_separator', 'accent_color');
        vc_remove_param('vc_text_separator', 'el_class');


            
        vc_add_param('vc_text_separator',  array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Border Color', 'Wyde' ),
			        'param_name' => 'color',
			        'description' => __( 'Select border color. If empty "Theme Color Scheme" will be used.', 'Wyde' ),
        ));

           
        vc_add_param('vc_text_separator', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));

        /* Tab Section
        ---------------------------------------------------------- */
        vc_remove_param('vc_tab', 'tab_id');
        vc_remove_param('vc_tab', 'title');

        vc_add_param('vc_tab', array(
			        'type' => 'dropdown',
			        'heading' => __( 'Navigation', 'Wyde' ),
			        'param_name' => 'mode',
			        'value' => array(
				        'Text' => 'text',
				        'Icon' => 'icon',
			        ),
			        'description' => __( 'Select tab navigation mode.', 'Wyde' )
        ));

        vc_add_param('vc_tab', array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Tab title.', 'js_composer' ),
            'dependency' => array(
		        'element' => 'mode',
		        'value' => array('text')
		    )
		));

        /*vc_add_param('vc_tab', array(
            'type' => 'wyde_icons',
            'class' => 'select2-drop-above',
            'heading' => __('Icon', 'Wyde'),
            'param_name' => 'icon_toggle_title',
            'value' => '',
            'description' => __('Select an icon.', 'Wyde'),
            'dependency' => array(
		        'element' => 'mode',
		        'value' => array('icon')
		    )
        ));*/

        vc_add_param('vc_tab', array(
			            'type' => 'dropdown',
			            'heading' => __( 'Icon Set', 'Wyde' ),
			            'value' => array(
				            'Font Awesome' => '',
				            'Open Iconic' => 'openiconic',
				            'Typicons' => 'typicons',
				            'Entypo' => 'entypo',
				            'Linecons' => 'linecons',
			            ),
			            'admin_label' => true,
			            'param_name' => 'icon_set',
			            'description' => __('Select an icon set.', 'Wyde'),
                        'dependency' => array(
		                    'element' => 'mode',
		                    'value' => array('icon')
		                )
                        
		            ));

        vc_add_param('vc_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true,  
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'is_empty' => true,
			            ),
		            ));


         vc_add_param('vc_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_openiconic',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'openiconic',
				            'iconsPerPage' => 4000, 
			            ),
                        'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'openiconic',
			            ),
		            ));

        vc_add_param('vc_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_typicons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'typicons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'typicons',
			            ),
		            ));

         vc_add_param('vc_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_entypo',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'entypo',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'entypo',
			            ),
		            ));

        vc_add_param('vc_tab', array(
			            'type' => 'iconpicker',
			            'heading' => __( 'Icon', 'Wyde' ),
			            'param_name' => 'icon_linecons',
			            'value' => '', 
			            'settings' => array(
				            'emptyIcon' => true, 
				            'type' => 'linecons',
				            'iconsPerPage' => 4000, 
			            ),
			            'description' => __('Select an icon.', 'Wyde'),
			            'dependency' => array(
				            'element' => 'icon_set',
				            'value' => 'linecons',
			            ),
		            ));





        /* Toggle (FAQ)
        ---------------------------------------------------------- */
        vc_remove_param('vc_toggle', 'css_animation');
        vc_remove_param('vc_toggle', 'el_class');

        vc_add_param('vc_toggle', array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
        ));

        vc_add_param('vc_toggle', array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
        ));

        vc_add_param('vc_toggle', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));

            
        /* Tour Section
        ---------------------------------------------------------- */
        vc_remove_param('vc_tour', 'title');


        /* Single Image
        ---------------------------------------------------------- */
        $params = array(
	        'params' => array(
		        array(
			        'type' => 'textfield',
			        'heading' => __( 'Widget Title', 'Wyde' ),
			        'param_name' => 'title',
			        'description' => __( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'Wyde' )
		        ),
		        array(
			        'type' => 'attach_image',
			        'heading' => __( 'Image', 'Wyde' ),
			        'param_name' => 'image',
			        'value' => '',
			        'description' => __( 'Select image from media library.', 'Wyde' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Size', 'Wyde' ),
			        'param_name' => 'img_size',
			        'value' => array(
				        'Thumbnail (150x150)' => 'thumbnail',
				        'Medium (300x300)' => 'medium',
				        'Large (640x640)'=> 'large',
                        'Full (Original)'=> 'full',
				        'Blog Medium (600x340)'=> 'blog-medium',
				        'Blog Large (800x450)'=> 'blog-large',
				        'Blog Full (1066x600)'=> 'blog-full',
			        ),
			        'description' => __( 'Select image size.', 'Wyde' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Alignment', 'Wyde' ),
			        'param_name' => 'alignment',
			        'value' => array(
				        __( 'Align Left', 'Wyde' ) => '',
				        __( 'Align Right', 'Wyde' ) => 'right',
				        __( 'Align Center', 'Wyde' ) => 'center'
			        ),
			        'description' => __( 'Select image alignment.', 'Wyde' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Style', 'Wyde' ),
			        'param_name' => 'style',
			        'value' => getVcShared( 'single image styles' )
		        ),
		        array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Border Color', 'Wyde' ),
			        'param_name' => 'border_color',
			        'dependency' => array(
				        'element' => 'style',
				        'value' => array( 'vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle' )
			        ),
			        'description' => __( 'Select border color.', 'Wyde' ),
			        'param_holder_class' => 'vc_colored-dropdown'
		        ),
		        array(
			        'type' => 'checkbox',
			        'heading' => __( 'Link to large image?', 'Wyde' ),
			        'param_name' => 'img_link_large',
			        'description' => __( 'If selected, image will be linked to the larger image.', 'Wyde' ),
			        'value' => array( __( 'Yes, please', 'Wyde' ) => 'yes' )
		        ),
		        array(
			        'type' => 'dropdown',
			        'heading' => __( 'Link Target', 'Wyde' ),
			        'param_name' => 'img_link_target',
			        'value' => array(
                    	__( 'Pretty Photo', 'Wyde' ) => "prettyphoto",
	                    __( 'Same window', 'Wyde' ) => '_self',
	                    __( 'New window', 'Wyde' ) => "_blank",
                    ),
                    'dependency' => array(
				        'element' => 'img_link_large',
				        'value' => array('yes')
			        )
		        ),
                array(
                      'type' => 'wyde_animation',
                      'class' => '',
                      'heading' => __('Animation', 'Wyde'),
                      'param_name' => 'animation',
                      'description' => __('Select a CSS3 Animation that applies to this element.', 'Wyde')
                ),
                array(
                    'type' => 'textfield',
                    'class' => '',
                    'heading' => __('Animation Delay', 'Wyde'),
                    'param_name' => 'animation_delay',
                    'value' => '',
                    'description' => __('Defines when the animation will start (in seconds). Example: 0.5, 1, 2, ...', 'Wyde'),
                    'dependency' => array(
				        'element' => 'animation',
				        'not_empty' => true
			        )
                ),
		        array(
			        'type' => 'textfield',
			        'heading' => __('Extra CSS Class', 'Wyde'),
			        'param_name' => 'el_class',
			        'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
		        ),
		        array(
			        'type' => 'css_editor',
			        'heading' => __( 'Css', 'Wyde' ),
			        'param_name' => 'css',
			        'group' => __( 'Design options', 'Wyde' )
                ) 
            )
        );

        vc_map_update('vc_single_image', $params);

        /* Progress Bar
        ---------------------------------------------------------- */
        vc_remove_param('vc_progress_bar', 'bgcolor');
        vc_remove_param('vc_progress_bar', 'custombgcolor');
        vc_remove_param('vc_progress_bar', 'el_class');

        vc_add_param('vc_progress_bar', array(
			        'type' => 'colorpicker',
			        'heading' => __( 'Bar Color', 'Wyde' ),
			        'param_name' => 'color',
			        'description' => __( 'Select progress bar color. If empty "Theme Color Scheme" will be used.', 'Wyde' ),
			        'admin_label' => true,
        ));

        vc_add_param('vc_progress_bar', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));

        vc_add_param('vc_progress_bar',  array(
			        'type' => 'css_editor',
			        'heading' => __( 'Css', 'Wyde' ),
			        'param_name' => 'css',
			        'group' => __( 'Design options', 'Wyde' )
        ) );
     
        


        /* Image Gallery
        ---------------------------------------------------------- */
        vc_remove_param('vc_gallery', 'img_size');
        vc_remove_param('vc_gallery', 'el_class');

        vc_add_param('vc_gallery', array(
			        'type' => 'dropdown',
			        'heading' => __( 'Image Size', 'Wyde' ),
			        'param_name' => 'img_size',
			        'value' => array(
				        'Thumbnail (150x150)' => 'thumbnail',
				        'Medium (300x300)' => 'medium',
				        'Large (640x640)'=> 'large',
                        'Full (Original)'=> 'full',
				        'Blog Medium (600x340)'=> 'blog-medium',
				        'Blog Large (800x450)'=> 'blog-large',
				        'Blog Full (1066x600)'=> 'blog-full',
			        ),
			        'description' => __( 'Select image size.', 'Wyde' )
        ));

        vc_add_param('vc_gallery', array(
                      'type' => 'textfield',
                      'class' => '',
                      'heading' => __('Extra CSS Class', 'Wyde'),
                      'param_name' => 'el_class',
                      'value' => '',
                      'description' => __('If you wish to style particular content element differently, then use this field to add a class name.', 'Wyde')
        ));
    }

    public function icons_field($settings, $value) {
    
        $dependency = vc_generate_dependencies_attributes($settings);


        $html = '<div class="wyde-icons">';


        /*$html .= sprintf('<input name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" type="hidden" value="%3$s" %4$s/>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), esc_attr( $value ), $dependency);
        $html .= sprintf('<ul class="list-icons"><li><a href="#"><span class="selected-value">%s</span> <i class="dropit-arrow fa fa-angle-down"></i></a><ul>', $value? '<i class="fa '.$value.'"></i>':'None');
            
        $html .= '<li><a href="#" title="No Icon">None</a></li>';

        $font_awesome_icons  = wyde_get_font_awesome_icons();

        foreach($font_awesome_icons as $key => $text){
            $html .= sprintf('<li><a href="#"><i class="fa %s"></i></a></li>', esc_attr( $key ));
        }

        $html .= '</ul></li></ul></div>';*/

        $html .= sprintf('<select name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" %3$s>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), $dependency);

        $font_awesome_icons  = wyde_get_font_awesome_icons();

        foreach($font_awesome_icons as $key => $val){
            $html .= sprintf('<option value="fa fa-%s" %s>%s</option>', esc_attr( $val ), ($value=='fa fa-'.$val? ' selected':''),  esc_html('&#x'.str_replace('\\', '', $key).'; '.$val));
        }

        $html .= '</select></div>';
        

        return $html;

    }

    public function animation_field($settings, $value) {
    
        $dependency = vc_generate_dependencies_attributes($settings);

        $html ='<div class="wyde-animation">';
        $html .='<div class="animation-field">';
        $html .= sprintf('<select name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" %3$s>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), $dependency);

        $animations  = wyde_get_animations();

        foreach($animations as $key => $text){
            $html .= sprintf('<option value="%s" %s>%s</option>', esc_attr( $key ), ($value==$key? ' selected':''), esc_html( $text ) );
        }

        $html .= '</select></div>';
        $html .= '<div class="animation-preview"><span>Animation</span></div>';
        $html .= '</div>';

        return $html;

    }

    public function gmaps_field($settings, $value) {
    
        $dependency = vc_generate_dependencies_attributes($settings);

        $html ='<div class="wyde-gmaps">';
        $html .='<div class="gmaps-field">';
        $html .= sprintf('<input name="%1$s" class="wpb_vc_param_value %1$s %2$s_field" type="hidden" value="%3$s" %4$s/>', esc_attr( $settings['param_name'] ), esc_attr( $settings['type'] ), esc_attr( $value ), $dependency);
        $html .= sprintf('  <div class="edit_form_line"><input class="map-address" type="text" value="" /><span class="vc_description vc_clearfix">%s</span></div>', __('Enter text to display in the Info Window.', 'Wyde'));
        
        $html .= '  <div class="vc_column vc_clearfix">';
        $html .= '      <div class="vc_col-sm-6">';
        $html .= sprintf('<div class="wpb_element_label">%s</div>', __('Map Type', 'Wyde'));
        $html .= '          <div class="edit_form_line">';
        $html .= '              <select class="wpb-select dropdown map-type"><option value="1">Hybrid</option><option value="2">RoadMap</option><option value="3">Satellite</option><option value="4">Terrain</option></select>';
        $html .= '          </div>';
        $html .= '       </div>';
        $html .= '      <div class="vc_col-sm-6">';
        $html .= sprintf('<div class="wpb_element_label">%s</div>', __('Map Zoom', 'Wyde'));
        $html .= '          <div class="edit_form_line">';
        $html .= '              <select class="wpb-select dropdown map-zoom">';
        for($i=1; $i<=20; $i++){
        $html .= sprintf('          <option value="%1$s">%1$s</option>', $i);
        }
        $html .= '              </select>';
        $html .= '          </div>';
        $html .= '      </div>';
        $html .= '  </div>';
        $html .= '</div>';
        $html .= '<div class="vc_column vc_clearfix">';
        $html .= sprintf('<span class="vc_description vc_clearfix">%s</span>', __('Drag & Drop marker to set your location.', 'Wyde'));
        $html .= '  <div class="gmaps-canvas" style="height:300px;"></div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;

    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function load_scripts() {
      
      //wp_enqueue_style( 'vc-extend-style', plugin_dir_url( __FILE__ ) . '/css/vc-extend.css');
      
    }

    public function load_admin_scripts() {
        
        wp_enqueue_script('vc-extend', plugin_dir_url( __FILE__ ). 'js/vc-extend.js', null, null, true);

        //wp_enqueue_style( 'vc-extend-style', plugin_dir_url( __FILE__ ) . 'css/select2.min.css');
        wp_enqueue_style( 'vc-extend-style', plugin_dir_url( __FILE__ ) . 'css/vc-extend.css', null, '1.2.0');
    
        wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js', null, null, false);
        wp_enqueue_script('googlemaps');

    }

    /*
    Show notice if this theme is activated but Visual Composer is not
    */
    public function show_vc_notice() {
        echo '<div class="updated"><p>'.__('<strong>This theme</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'Wyde').'</p></div>';
    }

    //Disable automatic updates notifications
    public function set_as_theme() {
	    vc_set_as_theme(false);
    }

    //Extract inline css from custom css
    public static function vc_inline_css($custom_css) {
	    $css = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $custom_css ) ? preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$2', $custom_css ) : '';
	    return $css;
    }

}

new Wyde_VCExtend();