<?php
add_filter( 'cmb_meta_boxes', 'vela_metaboxes' );

function vela_metaboxes( array $meta_boxes ) {

    global $wyde_options;

	$prefix = '_meta_';
  
    $template_directory = get_template_directory_uri();

    //Post/Portfolio Options.
    //embeds
    $meta_boxes['embed_options'] = array(
		'id'         => 'embed_options',
		'title'      => __( 'Media Embed Options', 'Vela' ),
		'pages'      => array('post', 'portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
            	'id'       => $prefix . 'media_info',
				'name'     => __( 'You can enter media URL from any major video and audio hosting service (Youtube, Vimeo, DailyMotion, SoundCloud, Mixcloud, WordPress.tv, etc.). Supports services listed at <a href="http://codex.wordpress.org/Embeds" target="_blank">http://codex.wordpress.org/Embeds</a>', 'Vela' ),
				'type'     => 'title',
				'on_front' => false,
			),
			array(
            	'id'   => $prefix . 'embed_url',
				'name' => __( 'Media URL', 'Vela' ),
				'desc' => __( 'Enter a media URL.', 'Vela' ),
				'type' => 'oembed'
			)
        )
    );

    
    //gallery
    $meta_boxes['gallery_options'] = array(
		'id'         => 'gallery_options',
		'title'      => __( 'Gallery Options', 'Vela' ),
		'pages'      => array('post', 'portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(

			array(
            	'id'   => $prefix . 'gallery_images',
				'name' => __( 'Images', 'Vela' ),
				'desc' => __( 'Upload or select images from media library.', 'Vela' ),
				'type' => 'file_list',
                'preview_size' => 'thumbnail', 
			)
        )
    );
    
    //Post Options.
    //post link
    $meta_boxes['link_options'] = array(
		'id'         => 'link_options',
		'title'      => __( 'Link Options', 'Vela' ),
		'pages'      => array('post'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
            	'id'       => $prefix . 'link_info',
				'name'     => __( 'You can enter external or internal URL. For Post Link Format only.', 'Vela' ),
				'type'     => 'title',
				'on_front' => false,
			),
			array(
            	'id'   => $prefix . 'post_link',
				'name' => __( 'Post URL', 'Vela' ),
				'desc' => __( 'Enter a post URL.', 'Vela' ),
				'type' => 'text_url'
			)
        )
    );

    //post quote
    $meta_boxes['quote_options'] = array(
		'id'         => 'quote_options',
		'title'      => __( 'Quote Options', 'Vela' ),
		'pages'      => array('post'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
            	'id'   => $prefix . 'post_quote',
				'name' => __( 'Quote', 'Vela' ),
				'desc' => __( 'Enter quote here.', 'Vela' ),
				'type' => 'textarea_small'
			),
            array(
            	'id'   => $prefix . 'post_quote_author',
				'name' => __( 'Author', 'Vela' ),
				'desc' => __( 'Enter quote\'s author.', 'Vela' ),
				'type' => 'text_medium'
			)
        )
    );
    

    //single post options
    $meta_boxes['post_options'] = array(
		'id'         => 'post_options',
		'title'      => __( 'Single Post Options', 'Vela' ),
		'pages'      => array('post', 'portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
            	'id'         => $prefix . 'post_custom_sidebar',
				'name'       => __( 'Sidebar', 'Vela' ),
				'desc'       => __( 'Check this to customize the sidebar for this post or uncheck to use default settings from theme options.', 'Vela' ),
				'type'       => 'checkbox'
			),
            array(
            	'id'         => $prefix . 'post_sidebar_position',
				'name'       => __( 'Sidebar Position', 'Vela' ),
				'desc'       => __( 'Select sidebar position.', 'Vela' ),
				'type'    => 'radio_inline',
				'options' => array(
					'1' => '<img src="' . $template_directory . '/images/columns/1.png" alt="No Sidebar"/>',
					'2'   => '<img src="' . $template_directory . '/images/columns/2.png" alt="One Left"/>',
					'3'     => '<img src="' . $template_directory . '/images/columns/3.png" alt="One Right"/>',
				),
                'default'   => isset( $wyde_options['blog_single_sidebar'] )? $wyde_options['blog_single_sidebar']:'3',
			),
            array(
            	'id'         => $prefix . 'post_custom_title',
				'name'       => __( 'Title Area', 'Vela' ),
				'desc'       => __( 'Check this to customize the title area for this post.', 'Vela' ),
				'type'       => 'checkbox'
			)

        )
    );

    //title   
	$meta_boxes['title_options'] = array(
		'id'         => 'title_options',
		'title'      => __( 'Title Options', 'Vela' ),
		'pages'      => array('page', 'post', 'portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            
            array(
            	'id'         => $prefix . 'title',
				'name'       => __( 'Page Title', 'Vela' ),
				'desc'       => __( 'Show or Hide the page title.', 'Vela' ),
				'type'       => 'select',
                'options'    => array(
                    'hide' => 'Hide',
                    'show' => 'Show'
                ),
                'default'  => 'show'

			),
            array(				
                'id'   => $prefix . 'title_align',
				'name' => __( 'Title Alignment', 'Vela' ),
				'desc' => __( 'Select the title alignment.', 'Vela' ),
				'type' => 'select',
                'options' => array(
                    '' => 'Default', 
                    'left' => 'Left', 
                    'center' => 'Center', 
                    'right' => 'Right', 
                 ),
                'default' => ''
			),
            array(				
                'id'   => $prefix . 'title_background',
				'name' => __( 'Title Background', 'Vela' ),
				'desc' => __( 'Select a background type for the title.', 'Vela' ),
				'type' => 'select',
                'options' => array(
                    '' => 'Default', 
                    'none' => 'None', 
                    'color' => 'Color', 
                    'image' => 'Image', 
                    'video' => 'Video'
                 ),
                'default' => ''
			),
			array(
				'id'   => $prefix . 'title_background_image',
                'name' => __( 'Background Image', 'Vela' ),
				'desc' => __( 'Upload an image or enter a URL.', 'Vela' ),
				'type' => 'file'
			),
			array(
				'id'   => $prefix . 'title_background_video',
                'name' => __( 'Background Video', 'Vela' ),
				'desc' => __( 'Upload a video or enter a URL.', 'Vela' ),
				'type' => 'file'
			),
            array(
				'id'      => $prefix . 'title_background_color',
				'name'    => __( 'Background Color', 'Vela' ),
				'desc'    => __( 'Choose a background color.', 'Vela' ),
				'type'    => 'colorpicker',
				'default' => ''
			),
			array(
				'id'   => $prefix . 'title_background_size',
				'name' => __( 'Background Size', 'Vela' ),
				'desc' => __( 'For full width background, choose Cover. For repeating background, choose Auto.', 'Vela' ),
				'type' => 'select',
                'options'   => array(
                   'cover' => 'Cover', 
                   'auto' => 'Auto'
                 ),
                'default'  => 'cover'
			),
			array(
				'id'   => $prefix . 'title_background_parallax',
				'name' => __( 'Parallax', 'Vela' ),
				'desc' => __( 'Enable parallax background scrolling.', 'Vela' ),
				'type' => 'checkbox',
                'default'  => ''
			),
            array(
				'id'      => $prefix . 'title_overlay',
				'name'    => __( 'Title Background Overlay', 'Vela' ),
				'desc'    => __( 'Apply an overlay to the title background.', 'Vela' ),
				'type' => 'select',
                'options'   => array(
                   '' => 'None', 
                   'color' => 'Color Overlay', 
                   'pattern' => 'Pattern Overlay'
                 ),
				'default' => ''
			),
            array(
				'id'      => $prefix . 'title_overlay_color',
				'name'    => __( 'Overlay Color', 'Vela' ),
				'desc'    => __( 'Select background color overlay.', 'Vela' ),
				'type'    => 'colorpicker',
				'default' => ''
			),
            array(
				'id'      => $prefix . 'title_mask',
				'name'    => __( 'Content Mask', 'Vela' ),
				'desc'    => __( 'Select content mask style.', 'Vela' ),
				'type' => 'select',
                'options'   => array(
                    ''  => 'Default',
                    'none'  => 'None',
                    '00' =>'0/100', 
                    '10' => '10/90',
                    '20' => '20/80',
                    '30' => '30/70',
                    '40' => '40/60',
                    '50' => '50/50',
                    '60' => '60/40',
                    '70' => '70/30',
                    '80' => '80/20',
                    '90' => '90/10',
                    '100' => '100/0',
                ),
				'default' => ''
			),
            array(
				'id'      => $prefix . 'title_mask_color',
				'name'    => __( 'Mask Color', 'Vela' ),
				'desc'    => __( 'Select content mask color.', 'Vela' ),
				'type'    => 'colorpicker',
				'default' => '#ffffff'
			)
            
		),
	);

    //background
    $meta_boxes['background_options'] = array(
		'id'         => 'background_options',
		'title'      => __( 'Background Options', 'Vela' ),
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
        	array(
				'id'   => $prefix . 'background',
				'name' => __( 'Background', 'Vela' ),
				'desc' => __( 'Select a background type for this page.', 'Vela' ),
				'type' => 'select',
                'options' => array(
                '' => 'Default', 
                'none' => 'None', 
                'color' => 'Color', 
                'image' => 'Image', 
                'video' => 'Video'
                ),
                'default' => ''
			),
			array(
				'id'   => $prefix . 'background_image',
				'name' => __( 'Background Image', 'Vela' ),
				'desc' => __( 'Upload an image or enter a URL.', 'Vela' ),
				'type' => 'file'
			),
			array(
				'id'   => $prefix . 'background_video',
				'name' => __( 'Background Video', 'Vela' ),
				'desc' => __( 'Upload a video or enter a URL.', 'Vela' ),
				'type' => 'file'
			),
            array(
				'id'      => $prefix . 'background_color',
				'name'    => __( 'Background Color', 'Vela' ),
				'desc'    => __( 'Choose a background color.', 'Vela' ),
				'type'    => 'colorpicker',
				'default' => ''
			),
			array(
				'id'   => $prefix . 'background_size',
				'name' => __( 'Background Size', 'Vela' ),
				'desc' => __( 'For full width background, choose Cover. For repeating background, choose Auto.', 'Vela' ),
				'type' => 'select',
                'options'   => array(
                   'cover' => 'Cover', 
                   'auto' => 'Auto'
                 ),
                'default'  => 'cover'
			),
            array(
				'id'      => $prefix . 'background_overlay',
				'name'    => __( 'Background Overlay', 'Vela' ),
				'desc'    => __( 'Apply an overlay to the background.', 'Vela' ),
				'type' => 'select',
                'options'   => array(
                   '' => 'None', 
                   'color' => 'Color Overlay', 
                   'pattern' => 'Pattern Overlay'
                 ),
				'default' => ''
			),
            array(
				'id'      => $prefix . 'background_overlay_color',
				'name'    => __( 'Overlay Color', 'Vela' ),
				'desc'    => __( 'Select background color overlay.', 'Vela' ),
				'type'    => 'colorpicker',
				'default' => ''
			),
			array(
				'id'   => $prefix . 'background_parallax',
				'name' => __( 'Parallax', 'Vela' ),
				'desc' => __( 'Enable parallax background scrolling.', 'Vela' ),
				'type' => 'checkbox',
                'default'  => ''
			)
        )

    );

    //Portfolio Options
    //portfolio post options
    $meta_boxes['portfolio_options'] = array(
		'id'         => 'portfolio_options',
		'title'      => __( 'Portfolio Options', 'Vela' ),
		'pages'      => array('portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
        		array(
                    'id'   => $prefix . 'portfolio_layout',
				    'name' => __( 'Layout', 'Vela' ),
				    'desc' => __( 'Select portfolio layout.', 'Vela' ),
				    'type' => 'radio_inline',
                    'options' => array(
					    '1' => '<img src="' . $template_directory . '/images/portfolio/1.png" alt="Slider" title="Slider"/>',
					    '2'   => '<img src="' . $template_directory . '/images/portfolio/2.png" alt="Gallery" title="Gallery"/>',
					    '3'     => '<img src="' . $template_directory . '/images/portfolio/3.png" alt="Medium" title="Medium"/>',
				    ),
                    'default'   => '1'
			    ),
                array(
                	'id'   => $prefix . 'project_url',
				    'name' => __( 'Project URL', 'Vela' ),
				    'desc' => __( 'Enter a project URL.', 'Vela' ),
				    'type' => 'text_url'
			    )
		)
    );

    //client options
    $meta_boxes['client_options'] = array(
		'id'         => 'client_options',
		'title'      => __( 'Client Information', 'Vela' ),
		'pages'      => array('portfolio'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
        		array(						
                    'id'   => $prefix . 'client_name',
					'name' => 'Name',
                    'description' => 'Enter a client name.',
					'type' => 'text_medium'
				),
				array(
                	'id'   => $prefix . 'client_detail',
					'name' => 'Description',
					'description' => 'Enter a short description about the client.',
					'type' => 'textarea_small',
				),
				array(
                	'id'   => $prefix . 'client_website',
					'name' => 'Website',
                    'description' => 'Enter a client website.',
					'type' => 'text_url',
				)
        )
    );


    //Testimonial Options.
    //customer information
    $meta_boxes['testimonial_options'] = array(
		'id'         => 'testimonial_options',
		'title'      => __( 'Customer Information', 'Vela' ),
		'pages'      => array('testimonial'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
            	'id'       => $prefix . 'testimonial_image',
				'name'     => __( 'Image', 'Vela' ),
                'desc' => __( 'Customer\'s image.', 'Vela' ),
				'type'     => 'file'
			),
            array(
            	'id'       => $prefix . 'testimonial_position',
				'name'     => __( 'Job Position', 'Vela' ),
                'desc' => __( 'Enter a customer\'s job position.', 'Vela' ),

				'type'     => 'text_medium'
			),
            array(
            	'id'       => $prefix . 'testimonial_company',
				'name'     => __( 'Company', 'Vela' ),
                'desc' => __( 'Enter a company name.', 'Vela' ),

				'type'     => 'text_medium'
			),
			array(
            	'id'   => $prefix . 'testimonial_website',
				'name' => __( 'Website', 'Vela' ),
				'desc' => __( 'Enter a URL that applies to this customer or company.', 'Vela' ),
				'type' => 'text_url'
			)
        )
    );

    $socials = wyde_get_social_icons();
    $social_options = array();
    foreach($socials as $key => $value){
       $social_options[$value] = $value; 
    }

    //Team Members Options.
    //member information
    $meta_boxes['member_options'] = array(
		'id'         => 'member_options',
		'title'      => __( 'Member Information', 'Vela' ),
		'pages'      => array('team-member'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
            	'id'       => $prefix . 'member_image',
				'name'     => __( 'Image', 'Vela' ),
                'desc' => __( 'Member\'s image.', 'Vela' ),
				'type'     => 'file'
			),
            array(
            	'id'       => $prefix . 'member_position',
				'name'     => __( 'Job Position', 'Vela' ),
                'desc' => __( 'Enter a member\'s job position.', 'Vela' ),
				'type'     => 'text_medium'
			),
            array(
            	'id'       => $prefix . 'member_email',
				'name'     => __( 'Email Address', 'Vela' ),
                'desc' => __( 'Enter a member\'s contact email address.', 'Vela' ),
				'type'     => 'text_medium'
			),
			array(
            	'id'   => $prefix . 'member_website',
				'name' => __( 'Website', 'Vela' ),
				'desc' => __( 'Enter a URL that applies to this member.', 'Vela' ),
				'type' => 'text_url'
			),
            array(
                'id'          => $prefix . 'member_socials',
                'type'        => 'group',
                'name' => __( 'Social Networks', 'Vela' ),
                'options'     => array(
                    'group_title'   => __( 'Social {#}', 'Vela' ), // since version 1.1.4, {#} gets replaced by row number
                    'add_button'    => __( 'Add Another Social URL', 'Vela' ),
                    'remove_button' => __( 'Remove Social', 'Vela')
                 ),
                'fields'      => array(
                    array(
                        'id'   => 'social',
                        'name' => 'Social networking websites',
                        'type' => 'select',
                        'description' => __('Select a social networking websites.', 'Vela'),
                        'options'   => $social_options
                    ),
                    array(
                        'id'   => 'url',
                        'name' => 'URL',
                        'description' => __('Enter member\'s profile URL or personal page.', 'Vela'),
                        'type' => 'text_url'
                    )
                )
            )
        )
    );

    //Page Options
    //sidebar
    $meta_boxes['sidebar_options'] = array(
		'id'         => 'sidebar_options',
		'title'      => __( 'Sidebar Options', 'Vela' ),
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
            	'id'         => $prefix . 'sidebar_position',
				'name'       => __( 'Sidebar Position', 'Vela' ),
				'desc'       => __( 'Select sidebar position.', 'Vela' ),
				'type'    => 'radio_inline',
				'options' => array(
					'1' => '<img src="' . $template_directory . '/images/columns/1.png" alt="No Sidebar"/>',
					'2'   => '<img src="' . $template_directory . '/images/columns/2.png" alt="One Left"/>',
					'3'     => '<img src="' . $template_directory . '/images/columns/3.png" alt="One Right"/>',
				),
                'default'   => '1'
			)
        )
    );


    //header options
    $meta_boxes['header_options'] = array(
		'id'         => 'header_options',
		'title'      => __( 'Header Options', 'Vela' ),
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
            	'id'         => $prefix . 'page_header',
				'name'       => __( 'Header', 'Vela' ),
				'desc'       => __( 'Show or hide the header.', 'Vela' ),
				'type'    => 'select',
				'options' => array(
                   ''   => 'Show',
                   'hide'   => 'Hide',
                )
			),
            array(
            	'id'         => $prefix . 'menu_style',
				'name'       => __( 'Menu Style', 'Vela' ),
				'desc'       => __( 'Select a menu style. Choose "Light" for the header which has "Dark" background color.', 'Vela' ),
				'type'    => 'select',
				'options' => array(
                   ''   => 'Default',
                   'light'   => 'Light',
                   'dark'   => 'Dark',
                )
			)
        )
    );

    //Slider items

    global $wpdb;

    //Slider Revolution
    $revsliders[0] = 'Select a slider';
    if(function_exists('rev_slider_shortcode')) {
	    $get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders');
	    if($get_sliders) {
		    foreach($get_sliders as $slider) {
			    $revsliders[$slider->alias] = $slider->title;
		    }
	    }
    }

    $meta_boxes['slider_options'] = array(
		'id'         => 'slider_options',
		'title'      => __( 'Slider Options', 'Vela' ),
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
            	'id'         => $prefix . 'slider_show',
				'name'       => __( 'Show Slider', 'Vela' ),
				'desc'       => __( 'Show the slider at top of the content.', 'Vela' ),
				'type'       => 'checkbox'
			),
            array(
            	'id'         => $prefix . 'slider_item',
				'name'       => __( 'Select Slider Item', 'Vela' ),
				'desc'       => __( 'Select a slider item to display.', 'Vela' ),
				'type'       => 'select',
                'options'   => $revsliders
			)
            
        )
    );

    

    //footer options
    $meta_boxes['footer_options'] = array(
		'id'         => 'footer_options',
		'title'      => __( 'Footer Options', 'Vela' ),
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
            array(
			    'id'         => $prefix . 'page_footer',
				'name'       => __( 'Footer', 'Vela' ),
				'desc'       => __( 'Show or hide the footer.', 'Vela' ),
				'type'      => 'select',
				'options'   => array(
                   ''   => 'Show',
                   'hide'   => 'Hide',
                ),
            )
          )
    );

	return $meta_boxes;
}

