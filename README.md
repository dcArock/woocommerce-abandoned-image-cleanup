# WooCommerce Abandoned Image Cleanup

A comprehensive WordPress plugin that scans your entire website to identify and safely remove truly abandoned images - checking products, content, custom fields, theme settings, and database meta to ensure only genuinely unused images are flagged for deletion.

## Description

Over time, WooCommerce stores accumulate unused images from deleted products, test uploads, or images that were never assigned to products. This plugin performs a **comprehensive scan** of your entire website - not just products, but also pages, posts, product descriptions, custom fields, theme settings, and all database meta fields - to identify truly abandoned images that are not being used anywhere on your site.

## Features

- **Simple Scan Interface**: One-click scanning with a clear, easy-to-use admin interface
- **Comprehensive Site-Wide Scanning**: Thoroughly checks your entire website including:
  - **Product Images**: Featured images, galleries, and variation images
  - **Content**: All posts, pages, and product descriptions (HTML content)
  - **Custom Fields**: Product meta fields and custom post meta
  - **Database**: All postmeta entries for image references
  - **Theme Settings**: Logos, backgrounds, banners from theme customizer
  - **Options**: Site-wide settings that may reference images
  - **All Image Sizes**: Checks thumbnails, medium, large, and custom sizes
- **Smart Detection**: Only flags truly abandoned images, protecting logos and content images
- **Visual Results**: Displays abandoned images in a familiar media library grid layout
- **Easy Bulk Selection**: Select individual images or use "Select All" for bulk operations
- **Safe Deletion**: Multiple warnings and confirmation prompts before deletion
- **Detailed Statistics**: View total images, images in use, and abandoned image counts
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
- It is NOT referenced in any post or page content
- It is NOT used in any product description
- It is NOT stored in any custom field or meta data
- It is NOT referenced in theme settings (logos, backgrounds, etc.)
- It is NOT found in the options table

### How Comprehensive is the Scan?

The plugin performs a **deep scan** of your entire WordPress database:
- ✅ Scans all product images (featured, galleries, variations)
- ✅ Searches HTML content of all posts, pages, and products
- ✅ Checks all postmeta fields for image IDs and URLs
- ✅ Examines theme customizer settings
- ✅ Searches options table for image references
- ✅ Checks all image sizes (thumbnail, medium, large, custom)
- ✅ Handles both image IDs and URLs
- ✅ Processes serialized data in meta fields

This ensures that logos, featured images in blog posts, images in product descriptions, and any other actively used images are **never** flagged as abandoned.

## Screenshots

### Admin Interface
The main scanning interface with clear instructions and warnings.

### Scan Results
View detailed statistics and a grid of all abandoned images.

### Bulk Selection
Easy selection and deletion with multiple safety warnings.

## Frequently Asked Questions

**Q: Will this delete images used in blog posts or pages?**

A: No! Version 1.2.0 and above performs a comprehensive scan of your entire site. Images used in posts, pages, product descriptions, or anywhere else on your site are protected and will NOT be flagged as abandoned.

**Q: What about my logo and theme images?**

A: These are safe! The plugin scans theme customizer settings, options table, and all meta fields to ensure logos, backgrounds, and other theme images are never flagged as abandoned.

**Q: What happens to deleted images?**

A: Images are permanently deleted (bypassing the trash). They cannot be recovered unless you have a backup.

**Q: Does this work with variable products?**

A: Yes! The plugin scans all product types including variable products and their variations.

**Q: Will this affect my site performance during scan?**

A: The scan may take a few moments for large sites as it performs a comprehensive database search. However, it only reads data and doesn't modify anything until you explicitly delete images. The scan is performed via AJAX so it won't interrupt your work.

**Q: Does it check product descriptions?**

A: Yes! The plugin checks all post content including product descriptions. If an image is used in a product's short or long description, it will be marked as "in use."

**Q: What about images in custom fields?**

A: Absolutely! The plugin scans all postmeta and custom fields, including serialized data, to find image references.

**Q: How often should I run scans?**

A: Run scans periodically based on your needs - monthly or quarterly is typical for most stores.

## Support

For bug reports and feature requests, please use the [GitHub repository](https://github.com/dcArock/woocommerce-abandoned-image-cleanup/issues).

## Changelog

### 1.2.0
- **Major Enhancement**: Comprehensive site-wide scanning
- Scan all posts, pages, and product content for image references
- Check product descriptions and custom fields
- Scan all postmeta entries for image IDs and URLs
- Check theme customizer settings (logos, backgrounds, etc.)
- Search options table for image references
- Handle all image sizes (thumbnail, medium, large, custom)
- Process serialized data in meta fields
- Smart detection to protect logos and content images
- Updated UI to reflect "Images in Use" instead of "Product Images"
- Improved accuracy - only truly abandoned images are flagged

### 1.1.0
- Add WooCommerce HPOS (High-Performance Order Storage) compatibility
- Declare compatibility with custom order tables feature

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
