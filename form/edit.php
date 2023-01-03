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

require_once("$CFG->libdir/formslib.php");

class submit extends moodleform {

    // Add elements to form.
    public function definition() {

        $mform = $this->_form;

        // Titulo
        $mform->addElement('text', 'titulo', get_string('title', 'local_silvestre')); 
        $mform->setType('titulo', PARAM_NOTAGS);
        $mform->setDefault('titulo', get_string('title_default', 'local_silvestre')); 

        // Descripción
        $mform->addElement('textarea', 'descripcion', get_string('description', 'local_silvestre')); 
        $mform->setDefault('descripcion', get_string('description_default', 'local_silvestre')); 

        // Foto
        $mform->addElement('filepicker', 'userfile', get_string('photo', 'local_silvestre'), null,
                   array('maxbytes' => 0 , 'accepted_types' => array('image')));

        // When ready, add your action buttons.
        $this->add_action_buttons();
    }

}