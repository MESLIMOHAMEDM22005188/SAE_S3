<?php
class ImageModel {
    private $homeImage = 'path/to/home.png';
    private $returnImage = 'path/to/retour.png';
    private $currentImage;

    public function __construct() {
        $this->currentImage = $this->homeImage;
    }

    public function toggleImage() {
        $this->currentImage = ($this->currentImage == $this->homeImage) ? $this->returnImage : $this->homeImage;
    }

    public function getCurrentImage() {
        return $this->currentImage;
    }
}
