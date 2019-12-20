<?php

/**
 * MIDDAG Diagnostic
 *
 * @package   local_middagdiagnostic
 * @copyright 2019 Middag {@link https://www.middag.com.br}
 * @license   Commercial
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/formslib.php');

class local_middagdiagnostic_index_form extends moodleform
{

    protected function definition()
    {
        $mform = $this->_form;

        $this->add_action_buttons(false, get_string('download', 'local_middagdiagnostic'));
    }
}
