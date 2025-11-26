# WooCommerce Abandoned Image Cleanup

A WordPress plugin that helps you identify and remove images in your media library that are not attached to any WooCommerce products.

## Description

Over time, WooCommerce stores accumulate unused images from deleted products, test uploads, or images that were never assigned to products. This plugin scans your entire media library and identifies all images that are not being used by any WooCommerce product, making it easy to clean up and free up storage space.

## Features

- **Simple Scan Interface**: One-click scanning with a clear, easy-to-use admin interface
- **Comprehensive Detection**: Scans all product images including:
  - Featured product images
  - Product gallery images
  - Variable product variation images
  - Products in all statuses (published, draft, pending, private, trash)
- **Visual Results**: Displays abandoned images in a familiar media library grid layout
- **Easy Bulk Selection**: Select individual images or use "Select All" for bulk operations
- **Safe Deletion**: Multiple warnings and confirmation prompts before deletion
- **Detailed Statistics**: View total images, used images, and abandoned image counts
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## Requirements

- WordPress 5.8 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher

## Installation

### Option 1: Manual Installation

1. Download the plugin files
2. Upload the `woocommerce-abandoned-image-cleanup` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to **WooCommerce > Abandoned Images** to start using the plugin

### Option 2: WordPress Admin

1. Go to **Plugins > Add New**
2. Click **Upload Plugin**
3. Choose the plugin zip file
4. Click **Install Now**
5. Activate the plugin

## Usage

1. **Navigate to the Plugin**
   - Go to **WooCommerce > Abandoned Images** in your WordPress admin

2. **Start a Scan**
   - Click the large **SCAN** button
   - The plugin will analyze your media library and WooCommerce products
   - This may take a few moments depending on your library size

3. **Review Results**
   - View statistics showing total images, used images, and abandoned images
   - Browse the grid of abandoned images (if any are found)
   - Each image shows thumbnail, title, file size, and upload date

4. **Select Images to Delete**
   - Click individual images to select them
   - Or use the **Select All** checkbox to select all abandoned images
   - The selected count updates as you make selections

5. **Delete Images**
   - Click **Delete Selected** button
   - Confirm the deletion warning (reminder to backup)
   - Selected images will be permanently removed

## Important Notes

### ⚠️ Backup Warning

**ALWAYS create a backup of your media library before deleting images!**

Deleted images are removed permanently and cannot be recovered. The plugin shows multiple warnings, but it's your responsibility to ensure you have backups.

### What Images Are Considered "Abandoned"?

An image is considered abandoned if it meets ALL of these criteria:
- It exists in your WordPress media library
- It is NOT used as a featured image for any product
- It is NOT in any product gallery
- It is NOT used by any product variation

### What Images Are NOT Scanned?

This plugin only scans for WooCommerce product images. Images used elsewhere on your site (posts, pages, widgets, theme files, etc.) will still be flagged as abandoned if they're not attached to products.

## Screenshots

### Admin Interface
The main scanning interface with clear instructions and warnings.

### Scan Results
View detailed statistics and a grid of all abandoned images.

### Bulk Selection
Easy selection and deletion with multiple safety warnings.

## Frequently Asked Questions

**Q: Will this delete images used in blog posts or pages?**

A: The plugin only checks if images are used in WooCommerce products. If an image is used in a blog post but not in any product, it will be flagged as abandoned. Always review the list carefully before deleting.

**Q: What happens to deleted images?**

A: Images are permanently deleted (bypassing the trash). They cannot be recovered unless you have a backup.

**Q: Does this work with variable products?**

A: Yes! The plugin scans all product types including variable products and their variations.

**Q: Will this affect my product performance?**

A: No. The scan only reads data; it doesn't modify anything until you explicitly delete images.

**Q: How often should I run scans?**

A: Run scans periodically based on your needs - monthly or quarterly is typical for most stores.

## Support

For bug reports and feature requests, please use the [GitHub repository](https://github.com/dcArock/woocommerce-abandoned-image-cleanup/issues).

## Changelog

### 1.0.0
- Initial release
- Scan media library for abandoned images
- Display results in media library style grid
- Bulk selection and deletion
- Comprehensive safety warnings
- Statistics dashboard

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by [dcArock](https://github.com/dcArock)

---

**Remember: Always backup your media library before using this plugin!**
