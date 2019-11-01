<?php

namespace GeminiLabs\BetterThumbnailSizes;

final class Application
{
    const ID = 'better-thumbnail-sizes';
    const PREFIX = 'glbts_';
    const SIZE_PREFIX = 'sizes';

    public $file;
    public $isMustUse;
    public $languages;
    public $name;
    public $version;

    public function __construct()
    {
        $this->file = realpath(trailingslashit(dirname(__DIR__)).static::ID.'.php');
        $this->isMustUse = false !== strpos($this->file, WPMU_PLUGIN_DIR);
        $plugin = get_file_data($this->file, [
            'languages' => 'Domain Path',
            'name' => 'Plugin Name',
            'version' => 'Version',
        ], 'plugin');
        array_walk($plugin, function ($value, $key) {
            $this->$key = $value;
        });
    }

    /**
     * @return void
     */
    public function init()
    {
        $controller = new Controller($this);
        add_action('admin_init',                 [$controller, 'addSettings']);
        add_action('admin_notices',              [$controller, 'displayError']);
        add_filter('wp_image_editors',           [$controller, 'filterImageEditors']);
        add_filter('wp_get_attachment_metadata', [$controller, 'filterImageFiles']);
        add_action('plugins_loaded',             [$controller, 'registerTextDomain']);
    }

    /**
     * @param string $file
     * @param bool $realpath
     * @return string
     */
    public function path($file = '', $realpath = true)
    {
        $path = plugin_dir_path($this->file);
        if (!$realpath) {
            $path = trailingslashit(WP_PLUGIN_DIR).basename(dirname($this->file));
        }
        return trailingslashit($path).ltrim(trim($file), '/');
    }

    /**
     * @param string $view
     * @return void
     */
    public function render($view, array $data = [])
    {
        $file = $this->path('views/'.$view).'.php';
        if (file_exists($file)) {
            extract($data);
            include $file;
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public function url($path = '')
    {
        return esc_url(plugin_dir_url($this->file).ltrim(trim($path), '/'));
    }
}
