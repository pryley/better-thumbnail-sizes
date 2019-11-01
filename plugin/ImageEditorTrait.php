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
        return $this->modifyFilePath($filename);
    }

    /**
     * @param string $filename
     * @param string $mimeType
     * @return array
     */
    protected function get_output_format($filename = null, $mimeType = null)
    {
        $output = parent::get_output_format($filename, $mimeType);
        if (!empty($output[0])) {
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
        $path = trailingslashit($path).trailingslashit(Application::SIZE_PREFIX);
        $file = pathinfo($filename, PATHINFO_BASENAME);
        return wp_mkdir_p($path)
            ? $path.$file
            : $filename;
    }
}
