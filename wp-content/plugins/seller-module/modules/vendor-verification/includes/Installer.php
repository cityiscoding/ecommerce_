<?php

namespace WeDevs\DokanPro\Modules\VendorVerification;

use WeDevs\DokanPro\Modules\VendorVerification\Models\VerificationMethod;

defined( 'ABSPATH' ) || exit;

/**
 * Installer Class.
 *
 * @since 3.11.1
 */
class Installer {

    /**
     * Run Installer.
     */
    public function run() {
        $this->add_roles();
        $this->create_table();
    }

    /**
     * Create Database table.
     *
     * @since 3.11.1
     * @return void
     */
    protected function create_table() {
        global $wpdb;

        $verification_methods = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}dokan_vendor_verification_methods` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL DEFAULT '',
            `help_text` text,
            `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `kind` varchar(255) DEFAULT '',
            `required` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `created_at` int(12) unsigned NOT NULL DEFAULT 0,
            `updated_at` int(12) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) {$wpdb->get_charset_collate()};";

        $verification_requests = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}dokan_vendor_verification_requests` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `vendor_id` bigint(20) unsigned NOT NULL,
            `method_id` bigint(20) unsigned NOT NULL,
            `status` varchar(100) NOT NULL,
            `checked_by` bigint(20) unsigned DEFAULT 0,
            `additional_info` text,
            `documents` text,
            `note` text,
            `created_at` int(12) unsigned NOT NULL DEFAULT 0,
            `updated_at` int(12) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`method_id`) REFERENCES `{$wpdb->prefix}dokan_vendor_verification_methods`(`id`) ON DELETE CASCADE,
            INDEX `verification` (vendor_id,method_id,status)
        ) {$wpdb->get_charset_collate()};";

        if ( ! function_exists( 'dbDelta' ) ) {
            require ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $verification_methods );
        dbDelta( $verification_requests );

        $this->seed_table();
    }

    /**
     * Seed verification Method database.
     *
     * @since 3.11.1
     * @return void
     */
    protected function seed_table() {
        if ( ! empty( ( new VerificationMethod() )->count( [] ) ) ) {
            return;
        }

        try {
            $passport = new VerificationMethod();
            $passport
                ->set_title( __( 'Hộ chiếu', 'dokan' ) )
                ->set_help_text( __( 'Tải lên một bản sao được quét hoặc ảnh của hộ chiếu hợp lệ của bạn.', 'dokan' ) )
                ->set_enabled( VerificationMethod::STATUS_ENABLED )
                ->set_required( false )
                ->create();

            $national_id = new VerificationMethod();
            $national_id
                ->set_title( __( 'CCCD', 'dokan' ) )
                ->set_help_text( __( 'PLEASE', 'dokan' ) )
                ->set_enabled( VerificationMethod::STATUS_ENABLED )
                ->set_required( false )
                ->create();

            $driving_license = new VerificationMethod();
            $driving_license
                ->set_title( __( 'Giấy phép lái xe', 'dokan' ) )
                ->set_help_text( __( 'Vui lòng tải lên một bản sao được quét hoặc hình ảnh giấy phép lái xe của bạn.', 'dokan' ) )
                ->set_enabled( VerificationMethod::STATUS_ENABLED )
                ->set_required( false )
                ->create();

            $address = new VerificationMethod();
            $address
                ->set_title( __( 'Địa chỉ', 'dokan' ) )
                ->set_help_text( __( 'Điền chính xác vào tất cả các trường địa chỉ và đính kèm tài liệu có cùng địa chỉ. Đảm bảo tài liệu được cập nhật và các trường bắt buộc được điền.', 'dokan' ) )
                ->set_enabled( VerificationMethod::STATUS_ENABLED )
                ->set_kind( VerificationMethod::TYPE_ADDRESS )
                ->set_required( true )
                ->create();

            $company = new VerificationMethod();
            $company
                ->set_title( __( 'Công ty', 'dokan' ) )
                ->set_help_text( __( "Tải lên các tài liệu đăng ký của công ty bạn.", 'dokan' ) )
                ->set_enabled( VerificationMethod::STATUS_ENABLED )
                ->set_required( true )
                ->create();

            $ids = [
                'passport'        => $passport->get_id(),
                'national_id'     => $national_id->get_id(),
                'driving_license' => $driving_license->get_id(),
                'address'         => $address->get_id(),
                'company'         => $company->get_id(),
            ];

            update_option( 'dokan_vendor_verification_initial_method_ids', $ids, false );
        } catch ( \Exception $e ) {
            dokan_log( $e->getMessage() );
        }
    }

    /**
     * Add necessary Roles.
     *
     * @since 3.11.1 Code migration from module page.
     * @return void
     */
    protected function add_roles() {
        global $wp_roles;

        if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }

        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new \WP_Roles(); //phpcs:ignore
        }

        $wp_roles->add_cap( 'seller', 'dokan_view_store_verification_menu' );
        $wp_roles->add_cap( 'administrator', 'dokan_view_store_verification_menu' );
        $wp_roles->add_cap( 'shop_manager', 'dokan_view_store_verification_menu' );
    }
}