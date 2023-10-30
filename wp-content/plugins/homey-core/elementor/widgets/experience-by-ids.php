<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Experience by IDs Widget.
 * @since 1.0.1
 */
class Homey_Elementor_experience_By_IDs extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0.1
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'homey_elementor_exp_property_by_ids';
    }

    /**
     * Get widget title.
     * @since 1.0.1
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Experiences by IDs', 'homey-core' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.1
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-list';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.1
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'homey-elements' ];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.1
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label'     => esc_html__( 'Content', 'homey-core' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'experience_style',
            [
                'label'     => esc_html__( 'Experience style', 'homey-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'grid'  => 'Grid View',
                    'grid-v2'  => 'Grid View v2',
                    'card'    => 'Card View'
                ],
                'description' => esc_html__('Select grid/card style, the default style will be list view', 'homey'),
                'default' => 'grid',
            ]
        );
        $this->add_control(
            'columns',
            [
                'label'     => esc_html__( 'Columns in Row', 'homey-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '2cols'  => '2 Columns',
                    '3cols'    => '3 Columns'
                ],
                'description' => '',
                'default' => '3cols',
            ]
        );

        $this->add_control(
            'experience_ids',
            [
                'label'     => esc_html__( 'Experience IDs', 'homey-core' ),
                'type'      => Controls_Manager::TEXT,
                'description'   => esc_html__( 'Enter Experiences ids comma separated. Ex 12,305,34', 'homey-core' ),
            ]
        );
        
        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Styling
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'typography_section',
            [
                'label'     => esc_html__( 'Typography', 'homey-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'hz_property_title',
                'label'    => esc_html__( 'Listing Title', 'homey-core' ),
                'selector' => '{{WRAPPER}} .experience-item .title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'hz_prop_address',
                'label'    => esc_html__( 'Address', 'homey-core' ),
                'selector' => '{{WRAPPER}} address.item-address',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'hz_item_price',
                'label'    => esc_html__( 'Price', 'homey-core' ),
                'selector' => '{{WRAPPER}} .item-price',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'hz_item_types',
                'label'    => esc_html__( 'Type', 'homey-core' ),
                'selector' => '{{WRAPPER}} .item-type',
            ]
        );
    
        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Margin and Spacing
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'hz_spacing_margin_section',
            [
                'label'     => esc_html__( 'Spaces & Sizes', 'homey-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'experience_style!' => 'card',
                ],
            ]
        );

        $this->add_responsive_control(
            'hz_title_margin_bottom',
            [
                'label' => esc_html__( 'Title Margin Bottom(px)', 'homey-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .experience-item .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hz_address_margin_bottom',
            [
                'label' => esc_html__( 'Address Margin Bottom(px)', 'homey-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .item-address' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'hz_content_padding',
            [
                'label'      => esc_html__( 'Content Area Padding', 'homey-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .item-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Box Shadow
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'hz_grid_box_shadow',
            [
                'label' => esc_html__( 'Box', 'homey-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'grid_bg_color',
            [
                'label'     => esc_html__( 'Background', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .item-body' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .item-wrap.item-grid-view .property-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .item-wrap.item-list-view .property-item' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'experience_style!' => 'card',
                ],
            ]
        );

        $this->add_responsive_control(
            'hz_box_radius',
            [
                'label'      => esc_html__( 'Radius', 'homey-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .item-wrap.item-grid-view .property-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .item-list-view .item-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .item-card-view .item-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'homey-core' ),
                'selector' => '{{WRAPPER}} .item-wrap.item-grid-view .property-item',
                'condition' => [
                    'experience_style' => 'grid',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow_card',
                'label'    => esc_html__( 'Box Shadow', 'homey-core' ),
                'selector' => '{{WRAPPER}} .item-card-view .item-wrap',
                'condition' => [
                    'experience_style' => 'card',
                ],
            ]
        );

        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Colors
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'hz_grid_colors',
            [
                'label' => esc_html__( 'Colors', 'homey-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Price', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .item-price' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .title a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'address_color',
            [
                'label'     => esc_html__( 'Address Color', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .item-address' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icons_color',
            [
                'label'     => esc_html__( 'Icons', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} i.homey-icon' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'labels_color',
            [
                'label'     => esc_html__( 'Labels', 'homey-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} span.star-text-right' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .item-type' => 'color: {{VALUE}}',
                    '{{WRAPPER}} span.star-text-right a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.1
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
                
        $args['experience_style'] =  $settings['experience_style'];
        $args['columns'] =  $settings['columns'];
        $args['experience_ids']     =  $settings['experience_ids'];
       
        if( function_exists( 'homey_experience_by_ids' ) ) {
            echo homey_experience_by_ids( $args );
        }

    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Homey_Elementor_experience_By_IDs );