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

namespace aiprovider_ai_manager;

use core\http_client;
use core_ai\process_base;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class process text generation.
 *
 * @package    aiprovider_ai_manager
 * @copyright  2025 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_processor extends process_base {

    #[\Override]
    protected function query_ai_api(): array {
        $aimanager = new \local_ai_manager\manager($this->get_purpose_from_action());
        $contextid = $this->action->get_configuration('contextid');
        $component = 'core_ai';
        $promptresponse = $aimanager->perform_request($this->action->get_configuration('prompttext'), $component, $contextid);

        if ($promptresponse->get_code() === 200) {
            return [
                    'success' => true,
                    'generatedcontent' => $promptresponse->get_content(),
                    'finishreason' => 'stop',
                    'prompttokens' => $promptresponse->get_usage()->customvalue1,
                    'completiontokens' => $promptresponse->get_usage()->customvalue2,
                    'model' => $promptresponse->get_modelinfo(),
            ];
        } else {
            $errormessage = $promptresponse->get_errormessage();
            if (debugging(DEBUG_DEVELOPER)) {
                $errormessage .= $promptresponse->get_errormessage() . ' ' . $promptresponse->get_debuginfo();
            }
            return [
                    'success' => false,
                    'errorcode' => $promptresponse->get_code(),
                    'errormessage' => $errormessage,
            ];
        }
    }

    protected abstract function get_purpose_from_action(): string;

}
