<?php
/**
 * This template will assist you with setting up drush aliases for
 * ACSF sites. Follow these steps to set up your drush aliases:
 *
 * 1. Fill in your site id below. This can be found in the drush
 *    aliases provided by Acquia Cloud.
 * 2. Fill in the list of sites below. Each site should correspond
 *    to the machine name of a site in the ACSF web interface.
 * 3. Fill in your prod and dev web server ids. These can be
 *    provided by your account manager or certain online tools.
 * 4. Rename this entire file to [PROJECT-NAME].aliases.drushrc.php
 */

// Acquia Cloud Site Factory id.
$site_id = '[PROJECT-NAME]';

/**
 * List of sites. For each site, one record should be added.
 * Optionally, you can also list vanity domains for each site.
 *
 * @code
 * $sites = array(
 *   'demo' => array(
 *     'dev' => 'dev.demo.example.com',
 *     'test' => 'test.demo.example.com',
 *     'prod' => 'demo.example.com',
 *   ),
 * );
 * @endcode
 */
$sites = array(
  'demo',
);

// Configure the server used for the production environment.
$prod_web = 'web-###';

// Configure the server used for the dev and test environments.
$dev_web = 'staging-###';

// =======================END OF CONFIGURATION==============================.
if ($site_id !== '[PROJECT-NAME]') {
  // Acquia Cloud Site Factory environment settings.
  $envs = array(
    'prod' => array(
      'remote-user' => $site_id . '.01live',
      'root' => '/var/www/html/' . $site_id . '.01live/docroot',
      'remote-host' => $prod_web . '.enterprise-g1.hosting.acquia.com',
    ),
    'test' => array(
      'remote-user' => $site_id . '.01test',
      'root' => '/var/www/html/' . $site_id . '.01test/docroot',
      'remote-host' => $dev_web . '.enterprise-g1.hosting.acquia.com',
    ),
    'dev' => array(
      'remote-user' => $site_id . '.01dev',
      'root' => '/var/www/html/' . $site_id . '.01dev/docroot',
      'remote-host' => $dev_web . '.enterprise-g1.hosting.acquia.com',
    ),
  );
  // These defaults connect to the Acquia Cloud Site Factory.
  $acsf_defaults = array(
    'ssh-options' => '-p 22',
    'path-aliases' => array(
      '%dump-dir' => '/mnt/tmp/'
    )
  );
  // Create the aliases using the defaults and the list of sites.
  foreach ($sites as $site_name => $site_domains) {
    if (!is_array($site_domains)) {
      $site_name = $site_domains;
    }
    foreach ($envs as $env_name => $env_info) {
      $uri = $site_name . '.' . $env_name . '-' . $site_id . '.acsitefactory.com';
      if ($env_name == 'prod') {
        $uri = $site_name . '.' . $site_id . '.acsitefactory.com';
      }
      if (is_array($site_domains) && isset($site_domains[$env_name])) {
        $uri = $site_domains[$env_name];
      }
      $aliases[$site_name . '.' . $env_name] = array_merge(
        $acsf_defaults,
        $env_info,
        array(
          'uri' => $uri,
        )
      );
    }
  }
}
