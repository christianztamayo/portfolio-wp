<?php

namespace Roots\Soiled\GoogleAnalytics;

/**
 * Google Analytics snippet from HTML5 Boilerplate
 *
 * Cookie domain is 'auto' configured. See: http://goo.gl/VUCHKM
 * You can enable/disable this feature in functions.php (or lib/setup.php if you're using Sage):
 * add_theme_support('soiled-google-analytics', 'UA-XXXXX-Y', 'wp_footer');
 */
function load_script() {
  $gaID = options('gaID');
  if (!$gaID) { return; }
  $loadGA = (!defined('WP_ENV') || \WP_ENV === 'production') && !current_user_can('manage_options');
  $loadGA = apply_filters('soiled/loadGA', $loadGA);
  ?>
  <script>
    <?php if ($loadGA) : ?>
      window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
    <?php else : ?>
      !function(a,b,c,d){a.ga=function(){a.ga.q.push(arguments),b.log&&b.log(c+d.call(arguments))},a.ga.q=[],a.ga.l=+new Date}(window,console,"Google Analytics: ",[].slice);
    <?php endif; ?>
    ga('create','<?= $gaID; ?>','auto');ga('send','pageview')
  </script>
  <?php if ($loadGA) : ?>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
  <?php endif; ?>
<?php
}

function options($option = null) {
  static $options;
  if (!isset($options)) {
    $options = \Roots\Soiled\Options::getByFile(__FILE__) + ['', 'wp_footer'];
    $options['gaID'] = &$options[0];
    $options['hook'] = &$options[1];
  }
  return is_null($option) ? $options : $options[$option];
}

$hook = options('hook');

add_action($hook, __NAMESPACE__ . '\\load_script', 20);
