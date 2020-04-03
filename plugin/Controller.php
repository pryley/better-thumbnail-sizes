<?php

namespace GeminiLabs\BetterThumbnailSizes;

class Controller
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $optionName;

    /**
     * @var bool
     */
    protected $optionValue;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->optionName = Application::PREFIX.Application::SIZE_PREFIX;
        $this->optionValue = $app->isMustUse
            ? true
            : wp_validate_boolean(get_option($this->optionName));
    }

    /**
     * @return void
     * @action admin_init
     */
    public function addSettings()
    {
        if ($this->app->isMustUse) {
            return;
        }
        add_settings_section($this->optionName, '', [$this, 'renderSettings'], 'media');
        register_setting('media', $this->optionName, [
            'default' => false,
            'type' => 'boolean',
        ]);
    }

    /**
     * @return void
     * @action admin_notices
     */
    public function displayError()
    {
        $uploadDirPath = wp_upload_dir()['path'];
        if (file_exists($uploadDirPath) && !is_writable($uploadDirPath)) {
            $this->app->render('error', [
                'title' => sprintf(__('%s has detected a problem.', 'better-thumbnail-sizes'), $this->app->name),
                'text' => sprintf(__('The <code>%s</code> directory is not writable.', 'better-thumbnail-sizes'), $uploadDirPath),
            ]);
        }
    }

    /**
     * @param array $editors
     * @return array
     * @filter wp_image_editors
     */
    public function filterImageEditors($editors)
    {
        return $this->optionValue
            ? [ImageEditorImagick::class, ImageEditorGD::class]
            : $editors;
    }

    /**
     * @param array|bool $meta
     * @return array
     * @filter wp_get_attachment_metadata
     */
    public function filterImageFiles($meta)
    {
        if ($this->optionValue && isset($meta['sizes'])) {
            $prefix = trailingslashit(Application::SIZE_PREFIX);
            $sizes = $meta['sizes'];
            foreach ($sizes as $size => $data) {
                if (!$this->isMimeTypeSupported($data)) {
                    continue;
                }
                $file = str_replace($prefix, '', $data['file']);
                $sizes[$size]['file'] = $prefix.$file;
            }
            $meta['sizes'] = $sizes;
        }
        return $meta;
    }

    /**
     * @return void
     * @action plugins_loaded
     */
    public function registerTextDomain()
    {
        load_plugin_textdomain(Application::ID, false,
            trailingslashit(plugin_basename($this->app->path()).'/'.$this->app->languages)
        );
    }

    /**
     * @return void
     * @callback add_settings_section
     */
    public function renderSettings()
    {
        $this->app->render('settings', [
            'checked_attribute' => checked(1, $this->optionValue, false),
            'name' => $this->optionName,
        ]);
    }

    /**
     * @param array $size
     * @return bool
     */
    public function isMimeTypeSupported($size)
    {
        return isset($size['file'])
            && isset($size['mime-type'])
            && wp_image_editor_supports(['mime_type' => $size['mime-type']]);
    }
}
