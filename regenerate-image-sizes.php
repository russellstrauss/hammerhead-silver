<?php
/**
 * Regenerate thumbnail sizes for a specific image
 */

define('WP_USE_THEMES', false);
require_once(__DIR__ . '/wp-load.php');

// Image path from URL
$image_path = '2018/07/WHITE-BUFFALO-POWER-RING.jpg';

echo "Regenerating thumbnail sizes for: $image_path\n\n";

// Find attachment by file path
global $wpdb;

$upload_dir = wp_upload_dir();
$base_dir = $upload_dir['basedir'];
$full_path = $base_dir . '/' . $image_path;

if ( ! file_exists( $full_path ) ) {
    echo "ERROR: Image file not found at: $full_path\n";
    exit(1);
}

echo "Found image file: $full_path\n";

// Find attachment ID by file path
$attachment = $wpdb->get_row( $wpdb->prepare(
    "SELECT ID FROM {$wpdb->posts} p
     INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
     WHERE pm.meta_key = '_wp_attached_file'
     AND pm.meta_value = %s
     AND p.post_type = 'attachment'
     LIMIT 1",
    $image_path
) );

if ( ! $attachment ) {
    echo "ERROR: Could not find attachment in database for: $image_path\n";
    echo "Trying alternative search by filename...\n";
    
    // Try searching by filename only
    $filename = basename( $image_path );
    $attachment = $wpdb->get_row( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
         WHERE pm.meta_key = '_wp_attached_file'
         AND pm.meta_value LIKE %s
         AND p.post_type = 'attachment'
         LIMIT 1",
        '%' . $wpdb->esc_like( $filename )
    ) );
    
    if ( ! $attachment ) {
        echo "ERROR: Could not find attachment in database\n";
        exit(1);
    }
}

$attachment_id = $attachment->ID;
echo "Found attachment ID: $attachment_id\n\n";

// Get current metadata
$metadata = wp_get_attachment_metadata( $attachment_id );
if ( ! $metadata ) {
    echo "No existing metadata found. Creating new metadata...\n";
    $metadata = array();
}

echo "Current metadata:\n";
echo "  File: " . ( isset( $metadata['file'] ) ? $metadata['file'] : 'N/A' ) . "\n";
echo "  Width: " . ( isset( $metadata['width'] ) ? $metadata['width'] : 'N/A' ) . "\n";
echo "  Height: " . ( isset( $metadata['height'] ) ? $metadata['height'] : 'N/A' ) . "\n";
echo "  Existing sizes: " . ( isset( $metadata['sizes'] ) ? count( $metadata['sizes'] ) : 0 ) . "\n\n";

// Regenerate thumbnails
echo "Regenerating thumbnails...\n";

// Include the image editor
require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Regenerate all image sizes
$regenerated = wp_generate_attachment_metadata( $attachment_id, $full_path );

if ( $regenerated && ! is_wp_error( $regenerated ) ) {
    // Update metadata
    wp_update_attachment_metadata( $attachment_id, $regenerated );
    
    echo "SUCCESS: Thumbnails regenerated!\n\n";
    echo "New metadata:\n";
    echo "  File: " . ( isset( $regenerated['file'] ) ? $regenerated['file'] : 'N/A' ) . "\n";
    echo "  Width: " . ( isset( $regenerated['width'] ) ? $regenerated['width'] : 'N/A' ) . "\n";
    echo "  Height: " . ( isset( $regenerated['height'] ) ? $regenerated['height'] : 'N/A' ) . "\n";
    
    if ( isset( $regenerated['sizes'] ) && is_array( $regenerated['sizes'] ) ) {
        echo "  Generated sizes:\n";
        foreach ( $regenerated['sizes'] as $size_name => $size_data ) {
            $size_file = dirname( $full_path ) . '/' . $size_data['file'];
            $exists = file_exists( $size_file ) ? '✓' : '✗';
            echo "    $exists $size_name: {$size_data['width']}x{$size_data['height']} - {$size_data['file']}\n";
        }
    }
} else {
    echo "ERROR: Failed to regenerate thumbnails\n";
    if ( is_wp_error( $regenerated ) ) {
        echo "  Error: " . $regenerated->get_error_message() . "\n";
    }
    exit(1);
}

echo "\nDone!\n";

