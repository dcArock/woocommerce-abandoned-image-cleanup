/**
 * WooCommerce Abandoned Image Cleanup - Admin JavaScript
 */

(function($) {
    'use strict';

    let abandonedImages = [];
    let selectedImages = [];

    /**
     * Initialize
     */
    $(document).ready(function() {
        initScanButton();
        initSelectAll();
        initDeleteButton();
    });

    /**
     * Initialize Scan Button
     */
    function initScanButton() {
        $('#wc-aic-scan-btn').on('click', function() {
            performScan();
        });
    }

    /**
     * Perform Scan
     */
    function performScan() {
        const $btn = $('#wc-aic-scan-btn');
        const $progress = $('#wc-aic-scan-progress');
        const $stats = $('#wc-aic-stats');
        const $results = $('#wc-aic-results');
        const $noResults = $('#wc-aic-no-results');

        // Reset state
        abandonedImages = [];
        selectedImages = [];

        // Hide previous results
        $stats.hide();
        $results.hide();
        $noResults.hide();

        // Show progress
        $btn.prop('disabled', true);
        $progress.show();

        // AJAX request
        $.ajax({
            url: wcAicData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wc_aic_scan_images',
                nonce: wcAicData.nonce
            },
            success: function(response) {
                if (response.success) {
                    abandonedImages = response.data.abandoned_images;

                    // Update stats
                    $('#wc-aic-total-images').text(response.data.total_images);
                    $('#wc-aic-product-images').text(response.data.product_images);
                    $('#wc-aic-abandoned-count').text(response.data.abandoned_count);

                    // Show stats
                    $stats.fadeIn();

                    // Display results
                    if (response.data.abandoned_count > 0) {
                        displayImages(response.data.abandoned_images);
                        $results.fadeIn();
                    } else {
                        $noResults.fadeIn();
                    }
                } else {
                    alert(response.data.message || wcAicData.strings.scanError);
                }
            },
            error: function() {
                alert(wcAicData.strings.scanError);
            },
            complete: function() {
                $btn.prop('disabled', false);
                $progress.hide();
            }
        });
    }

    /**
     * Display Images Grid
     */
    function displayImages(images) {
        const $grid = $('#wc-aic-images-grid');
        $grid.empty();

        images.forEach(function(image) {
            const $item = $('<div>')
                .addClass('wc-aic-image-item')
                .attr('data-image-id', image.id);

            const $checkbox = $('<input>')
                .attr('type', 'checkbox')
                .addClass('wc-aic-image-checkbox')
                .attr('data-image-id', image.id);

            const $thumbnail = $('<div>')
                .addClass('wc-aic-image-thumbnail')
                .html('<img src="' + image.thumbnail + '" alt="' + image.title + '">');

            const $info = $('<div>')
                .addClass('wc-aic-image-info')
                .html(
                    '<div class="wc-aic-image-title" title="' + image.title + '">' + image.title + '</div>' +
                    '<div class="wc-aic-image-meta">' +
                    '<span>' + image.size + '</span>' +
                    '<span>' + image.date + '</span>' +
                    '</div>'
                );

            $item.append($checkbox, $thumbnail, $info);
            $grid.append($item);
        });

        // Bind click events
        bindImageSelection();
    }

    /**
     * Bind Image Selection
     */
    function bindImageSelection() {
        // Individual image click (toggle selection)
        $('.wc-aic-image-item').on('click', function(e) {
            if ($(e.target).is('input[type="checkbox"]')) {
                return; // Let checkbox handle its own event
            }

            const $item = $(this);
            const $checkbox = $item.find('.wc-aic-image-checkbox');
            $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
        });

        // Checkbox change event
        $('.wc-aic-image-checkbox').on('change', function() {
            const $item = $(this).closest('.wc-aic-image-item');
            const imageId = parseInt($(this).attr('data-image-id'));

            if ($(this).is(':checked')) {
                $item.addClass('selected');
                if (selectedImages.indexOf(imageId) === -1) {
                    selectedImages.push(imageId);
                }
            } else {
                $item.removeClass('selected');
                const index = selectedImages.indexOf(imageId);
                if (index > -1) {
                    selectedImages.splice(index, 1);
                }
            }

            updateSelectionCount();
            updateSelectAllCheckbox();
            toggleDeleteWarning();
        });
    }

    /**
     * Initialize Select All
     */
    function initSelectAll() {
        $('#wc-aic-select-all').on('change', function() {
            const isChecked = $(this).is(':checked');

            $('.wc-aic-image-checkbox').each(function() {
                const $checkbox = $(this);
                const $item = $checkbox.closest('.wc-aic-image-item');
                const imageId = parseInt($checkbox.attr('data-image-id'));

                $checkbox.prop('checked', isChecked);

                if (isChecked) {
                    $item.addClass('selected');
                    if (selectedImages.indexOf(imageId) === -1) {
                        selectedImages.push(imageId);
                    }
                } else {
                    $item.removeClass('selected');
                    selectedImages = [];
                }
            });

            updateSelectionCount();
            toggleDeleteWarning();
        });
    }

    /**
     * Update Select All Checkbox State
     */
    function updateSelectAllCheckbox() {
        const totalImages = $('.wc-aic-image-checkbox').length;
        const selectedCount = selectedImages.length;

        const $selectAll = $('#wc-aic-select-all');

        if (selectedCount === 0) {
            $selectAll.prop('checked', false);
            $selectAll.prop('indeterminate', false);
        } else if (selectedCount === totalImages) {
            $selectAll.prop('checked', true);
            $selectAll.prop('indeterminate', false);
        } else {
            $selectAll.prop('checked', false);
            $selectAll.prop('indeterminate', true);
        }
    }

    /**
     * Update Selection Count
     */
    function updateSelectionCount() {
        const count = selectedImages.length;
        const text = count === 1
            ? '1 image selected'
            : count + ' images selected';

        $('#wc-aic-selected-count').text(text);
    }

    /**
     * Toggle Delete Warning
     */
    function toggleDeleteWarning() {
        const $warning = $('#wc-aic-delete-warning');

        if (selectedImages.length > 0) {
            $warning.slideDown();
        } else {
            $warning.slideUp();
        }
    }

    /**
     * Initialize Delete Button
     */
    function initDeleteButton() {
        $('#wc-aic-delete-selected').on('click', function() {
            if (selectedImages.length === 0) {
                alert(wcAicData.strings.selectImages);
                return;
            }

            if (!confirm(wcAicData.strings.deleteConfirm)) {
                return;
            }

            deleteSelectedImages();
        });
    }

    /**
     * Delete Selected Images
     */
    function deleteSelectedImages() {
        const $btn = $('#wc-aic-delete-selected');
        const originalText = $btn.html();

        // Disable button and show loading
        $btn.prop('disabled', true).html(
            '<span class="spinner is-active" style="float:none;margin:0 5px 0 0;"></span>' +
            wcAicData.strings.deleting
        );

        // AJAX request
        $.ajax({
            url: wcAicData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wc_aic_delete_images',
                nonce: wcAicData.nonce,
                image_ids: selectedImages
            },
            success: function(response) {
                if (response.success) {
                    // Remove deleted images from display
                    selectedImages.forEach(function(imageId) {
                        $('.wc-aic-image-item[data-image-id="' + imageId + '"]')
                            .fadeOut(300, function() {
                                $(this).remove();

                                // Check if no images left
                                if ($('.wc-aic-image-item').length === 0) {
                                    $('#wc-aic-results').fadeOut();
                                    $('#wc-aic-no-results').fadeIn();
                                }
                            });

                        // Remove from abandonedImages array
                        const index = abandonedImages.findIndex(img => img.id === imageId);
                        if (index > -1) {
                            abandonedImages.splice(index, 1);
                        }
                    });

                    // Update stats
                    const currentCount = parseInt($('#wc-aic-abandoned-count').text());
                    $('#wc-aic-abandoned-count').text(currentCount - response.data.deleted);

                    // Reset selection
                    selectedImages = [];
                    updateSelectionCount();
                    $('#wc-aic-select-all').prop('checked', false);
                    $('#wc-aic-delete-warning').slideUp();

                    // Show success message
                    showNotice('success', response.data.message);
                } else {
                    alert(response.data.message || wcAicData.strings.deleteError);
                }
            },
            error: function() {
                alert(wcAicData.strings.deleteError);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    }

    /**
     * Show Notice
     */
    function showNotice(type, message) {
        const $notice = $('<div>')
            .addClass('notice notice-' + type + ' is-dismissible')
            .html('<p>' + message + '</p>')
            .hide();

        $('.wc-aic-wrap h1').after($notice);
        $notice.slideDown();

        // Add dismiss button functionality
        $notice.append('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss</span></button>');

        $notice.find('.notice-dismiss').on('click', function() {
            $notice.slideUp(function() {
                $(this).remove();
            });
        });

        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $notice.slideUp(function() {
                $(this).remove();
            });
        }, 5000);
    }

})(jQuery);