function wyde_get_social_icons(){
    
    return array(
            'fa-behance'    => 'Behance',
            'fa-dribbble'   => 'Dribbble',
            'fa-facebook'   => 'Facebook',
            'fa-flickr'     => 'Flickr',
            'fa-github-alt' => 'Github',
            'fa-google-plus'    => 'Google+',
            'fa-instagram'  => 'Instagram',
            'fa-linkedin'   => 'LinkedIn',
            'fa-pinterest'  => 'Pinterest',
            'fa-tumblr'     => 'Tumblr',
            'fa-twitter'    => 'Twitter',
            'fa-vimeo-square'   => 'Vimeo',
            'fa-youtube'    => 'Youtube',
            );
}


/*add_filter( 'cmb_render_select_icons', 'render_select_icons', 10, 2 );
function render_select_icons( $field_args, $value ) {

    $font_awesome_icons  = get_font_awesome_icons();

    $html = '<div class="select-icons">';
    $html .= sprintf('<input name="%1$s" class="select_icons_field" type="hidden" value="%2$s" />', $field_args['id'], $value);
    $html .= sprintf('<ul class="list-icons"><li><a href="#"><span class="selected-value">%s</span> <i class="dropit-arrow fa fa-angle-down"></i></a><ul>', ($value && $value!='none')? '<i class="fa '.$value.'"></i>':'None');
            
    $html .= '<li><a href="#" title="No Icon">None</a></li>';

    foreach($font_awesome_icons as $key => $text){
        $html .= sprintf('<li><a href="#" title="%s"><i class="fa %s"></i></a></li>', $text, $key);
    }

    $html .= '</ul></li></ul></div>';

    echo $html;

    if ( ! empty( $field_args['desc'] ) ) {
        echo '<p class="cmb_metabox_description">' . $field_args['desc'] . '</p>';
    }

}
*/

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

function cmb_initialize_cmb_meta_boxes() {


	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';


}

add_action('admin_enqueue_scripts', 'options_load_scripts');
function options_load_scripts($hook) {
    if( $hook != 'post.php' && $hook != 'post-new.php' ) 
        return;
 
    $template_directory = get_template_directory_uri();


    wp_enqueue_style( 'metabox-style', $template_directory . '/inc/metaboxes/css/custom.css',  null, null);
   
	$min = defined('WP_DEBUG') && WP_DEBUG? '' : '.min';

    wp_enqueue_script( 'metabox-options', $template_directory.'/inc/metaboxes/js/vela-options'.$min.'.js', null, '1.4.1', false );

}