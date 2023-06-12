<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = strip_tags($_POST['firstName']);
    $lastName = strip_tags($_POST['lastName']);
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $confirmPassword = strip_tags($_POST['confirmPassword']);

    $errors = [];

    // Валідація email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Неправильний формат email';
    }

    // Перевірка на співпадіння паролів
    if ($password !== $confirmPassword) {
        $errors[] = 'Паролі не співпадають';
    }

    if (empty($errors)) {
        // Створення масиву користувачів (замість бази даних)
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => '123456'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'janesmith@example.com', 'password' => 'abcdef']
        ];

        // Перевірка наявності емейла у списку користувачів
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $errors[] = 'Користувач з таким email вже існує';
                break;
            }
        }
    }

    if (empty($errors)) {
        // Запис даних у масив користувачів (замість бази даних)
        $newUser = [
            'id' => count($users) + 1,
            'name' => $firstName . ' ' . $lastName,
            'email' => $email,
            'password' => $password
        ];
        $users[] = $newUser;

        // Запис результатів у файл
        $logMessage = sprintf(
            "[%s] Успішна реєстрація. Email: %s, Ім'я: %s",
            date('Y-m-d H:i:s'),
            $email,
            $firstName . ' ' . $lastName
        );
        file_put_contents('log.txt', $logMessage . PHP_EOL, FILE_APPEND);

        echo 'success';
    } else {
        $errorMessage = implode('<br>', $errors);
        echo $errorMessage;
    }
} else {
    http_response_code(405); // Метод не дозволений
}
