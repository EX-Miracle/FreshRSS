#!/usr/bin/env php
<?php
require(__DIR__ . '/_cli.php');

performRequirementCheck(FreshRSS_Context::$system_conf->db['type'] ?? '');

$params = array(
	'user:',
);

$options = getopt('', $params);

if (!validateOptions($argv, $params) || empty($options['user']) || !is_string($options['user'])) {
	fail('Usage: ' . basename(__FILE__) . " --user username > /path/to/file.opml.xml");
}

$username = cliInitUser($options['user']);

fwrite(STDERR, 'FreshRSS exporting OPML for user “' . $username . "”…\n");

$export_service = new FreshRSS_Export_Service($username);
list($filename, $content) = $export_service->generateOpml();
echo $content;

invalidateHttpCache($username);

done();
