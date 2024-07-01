<?php

namespace WeDevs\DokanPro\Announcement;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Announcement Post Type
 *
 * @since 3.9.4
 */
class PostType {
    /*
     * Post type name
     *
     * @since 3.9.4
     *
     * @var string
     */
    private $post_type = 'dokan_announcement';

    /**
     * Class constructor
     *
     * @since 3.9.4
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ],20 );
    }

    /**
     * Register announcement post type
     *
     * @since 2.1
     * @since 3.9.4 moved this method from Announcement class to PostType class
     *
     * @return void
     */
    public function register_post_type() {
        register_post_type(
            $this->post_type, array(
                'label'           => __( 'Thông báo', 'dokan' ),
                'description'     => '',
                'public'          => false,
                'show_ui'         => true,
                'show_in_menu'    => false,
                'capability_type' => 'post',
                'hierarchical'    => false,
                'rewrite'         => array( 'slug' => '' ),
                'query_var'       => false,
                'supports'        => array( 'title', 'editor' ),
                'labels'          => array(
                    'name'               => __( 'Announcement', 'dokan' ),
                    'singular_name'      => __( 'Announcement', 'dokan' ),
                    'menu_name'          => __( 'Dokan Announcement', 'dokan' ),
                    'add_new'            => __( 'Thêm thông báo', 'dokan' ),
                    'add_new_item'       => __( 'Thêm thông báo mới', 'dokan' ),
                    'edit'               => __( 'Chỉnh sửa', 'dokan' ),
                    'edit_item'          => __( 'Chỉnh sửa thông báo', 'dokan' ),
                    'new_item'           => __( 'Thông báo mới', 'dokan' ),
                    'view'               => __( 'Xem thông báo', 'dokan' ),
                    'view_item'          => __( 'Xem thông báo', 'dokan' ),
                    'search_items'       => __( 'Thông báo tìm kiếm', 'dokan' ),
                    'not_found'          => __( 'Không tìm thấy thông báo', 'dokan' ),
                    'not_found_in_trash' => __( 'Không có thông báo nào được tìm thấy trong thùng rác', 'dokan' ),
                    'parent'             => __( 'Parent Announcement', 'dokan' ),
                ),
            )
        );
    }
}