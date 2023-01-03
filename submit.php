<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_silvestre
 * @copyright 2022, Matias Olea <matolea@alumnos.uai.cl>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/local/silvestre/classes/form/edit.php');

require_login();

global $DB, $USER;

$context = context_system::instance();
$url_view= '/local/silvestre/submit.php';
$url = new moodle_url($url_view);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title('Submit');

$mform = new submit();

if ($mform->is_cancelled()) {
    // Go back to view.php page
    redirect($CFG->wwwroot . '/local/silvestre/view.php', get_string('cancelled_form', 'local_silvestre'));

} else if ($fromform = $mform->get_data()) {
    
    $time = new DateTime("now", core_date::get_user_timezone_object());
    $name = $mform->get_new_filename('userfile');

    $storedfile = $mform->save_stored_file('userfile', $context->id, 'local_silvestre', 'fotos', 0 ,'/', null, true);

    if(!$storedfile){
        die();
    }


    $nombre = explode(' ', $USER->firstname)[0];
    $apellido = explode(' ', $USER->lastname)[0];


    $recordtoinsert = new stdClass();

    $recordtoinsert -> id = 0;
    $recordtoinsert -> titulo = $fromform->titulo;
    $recordtoinsert -> texto = $fromform->descripcion;
    $recordtoinsert -> idusuario = $USER->id;
    $recordtoinsert -> fecha = $time->format('Y-m-d H:i:s');
    $recordtoinsert -> imagen =  $name;
    $recordtoinsert -> nombreusuario =  "$nombre $apellido";


    $DB->insert_record("local_silvestre_posts", $recordtoinsert);
    
    redirect($CFG->wwwroot . '/local/silvestre/view.php',get_string('upload_success', 'local_silvestre'));

}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();

