<?php

/**
 * MIDDAG Diagnostic
 *
 * @package   local_middagdiagnostic
 * @copyright 2019 Middag {@link https://www.middag.com.br}
 * @license   Commercial
 */

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('index_form.php');

defined('MOODLE_INTERNAL') || die();

define('PLUGIN_NAME', 'middagdiagnostic');
define('PLUGIN_COMPONENT', 'local_middagdiagnostic');
define('PLUGIN_VERSION', '1.0');

require_login();

if (!is_siteadmin()) {
    redirect(new moodle_url('/'), get_string('onlyadmins', PLUGIN_COMPONENT), 5);
}

$context = context_system::instance();

$title = get_string('pluginname', PLUGIN_COMPONENT);
$heading = get_string('pluginname', PLUGIN_COMPONENT);
$url = new \moodle_url('/local/'.PLUGIN_NAME);

$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($heading);
$PAGE->set_url($url);

admin_externalpage_setup(PLUGIN_NAME);

$form = new local_middagdiagnostic_index_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    $middagdiagnostic = new \local_middagdiagnostic\middagdiagnostic();
    $middagdiagnostic->report();
}

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);
echo html_writer::div(get_string('about', 'local_middagdiagnostic'));
$form->display();
echo $OUTPUT->footer();
