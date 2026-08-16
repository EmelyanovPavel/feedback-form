<?php
declare(strict_types=1);

require __DIR__ . '/config/database.php';
require __DIR__ . '/src/Model/FeedbackModel.php';
require __DIR__ . '/src/Controller/FeedbackController.php';

use Config\Database;
use Model\FeedbackModel;
use Controller\FeedbackController;

$db = new Database();
$model = new FeedbackModel($db);
$controller = new FeedbackController($model);
$controller->handleRequest(); 
?>