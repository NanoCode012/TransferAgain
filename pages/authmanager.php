<?php

if (isset($_POST['login'])) {
    $dict = [
        'username' => '0',
        'password' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        if (
            $result = $db->row(
                'SELECT id, password from users WHERE username = ? or student_id = ? LIMIT 1',
                $dict['username'],
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
        'password' => '0',
        'bank_name' => '0',
        'bank_num' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        $password_hash = password_hash($dict['password'], PASSWORD_DEFAULT);

        $dict['username'] =
            !isset($_POST['username']) || trim($_POST['username']) == ''
                ? $_SESSION['student_id']
                : trim($_POST['username']);

        $result = $db->cell(
            'SELECT COUNT(id) from users WHERE username = ?',
            $dict['username']
        );

        if ($result == 0) {
            if (
                $db->insert('banks', [
                    'name' => $dict['bank_name'],
                    'number' => $dict['bank_num'],
                ])
            ) {
                $dict['bank_id'] = $db->cell('select max(id) from banks');

                if (
                    $db->insert('users', [
                        'username' => $dict['username'],
                        'password' => $password_hash,
                        'student_id' => $_SESSION['student_id'],
                        'display_name' => $_SESSION['student_name'],
                        'bank_id' => $dict['bank_id'],
                    ])
                ) {
                    session_destroy();
                    $msgBox = success($m_registersuccess);
                } else {
                    $msgBox = error($m_registererror);
                }
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

    checkDictwithPOST($dict, $msgBox);

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
} elseif (isset($_POST['reg_student_id'])) {
    $dict = [
        'student_id' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        $result = $db->cell(
            'SELECT COUNT(id) from users WHERE student_id = ?',
            $dict['student_id']
        );

        if ($result == 0) {
            $dict['student_name'] = getStudentName($dict['student_id']);
            if (!is_null($dict['student_name'])) {
                session_destroy();
                session_start();
                $_SESSION['student_id'] = trim($dict['student_id']);
                $_SESSION['student_name'] = trim($dict['student_name']);
                header('Location: index.php?p=register');
                exit();
            } else {
                $msgBox = error($m_registererror);
            }
        } else {
            $msgBox = error($m_registererror);
        }
    }
}
if (isset($msgBox)) {
    $_SESSION['msgBox'] = $msgBox;
}
header('Location: ?p=login', true, 303);
exit();
?>
