<?php

namespace MageSuite\Opengraph\Helper;

class Mime extends \Magento\Framework\App\Helper\AbstractHelper
{
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

        $mimeType = $this->mimeTypes[$extension] ?? null;

        return $mimeType;
    }

    protected function getFileExtension($file)
    {
        if(strpos($file, '/') !== false){
            $fileParts = explode('/', $file);
            $file = end($fileParts);
        }

        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }
}
