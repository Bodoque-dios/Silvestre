<?php
/**
 * Serve the files from the myplugin file areas.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
*/



require_once(__DIR__.'/../../config.php');
$context = context_system::instance();

function local_silvestre_pluginfile(
    $course,
    $cm,
    $context,
    string $filearea,
    array $args,
    bool $forcedownload,
    array $options = []
): bool {

    $fileName = array_shift($args);

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_silvestre', 'fotos', '0', '/', $fileName);
    
    if (!$file) {

		send_file_not_found();
        return false;

    }
    
    send_stored_file($file, null, 0, $forcedownload, $options);
}