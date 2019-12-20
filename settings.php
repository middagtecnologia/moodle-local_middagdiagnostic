<?php

/**
 * MIDDAG Diagnostic
 *
 * @package   local_middagdiagnostic
 * @copyright 2019 Middag {@link https://www.middag.com.br}
 * @license   Commercial
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('server', new admin_externalpage('middagdiagnostic', get_string('pluginname', 'local_middagdiagnostic'), new moodle_url('/local/middagdiagnostic')));
}
