<?php
/*
Plugin Name:        Soiled
Plugin URI:         https://roots.io/plugins/soil/
Description:        A collection of modules to apply theme-agnostic front-end modifications to WordPress.
Version:            3.7.0
Author:             Roots
Author URI:         https://roots.io/

License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

namespace Roots\Soiled;

class Options {
  protected static $modules = [];
  protected $options = [];

  public static function init($module, $options = []) {
    if (!isset(self::$modules[$module])) {
      self::$modules[$module] = new static((array) $options);
    }
    return self::$modules[$module];
  }

  public static function getByFile($file) {
    if (file_exists($file) || file_exists(__DIR__ . '/modules/' . $file)) {
      return self::get('soiled-' . basename($file, '.php'));
    }
    return [];
  }

  public static function get($module) {
    if (isset(self::$modules[$module])) {
      return self::$modules[$module]->options;
    }
    if (substr($module, 0, 5) !== 'soiled-') {
      return self::get('soiled-' . $module);
    }
    return [];
  }

  protected function __construct($options) {
    $this->set($options);
  }

  public function set($options) {
    $this->options = $options;
  }
}

require_once __DIR__ . '/lib/utils.php';

function load_modules() {
  global $_wp_theme_features;
  foreach (glob(__DIR__ . '/modules/*.php') as $file) {
    $feature = 'soiled-' . basename($file, '.php');
    if (isset($_wp_theme_features[$feature])) {
      Options::init($feature, $_wp_theme_features[$feature]);
      require_once $file;
    }
  }
}
add_action('after_setup_theme', __NAMESPACE__ . '\\load_modules', 100);
