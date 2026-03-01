<?php

function validateRegistration(array $data): array
{
    if (empty($data['email'])) {
        return ['status' => false, 'message' => 'Email обязателен'];
    }

    if (strlen($data['email']) <= 5 || strpos($data['email'], '@') === false) {
        return ['status' => false, 'message' => 'Некорректный email'];
    }

    if (empty($data['password'])) {
        return ['status' => false, 'message' => 'Пароль обязателен'];
    }

    if (strlen($data['password']) <= 8) {
        return ['status' => false, 'message' => 'Пароль слишком короткий'];
    }

    if (!preg_match('/[A-Za-z]/', $data['password']) ||
        !preg_match('/[0-9]/', $data['password'])) {
        return ['status' => false, 'message' => 'Пароль должен содержать буквы и цифры'];
    }

    if (empty($data['repit_password']) ||
        $data['password'] !== $data['repit_password']) {
        return ['status' => false, 'message' => 'Пароли не совпадают'];
    }

    if (empty($data['name']) ||
        !preg_match('/^[a-zA-Zа-яА-ЯёЁ]+$/u', $data['name'])) {
        return ['status' => false, 'message' => 'Некорректное имя'];
    }

    if (!empty($data['phone_number']) &&
        strlen((string)$data['phone_number']) <= 5) {
        return ['status' => false, 'message' => 'Некорректный телефон'];
    }

    $allowed = ['site', 'city', 'tv', 'others'];
    if (!empty($data['came_from']) &&
        !in_array($data['came_from'], $allowed)) {
        return ['status' => false, 'message' => 'Некорректный источник'];
    }

    if (empty($data['date_birth'])) {
        return ['status' => false, 'message' => 'Дата рождения обязательна'];
    }

    $birth = strtotime($data['date_birth']);
    $age = (int)((time() - $birth) / (365.25 * 24 * 60 * 60));

    if ($age <= 15 || $age >= 67) {
        return ['status' => false, 'message' => 'Недопустимый возраст'];
    }

    return ['status' => true, 'message' => 'OK'];
}