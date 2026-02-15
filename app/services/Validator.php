<?php

namespace app\services;

use app\models\User;

class Validator
{

    public static function normalizeTelephone($tel)
    {
        return preg_replace('/\s+/', '', trim((string)$tel));
    }

    public static function validateRegister(array $input, User $repo = null)
    {
        $errors = [
            'username' => '',
            'password' => '',
            'confirmPassword' => ''
        ];

        $values = [
            'username' => trim((string)($input['username'] ?? '')),
        ];

        $password = (string)($input['password'] ?? '');
        $confirm  = (string)($input['confirmPassword'] ?? '');

        if (mb_strlen($values['username']) < 2) $errors['username'] = "Le nom d'utilisateur doit contenir au moins 2 caractères.";

        if (strlen($password) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une lettre majuscule.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une lettre minuscule.";
        } elseif (!preg_match('/\d/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins un chiffre.";
        } elseif (!preg_match('/[\W_]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        if (strlen($confirm) < 8) {
            $errors['confirmPassword'] = "Veuillez confirmer le mot de passe (min 8 caractères).";
        } elseif ($password !== $confirm) {
            $errors['confirmPassword'] = "Les mots de passe ne correspondent pas.";
            if ($errors['password'] === '') {
                $errors['password'] = "Vérifiez le mot de passe et sa confirmation.";
            }
        }

        // Store password in values for processing (will be hashed later)
        if ($errors['password'] === '' && $errors['confirmPassword'] === '') {
            $values['password'] = $password;
        }

        $ok = true;
        foreach ($errors as $m) {
            if ($m !== '') {
                $ok = false;
                break;
            }
        }

        return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
    }

    public static function validateLogin(array $input, User $repo = null)
    {
        $errors = [
            'username' => '',
            'password' => ''
        ];

        $values = [
            'username' => trim((string)($input['username'] ?? '')),
        ];

        $password = (string)($input['password'] ?? '');

        if (mb_strlen($values['username']) < 2) $errors['username'] = "Le nom d'utilisateur doit contenir au moins 2 caractères.";

        if (strlen($password) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une lettre majuscule.";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une lettre minuscule.";
        } elseif (!preg_match('/\d/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins un chiffre.";
        } elseif (!preg_match('/[\W_]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        // Store password in values for processing (will be hashed later)
        if ($errors['password'] === '') {
            $values['password'] = $password;
        }

        $ok = true;
        foreach ($errors as $m) {
            if ($m !== '') {
                $ok = false;
                break;
            }
        }

        return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
    }
}
