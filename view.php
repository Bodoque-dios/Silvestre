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

require_login();

global $DB;

$context = context_system::instance();
$url_view= '/local/silvestre/view.php';
$url = new moodle_url($url_view);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string('pluginname', 'local_silvestre'));

$records = array_values($DB->get_records('local_silvestre_posts'));


// separamos los posts en columnas
$left_column = array();
$right_column = array();

for ($x = 0; $x <= count($records) - 1 ; $x++) {

        if ($x % 2 == 0) {
            array_push($left_column,$records[$x]);
        }
        else {
            array_push($right_column,$records[$x]);
        }
}


// juntamos columnas en una sola variable
for ($x = 0; $x <= count($left_column) - 1 ; $x++) {

    $row = new stdClass();
    $algo = $left_column[$x]; // EXCELENTE NOMBRE // crea columna izquierda (Left)

    $url = moodle_url::make_pluginfile_url(
        $context->id,
        'local_silvestre',
        'fotos',
        null,
        '/',
        $algo->imagen,
        false                     // Do not force download of the file.
    );

    $id = $algo -> id;
    $idusuario = $algo-> idusuario;
    $likesCount = $DB->get_record_sql("SELECT COUNT(*) FROM {local_silvestre_likes} WHERE postid = $id");
    $likes = intval($likesCount-> {"count(*)"});
    $isLiked = $DB->record_exists_sql("SELECT * FROM {local_silvestre_likes} WHERE postid =  $id AND userid = $USER->id");

    $row -> idL =  $id;
    $row -> tituloL = $algo -> titulo;
    $row -> textoL = $algo-> texto;
    $row -> idusuarioL = $idusuario;
    $row -> nombreusuarioL = $algo->nombreusuario;
    $row -> fechaL = $algo-> fecha;
    $row -> imagenL = $url;
    $row -> LikesL = $likes;
    if ($isLiked) $row -> isLikedL = $isLiked;

    if ($x < count($right_column)){

        $algo = $right_column[$x]; // crea columna derecha (Right)

        $fs = get_file_storage();

        $url = moodle_url::make_pluginfile_url(
            $context->id,
            'local_silvestre',
            'fotos',
            null,
            '/',
            $algo->imagen,
            false                     // Do not force download of the file.
        );
 
        $id = $algo -> id;
        $idusuario = $algo-> idusuario;
        $likesCount = $DB->get_record_sql("SELECT COUNT(*) FROM {local_silvestre_likes} WHERE postid =  $id");
        $likes = intval($likesCount -> {"count(*)"});
        $isLiked = $DB->record_exists_sql("SELECT * FROM {local_silvestre_likes} WHERE postid =  $id AND userid =  $USER->id");
        
        $row -> idR = $algo-> id;
        $row -> tituloR = $algo-> titulo;
        $row -> textoR = $algo-> texto;
        $row -> idusuarioR = $idusuario;
        $row -> nombreusuarioR = $algo->nombreusuario;
        $row -> fechaR = $algo-> fecha;
        $row -> imagenR = $url;
        $row -> LikesR = $likes;
        if ($isLiked) $row -> isLikedR = $isLiked;
    }

    $data[$x] = $row;
}



echo $OUTPUT->header();

$templatecontext = (object)[
    'data_as_rows' => $data,
];

echo $OUTPUT->render_from_template('local_silvestre/view', $templatecontext);

echo $OUTPUT->footer();