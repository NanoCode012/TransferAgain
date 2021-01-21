<?php

if (isset($_POST['login'])) {
    $dict = [
        'username' => '0',
        'password' => '0'
    ];

    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($msgBox)) {
        if (
            $result = $db->row(
                'SELECT id, password from users WHERE username = ?',
                $dict['username']
            )
        ) {
            $id = $result['id'];
            $password_hash = $result['password'];
            if (password_verify($dict['password'], $password_hash)) {
                session_destroy();
                session_start();
                $_SESSION['user_id'] = $id;
                header('Location: index.php?p=welcome');
                exit();
            } else {
                $msgBox = error($m_loginerror);
            }
        } else {
            $msgBox = error($m_loginerror);
        }
    }
} elseif (isset($_POST['register'])) {
    $dict = [
        'username' => '0',
        'password' => '0'
    ];
    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }
    if (!isset($msgBox)) {
        $password_hash = password_hash($dict['password'], PASSWORD_DEFAULT);

        $result = $db->cell(
            'SELECT COUNT(id) from users WHERE username = ?',
            $dict['username']
        );

        if ($result == 0) {
            if (
                $stmt = $db->insert('users', [
                    'username' => $dict['username'],
                    'password' => $password_hash
                ])
            ) {
                $msgBox = success($m_registersuccess);
            } else {
                $msgBox = error($m_registererror);
            }
        } else {
            $msgBox = error($m_registererror);
        }
    }
} elseif (isset($_POST['reset'])) {
    $dict = [
        'password' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $msgBox = error($key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if (!isset($msgBox)) {
        $password_hash = password_hash($dict['password'], PASSWORD_DEFAULT);
        $result = $db->cell(
            'SELECT COUNT(id) from users WHERE username = ?',
            $dict['username']
        );

        if ($result == 1) {
            if (
                $stmt = $db->update(
                    'users',
                    ['password' => $password_hash],
                    ['username' => $dict['username']]
                )
            ) {
                $msgBox = success($m_resetsuccess);
            } else {
                $msgBox = error($m_reseterror);
            }
        } else {
            $msgBox = error($m_reseterror);
        }
    }
}
if (isset($msgBox)) {
    $_SESSION['msgBox'] = $msgBox;
}
header('Location: ?p=login', true, 303);
exit();
?>
