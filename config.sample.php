<?php
/**
 * Optional. Copy to config.php to override defaults. Everything else is set
 * from Admin → Settings and stored in the database.
 */
return [
    // Where the SQLite database lives. Moving it outside the web root is
    // the safest choice when your host allows it, e.g. '/home/user/postbase.sqlite'.
    'db_path' => __DIR__ . '/data/postbase.sqlite',

    // When set, the first-run setup screen asks for this value before it
    // creates the first admin account. Recommended on standalone installs.
    'setup_key' => '',

    // Upload limits for images. Larger photos are resized to max_image_px wide (needs GD).
    'max_upload_mb' => 5,
    'max_image_px'  => 1600,
];
