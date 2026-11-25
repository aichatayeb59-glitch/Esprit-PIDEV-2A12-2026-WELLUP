<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel($pdo);

function currentUserOrLogout(UserModel $userModel): array {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $user = $userModel->findById((int)$_SESSION['user_id']);
    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    return $user;
}

$currentUser = currentUserOrLogout($userModel);
$flashMessage = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');

        if (empty($nom) || empty($prenom) || empty($username) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $flashMessage = "Veuillez remplir tous les champs avec un email valide.";
            $flashType = "error";
        } elseif ($userModel->updateProfile($currentUser['id'], $nom, $prenom, $username, $email)) {
            $currentUser = $userModel->findById($currentUser['id']);
            $flashMessage = "Profil mis à jour avec succès.";
            $flashType = "success";
        } else {
            $flashMessage = "Cet email ou nom d'utilisateur est déjà utilisé.";
            $flashType = "error";
        }
    }

    if ($_POST['action'] === 'change_password') {
        $currentPwd = $_POST['current_password'] ?? '';
        $newPwd     = $_POST['new_password'] ?? '';
        $confirmPwd = $_POST['confirm_password'] ?? '';

        if (strlen($newPwd) < 8) {
            $flashMessage = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
            $flashType = "error";
        } elseif ($newPwd !== $confirmPwd) {
            $flashMessage = "Les nouveaux mots de passe ne correspondent pas.";
            $flashType = "error";
        } elseif ($userModel->changePassword($currentUser['id'], $currentPwd, $newPwd)) {
            $flashMessage = "Mot de passe modifié avec succès.";
            $flashType = "success";
        } else {
            $flashMessage = "L'ancien mot de passe est incorrect.";
            $flashType = "error";
        }
    }
}
