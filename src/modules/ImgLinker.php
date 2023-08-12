<?php
class ImgLinker
{
    private $imagePath;
    private $imageUrl;

    public function __construct($imageName)
    {
        $this->setImage($imageName);
        $this->updateImageUrl();
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function setImage($imageName)
    {
        $this->imagePath = "odmupdates/src/libs/" . $imageName;
    }

    private function updateImageUrl()
    {
        $this->imageUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . '/' . $this->imagePath;
    }

    public function getBaseUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . '/';
    }

    public function isSecureConnection()
    {
        return $_SERVER['REQUEST_SCHEME'] === 'https';
    }
}
