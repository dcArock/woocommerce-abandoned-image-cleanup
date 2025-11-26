<?php
/**
 * Admin Page Template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap wc-aic-wrap">
    <h1><?php esc_html_e('Scan your media library for abandoned images', 'wc-abandoned-image-cleanup'); ?></h1>

    <div class="wc-aic-intro">
        <p><?php esc_html_e('This tool will scan your media library and identify images that are not attached to any WooCommerce products. This includes images from deleted products or images that were uploaded but never used.', 'wc-abandoned-image-cleanup'); ?></p>
        <p class="wc-aic-warning">
            <span class="dashicons dashicons-warning"></span>
            <strong><?php esc_html_e('Important:', 'wc-abandoned-image-cleanup'); ?></strong>
            <?php esc_html_e('Always create a backup of your media library before deleting images. Deleted images are removed permanently and cannot be recovered!', 'wc-abandoned-image-cleanup'); ?>
        </p>
    </div>

    <div class="wc-aic-scan-section">
        <button type="button" class="button button-primary button-hero wc-aic-scan-btn" id="wc-aic-scan-btn">
            <span class="dashicons dashicons-search"></span>
            <?php esc_html_e('SCAN', 'wc-abandoned-image-cleanup'); ?>
        </button>

        <div class="wc-aic-scan-progress" id="wc-aic-scan-progress" style="display: none;">
            <span class="spinner is-active"></span>
            <span class="wc-aic-progress-text"><?php esc_html_e('Scanning your media library...', 'wc-abandoned-image-cleanup'); ?></span>
        </div>
    </div>

    <div class="wc-aic-stats" id="wc-aic-stats" style="display: none;">
        <div class="wc-aic-stat-box">
            <div class="wc-aic-stat-number" id="wc-aic-total-images">0</div>
            <div class="wc-aic-stat-label"><?php esc_html_e('Total Images', 'wc-abandoned-image-cleanup'); ?></div>
        </div>
        <div class="wc-aic-stat-box">
            <div class="wc-aic-stat-number" id="wc-aic-product-images">0</div>
            <div class="wc-aic-stat-label"><?php esc_html_e('Used in Products', 'wc-abandoned-image-cleanup'); ?></div>
        </div>
        <div class="wc-aic-stat-box wc-aic-stat-abandoned">
            <div class="wc-aic-stat-number" id="wc-aic-abandoned-count">0</div>
            <div class="wc-aic-stat-label"><?php esc_html_e('Abandoned Images', 'wc-abandoned-image-cleanup'); ?></div>
        </div>
    </div>

    <div class="wc-aic-results" id="wc-aic-results" style="display: none;">
        <div class="wc-aic-results-header">
            <h2><?php esc_html_e('Abandoned Images', 'wc-abandoned-image-cleanup'); ?></h2>
            <div class="wc-aic-bulk-actions">
                <label>
                    <input type="checkbox" id="wc-aic-select-all">
                    <?php esc_html_e('Select All', 'wc-abandoned-image-cleanup'); ?>
                </label>
                <button type="button" class="button button-secondary" id="wc-aic-delete-selected">
                    <span class="dashicons dashicons-trash"></span>
                    <?php esc_html_e('Delete Selected', 'wc-abandoned-image-cleanup'); ?>
                </button>
                <span class="wc-aic-selected-count" id="wc-aic-selected-count">
                    <?php esc_html_e('0 images selected', 'wc-abandoned-image-cleanup'); ?>
                </span>
            </div>
        </div>

        <div class="wc-aic-delete-warning" id="wc-aic-delete-warning" style="display: none;">
            <div class="notice notice-warning inline">
                <p>
                    <span class="dashicons dashicons-warning"></span>
                    <strong><?php esc_html_e('Warning:', 'wc-abandoned-image-cleanup'); ?></strong>
                    <?php esc_html_e('You are about to permanently delete images. Please ensure you have a backup before proceeding!', 'wc-abandoned-image-cleanup'); ?>
                </p>
            </div>
        </div>

        <div class="wc-aic-images-grid" id="wc-aic-images-grid">
            <!-- Images will be loaded here via JavaScript -->
        </div>
    </div>

    <div class="wc-aic-no-results" id="wc-aic-no-results" style="display: none;">
        <div class="notice notice-success inline">
            <p>
                <span class="dashicons dashicons-yes-alt"></span>
                <strong><?php esc_html_e('Great news!', 'wc-abandoned-image-cleanup'); ?></strong>
                <?php esc_html_e('No abandoned images found. Your media library is clean!', 'wc-abandoned-image-cleanup'); ?>
            </p>
        </div>
    </div>
</div>
