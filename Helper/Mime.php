<?php

namespace MageSuite\Opengraph\Helper;

class Mime extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected \Magento\Framework\Filesystem\Io\File $file;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem\Io\File $file,
    ) {
        parent::__construct($context);
        $this->file = $file;
    }

    protected $mimeTypes = [
        'png'  => 'image/png',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
    ];

    public function getMimeType($file)
    {
        $extension = $this->getFileExtension($file);
        return $this->mimeTypes[$extension] ?? null;
    }

    protected function getFileExtension($file)
    {
        if (strpos($file, '/') !== false) {
            $fileParts = explode('/', $file);
            $file = end($fileParts);
        }
        return strtolower($this->file->getPathInfo($file)['extension']);
    }
}
