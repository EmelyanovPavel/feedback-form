<?php
declare(strict_types=1);

namespace Controller;
use Model\FeedbackModel;
use InvalidArgumentException;

class FeedbackController
{
    private FeedbackModel $model;

    public function __construct(FeedbackModel $model)
    {
        $this->model = $model;
    }

    public function handleRequest(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
            try {
                $fullName = trim($_POST['full_name'] ?? '');
                $email    = trim($_POST['email'] ?? '');
                $message  = trim($_POST['message'] ?? '');
                $this->model->save($fullName, $email, $message);

                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Message saved']);
            } catch (InvalidArgumentException $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            } catch (\Throwable $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Server error']);
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
            try {
                $feedbacks = $this->model->getAll();
                echo json_encode($feedbacks, JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to load messages']);
            }
            exit;
        }

        // Если не API-запрос — рендерим страницу
        require __DIR__ . 'src/View/FeedbackView.php';
    }
}
?>