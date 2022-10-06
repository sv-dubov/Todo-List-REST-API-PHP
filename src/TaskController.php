<?php

class TaskController
{
    public function __construct(private TaskGateway $gateway, private int $user_id)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {
            if ($method == "GET") {
                echo json_encode($this->gateway->getAll($this->user_id));
            } elseif ($method == "POST") {
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }
                $id = $this->gateway->create($this->user_id, $data);
                $this->respondCreated($id);
            } else {
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {
            $task = $this->gateway->getById($this->user_id, $id);
            if ($task === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case "GET":
                    echo json_encode($task);
                    break;

                case "PATCH":
                    $data = (array) json_decode(file_get_contents("php://input"), true);
                    $errors = $this->getValidationErrors($data, false);
                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }
                    $rows = $this->gateway->update($this->user_id, $id, $data);
                    echo json_encode(["message" => "Task was updated", "rows" => $rows]);
                    break;

                case "DELETE":
                    $rows = $this->gateway->delete($this->user_id, $id);
                    echo json_encode(["message" => "Task was deleted", "rows" => $rows]);
                    break;

                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Task with ID $id was not found"]);
    }

    private function respondCreated(string $id): void
    {
        http_response_code(201);
        echo json_encode(["message" => "Task was created", "id" => $id]);
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["name"])) {
            $errors[] = "name is required";
        }

        if (mb_strlen($data["name"]) > 128) {
            $errors[] = "name must be not greater than 128 characters";
        }

        if (mb_strlen($data["description"]) > 255) {
            $errors[] = "description must be not greater than 255 characters";
        }

        if (!empty($data["is_urgent"])) {
            if (filter_var($data["is_urgent"], FILTER_VALIDATE_BOOLEAN) === false) {
                $errors[] = "is_urgent must be a boolean";
            }
        }

        if (!empty($data["is_personal"])) {
            if (filter_var($data["is_personal"], FILTER_VALIDATE_BOOLEAN) === false) {
                $errors[] = "is_personal must be a boolean";
            }
        }

        if (!empty($data["is_working"])) {
            if (filter_var($data["is_working"], FILTER_VALIDATE_BOOLEAN) === false) {
                $errors[] = "is_working must be a boolean";
            }
        }

        if (!empty($data["is_done"])) {
            if (filter_var($data["is_done"], FILTER_VALIDATE_BOOLEAN) === false) {
                $errors[] = "is_done must be a boolean";
            }
        }

        if (!preg_match('/^(\d{4}-([0]\d|[1][0-2])-([0-2]\d|[3][0-1])\s([0-1]\d|[2][0-3])\:[0-5]\d\:[0-5]\d)$/', $data["completed_at"])) {
            $errors[] = "completed_at must be datetime format";
        }

        return $errors;
    }
}
