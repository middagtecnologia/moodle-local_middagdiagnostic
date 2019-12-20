<?php

/**
 * MIDDAG Diagnostic
 *
 * @package   local_middagdiagnostic
 * @copyright 2019 Middag {@link https://www.middag.com.br}
 * @license   Commercial
 */

namespace local_middagdiagnostic;

use dataformat_json\writer;

defined('MOODLE_INTERNAL') || die();

class middagdiagnostic
{
    public $data;
    public $sitename;
    public $wwwroot;

    public function __construct()
    {
        global $CFG, $SITE;

        $this->sitename = $SITE->fullname;
        $this->wwwroot = $CFG->wwwroot;

        $this->data = $this->process();
    }

    public function report()
    {
        $time = time();
        $filename = "middagdiagnostic-$this->wwwroot-$time";

        $format = new writer();
        $format->set_filename($filename);
        $format->send_http_headers();
        $format->start_output();
        $format->write_record($this->data, 0);
        $format->close_output();
        exit;
    }

    private function process()
    {
        $data = [];

        $data['timestart'] = time();

        try {
            $data['sitename'] = $this->sitename;
            $data['wwwroot'] = $this->wwwroot;
            $data['totalcourse'] = $this->count_course();
            $data['totaluser'] = $this->count_user();
            $data['totaluseractive7days'] = $this->count_user_lastdays(7);
            $data['totaluseractive15days'] = $this->count_user_lastdays(15);
            $data['totaluseractive30days'] = $this->count_user_lastdays(30);
            $data['totaluseractive90days'] = $this->count_user_lastdays(90);
            $data['config_core'] = $this->get_config_core();
            $data['config_plugins'] = $this->get_config_plugins();
        } catch (\moodle_exception $e) {
            $data['error_code'] = $e->getCode();
            $data['error_message'] = $e->getMessage();
            $data['error_file'] = $e->getFile();
            $data['error_line'] = $e->getLine();
        }

        $data['timeend'] = time();

        return $data;
    }

    private function count_course()
    {
        global $DB;

        return $DB->count_records('course') - 1;
    }

    private function count_user()
    {
        global $DB;

        return $DB->count_records('user', []);
    }

    private function count_user_lastdays($days)
    {
        global $DB;

        $time = time() - $days * DAYSECS;

        return $DB->count_records_sql('SELECT COUNT(id) FROM {user} WHERE lastaccess > ?', [$time]);
    }

    private function get_config_core()
    {
        $config = get_config('core');

        $keys = [
            'dbpass',
            'cronremotepassword',
            'smtppass',
            'messageinbound_hostpass',
            'reposecretkey',
        ];

        foreach ($keys as $key) {
            unset($config->$key);
        }

        return $config;
    }

    private function get_config_plugins()
    {
        global $DB;

        $data = [];

        $records = $DB->get_records('config_plugins');

        foreach ($records as $record) {
            $data[$record->plugin][$record->name] = $record->value;
        }

        return $data;
    }

}
