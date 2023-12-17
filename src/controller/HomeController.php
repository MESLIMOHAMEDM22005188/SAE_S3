<?php
include_once '../model/ImageModel.php';

class HomeController {
    private $model;

    public function __construct() {
        $this->model = new ImageModel();
    }

    public function onClick() {
        $this->model->toggleImage();
        echo json_encode(['newImageSrc' => $this->model->getCurrentImage()]);
    }
}

$controller = new HomeController();
$controller->onClick();
