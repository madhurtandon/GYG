<?php
/**
 * GYG product availabilities
 *
 * @package        App
 * @author         Madhur Tandon
 */

define('DIR_PREFIX', dirname(__FILE__));

// Autoload the Libraries that are used or supported by GYG
require_once(DIR_PREFIX . '/vendor/autoload.php');

require_once DIR_PREFIX . DIRECTORY_SEPARATOR . 'CLI.php';

// Validate the CLI arguments
(new \GYG\Library\Validator())->IsValidCLIArguments($argv);