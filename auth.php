<?php
require_once __DIR__ . '/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login') {
    $identifier = sanitize($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check against DB or JSON
    $foundUser = null;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$identifier, $identifier]);
        $foundUser = $stmt->fetch();
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        foreach ($data['users'] as $u) {
            if ($u['email'] === $identifier || $u['phone'] === $identifier) {
                $foundUser = $u;
                break;
            }
        }
    }

    if ($foundUser && password_verify($password, $foundUser['password'])) {
        $_SESSION['user'] = [
            'id' => $foundUser['id'],
            'name' => $foundUser['name'],
            'email' => $foundUser['email'],
            'phone' => $foundUser['phone']
        ];
        header("Location: index.php?msg=logged_in");
        exit;
    } else {
        // Fallback demo user check if plain text
        if ($identifier === 'user@ksrtc.in' && $password === 'password123') {
            $_SESSION['user'] = [
                'id' => 1,
                'name' => 'Demo Passenger',
                'email' => 'user@ksrtc.in',
                'phone' => '9876543210'
            ];
            header("Location: index.php?msg=logged_in");
            exit;
        }
        header("Location: index.php?error=invalid_credentials");
        exit;
    }
} elseif ($action === 'register') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($useSqlite && $pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $hash]);
                $userId = $pdo->lastInsertId();
            } catch (Exception $e) {
                header("Location: index.php?error=email_exists");
                exit;
            }
        } else {
            $data = json_decode(file_get_contents($jsonStorageFile), true);
            $userId = count($data['users']) + 1;
            $data['users'][] = [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $hash,
                'created_at' => date('Y-m-d H:i:s')
            ];
            file_put_contents($jsonStorageFile, json_encode($data, JSON_PRETTY_PRINT));
        }

        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];
        header("Location: index.php?msg=registered");
        exit;
    }
} elseif ($action === 'logout') {
    unset($_SESSION['user']);
    header("Location: index.php?msg=logged_out");
    exit;
}

header("Location: index.php");
exit;

