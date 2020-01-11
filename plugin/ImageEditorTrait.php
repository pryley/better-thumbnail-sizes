<?php

namespace GeminiLabs\BetterThumbnailSizes;

trait ImageEditorTrait
{
    /**
     * @param string $suffix
     * @param string $destPath
     * @param string $extension
     * @return string
     */
    public function generate_filename($suffix = null, $destPath = null, $extension = null)
    {
        $filename = parent::generate_filename($suffix, $destPath, $extension);
        if (static::supports_mime_type(wp_get_image_mime($filename))) {
            return $this->modifyFilePath($filename)
        }
        return $filename;
    }

    /**
     * @param string $filename
     * @param string $mimeType
     * @return array
     */
    protected function get_output_format($filename = null, $mimeType = null)
    {
        $output = parent::get_output_format($filename, $mimeType);
        if (!empty($output[2])) {
            $mimeType = $output[2];
        }
        if (!empty($output[0]) && static::supports_mime_type($mimeType)) {
            $output[0] = $this->modifyFilePath($output[0]);
        }
        return $output;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function modifyFilePath($filename)
    {
        $path = wp_get_upload_dir()['basedir'];
        $file = pathinfo($filename, PATHINFO_BASENAME);
        $subdir = str_replace($path, '', $filename);
        $subdir = str_replace($file, '', $subdir);
        $modifiedPath = sprintf('%s%s%s',
            untrailingslashit($path),
            trailingslashit($subdir),
            trailingslashit(Application::SIZE_PREFIX)
        );
        return wp_mkdir_p($path)
            ? $modifiedPath.$file
            : $filename;
    }
}
