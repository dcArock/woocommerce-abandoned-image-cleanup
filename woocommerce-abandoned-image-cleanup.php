<?php
/**
 * Plugin Name: WooCommerce Abandoned Image Cleanup
 * Plugin URI: https://github.com/dcArock/woocommerce-abandoned-image-cleanup
 * Description: Scan your media library for images not attached to any WooCommerce products and clean them up easily.
 * Version: 1.1.0
 * Author: dcArock
 * Author URI: https://github.com/dcArock
 * Text Domain: wc-abandoned-image-cleanup
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WC_AIC_VERSION', '1.1.0');
define('WC_AIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WC_AIC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WC_AIC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class WC_Abandoned_Image_Cleanup {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if WooCommerce is active
        add_action('plugins_loaded', array($this, 'check_woocommerce'));

        // Initialize plugin
        add_action('init', array($this, 'init'));

        // Admin hooks
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

            // AJAX handlers
            add_action('wp_ajax_wc_aic_scan_images', array($this, 'ajax_scan_images'));
            add_action('wp_ajax_wc_aic_delete_images', array($this, 'ajax_delete_images'));
        }
    }

    /**
     * Check if WooCommerce is active
     */
    public function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            deactivate_plugins(WC_AIC_PLUGIN_BASENAME);
        }
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e('WooCommerce Abandoned Image Cleanup requires WooCommerce to be installed and active.', 'wc-abandoned-image-cleanup'); ?></p>
        </div>
        <?php
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('wc-abandoned-image-cleanup', false, dirname(WC_AIC_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Abandoned Images', 'wc-abandoned-image-cleanup'),
            __('Abandoned Images', 'wc-abandoned-image-cleanup'),
            'manage_woocommerce',
            'wc-abandoned-images',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on our admin page
        if ('woocommerce_page_wc-abandoned-images' !== $hook) {
            return;
        }

        // Enqueue WordPress media styles
        wp_enqueue_media();

        // Enqueue custom CSS
        wp_enqueue_style(
            'wc-aic-admin',
            WC_AIC_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WC_AIC_VERSION
        );

        // Enqueue custom JS
        wp_enqueue_script(
            'wc-aic-admin',
            WC_AIC_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            WC_AIC_VERSION,
            true
        );

        // Localize script
        wp_localize_script('wc-aic-admin', 'wcAicData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wc_aic_nonce'),
            'strings' => array(
                'scanning' => __('Scanning...', 'wc-abandoned-image-cleanup'),
                'scanComplete' => __('Scan complete!', 'wc-abandoned-image-cleanup'),
                'scanError' => __('An error occurred during scanning.', 'wc-abandoned-image-cleanup'),
                'deleteConfirm' => __('Are you sure you want to permanently delete the selected images? This action cannot be undone!', 'wc-abandoned-image-cleanup'),
                'deleting' => __('Deleting...', 'wc-abandoned-image-cleanup'),
                'deleteComplete' => __('Images deleted successfully!', 'wc-abandoned-image-cleanup'),
                'deleteError' => __('An error occurred during deletion.', 'wc-abandoned-image-cleanup'),
                'selectImages' => __('Please select at least one image to delete.', 'wc-abandoned-image-cleanup'),
            )
        ));
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        include WC_AIC_PLUGIN_DIR . 'includes/admin-page.php';
    }

    /**
     * AJAX: Scan images
     */
    public function ajax_scan_images() {
        check_ajax_referer('wc_aic_nonce', 'nonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'wc-abandoned-image-cleanup')));
        }

        // Get all media library images
        $all_images = $this->get_all_media_images();

        // Get all WooCommerce product images
        $product_images = $this->get_all_product_images();

        // Find abandoned images
        $abandoned_images = $this->find_abandoned_images($all_images, $product_images);

        // Get image details for display
        $image_details = $this->get_image_details($abandoned_images);

        wp_send_json_success(array(
            'total_images' => count($all_images),
            'product_images' => count($product_images),
            'abandoned_count' => count($abandoned_images),
            'abandoned_images' => $image_details
        ));
    }

    /**
     * Get all media library images
     */
    private function get_all_media_images() {
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids'
        );

        $query = new WP_Query($args);
        return $query->posts;
    }

    /**
     * Get all WooCommerce product images
     */
    private function get_all_product_images() {
        global $wpdb;

        $image_ids = array();

        // Get all products
        $products = wc_get_products(array(
            'limit' => -1,
            'status' => array('publish', 'pending', 'draft', 'future', 'private', 'trash')
        ));

        foreach ($products as $product) {
            // Get featured image
            $featured_image_id = $product->get_image_id();
            if ($featured_image_id) {
                $image_ids[] = $featured_image_id;
            }

            // Get gallery images
            $gallery_image_ids = $product->get_gallery_image_ids();
            if (!empty($gallery_image_ids)) {
                $image_ids = array_merge($image_ids, $gallery_image_ids);
            }

            // For variable products, check variations
            if ($product->is_type('variable')) {
                $variations = $product->get_children();
                foreach ($variations as $variation_id) {
                    $variation = wc_get_product($variation_id);
                    if ($variation) {
                        $variation_image_id = $variation->get_image_id();
                        if ($variation_image_id) {
                            $image_ids[] = $variation_image_id;
                        }
                    }
                }
            }
        }

        // Remove duplicates
        $image_ids = array_unique($image_ids);

        return $image_ids;
    }

    /**
     * Find abandoned images
     */
    private function find_abandoned_images($all_images, $product_images) {
        return array_diff($all_images, $product_images);
    }

    /**
     * Get image details for display
     */
    private function get_image_details($image_ids) {
        $images = array();

        foreach ($image_ids as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            $full_image_url = wp_get_attachment_image_url($image_id, 'full');
            $title = get_the_title($image_id);
            $file_size = size_format(filesize(get_attached_file($image_id)));
            $upload_date = get_the_date('', $image_id);

            $images[] = array(
                'id' => $image_id,
                'thumbnail' => $image_url,
                'full' => $full_image_url,
                'title' => $title,
                'size' => $file_size,
                'date' => $upload_date
            );
        }

        return $images;
    }

    /**
     * AJAX: Delete images
     */
    public function ajax_delete_images() {
        check_ajax_referer('wc_aic_nonce', 'nonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'wc-abandoned-image-cleanup')));
        }

        $image_ids = isset($_POST['image_ids']) ? array_map('intval', $_POST['image_ids']) : array();

        if (empty($image_ids)) {
            wp_send_json_error(array('message' => __('No images selected.', 'wc-abandoned-image-cleanup')));
        }

        $deleted_count = 0;
        $failed_count = 0;

        foreach ($image_ids as $image_id) {
            // Force delete (bypass trash)
            if (wp_delete_attachment($image_id, true)) {
                $deleted_count++;
            } else {
                $failed_count++;
            }
        }

        wp_send_json_success(array(
            'deleted' => $deleted_count,
            'failed' => $failed_count,
            'message' => sprintf(
                __('%d images deleted successfully.', 'wc-abandoned-image-cleanup'),
                $deleted_count
            )
        ));
    }
}

// Declare HPOS compatibility
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Initialize plugin
function wc_abandoned_image_cleanup() {
    return WC_Abandoned_Image_Cleanup::get_instance();
}

// Start the plugin
wc_abandoned_image_cleanup();
