<?php
/**
 * Regenerate missing WooCommerce product image thumbnails
 * Creates missing image sizes from full-size images
 */

define('WP_USE_THEMES', false);
require_once(__DIR__ . '/wp-load.php');

// Include WordPress image functions
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    die("WooCommerce is not active.\n");
}

echo "Regenerating missing product image thumbnails...\n\n";

// Get all product images
$attachments = get_posts(array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => -1,
    'post_status' => 'inherit'
));

$regenerated = 0;
$skipped = 0;
$errors = 0;

foreach ($attachments as $attachment) {
    $file = get_attached_file($attachment->ID);
    
    if (!$file || !file_exists($file)) {
        $skipped++;
        continue;
    }
    
    // Get current metadata
    $metadata = wp_get_attachment_metadata($attachment->ID);
    
    if (!$metadata) {
        $skipped++;
        continue;
    }
    
    // Check if thumbnails are missing
    $upload_dir = wp_upload_dir();
    $base_dir = $upload_dir['basedir'];
    $file_dir = dirname($file);
    $file_basename = basename($file);
    
    $missing_sizes = array();
    
    // Check all registered image sizes
    $image_sizes = get_intermediate_image_sizes();
    $image_sizes[] = 'full'; // Also check full size
    
    foreach ($image_sizes as $size) {
        if ($size === 'full') continue;
        
        $image = image_get_intermediate_size($attachment->ID, $size);
        
        if ($image) {
            $thumb_path = $base_dir . '/' . dirname($metadata['file']) . '/' . $image['file'];
            
            if (!file_exists($thumb_path)) {
                $missing_sizes[] = $size;
            }
        } else {
            // Size doesn't exist in metadata
            $missing_sizes[] = $size;
        }
    }
    
    if (!empty($missing_sizes)) {
        echo "Regenerating thumbnails for: " . basename($file) . "\n";
        echo "  Missing sizes: " . implode(', ', $missing_sizes) . "\n";
        
        // Regenerate metadata (this will create missing thumbnails)
        $new_metadata = wp_generate_attachment_metadata($attachment->ID, $file);
        
        if ($new_metadata && !is_wp_error($new_metadata)) {
            wp_update_attachment_metadata($attachment->ID, $new_metadata);
            $regenerated++;
            echo "  ✓ Successfully regenerated\n\n";
        } else {
            $errors++;
            echo "  ✗ Error regenerating\n\n";
        }
    } else {
        $skipped++;
    }
}

echo "\n";
echo "Summary:\n";
echo "  Regenerated: $regenerated images\n";
echo "  Skipped: $skipped images (already have thumbnails or missing source)\n";
echo "  Errors: $errors images\n";
echo "\nDone!\n";

