<?php

if (isset($_POST['create_event'])) {
    $dict = [
        'event_name' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        if (
            $db->insert('events', [
                'event_name' => $dict['event_name'],
                'creator_id' => $_SESSION['user_id'],
            ])
        ) {
            $msgBox = success($m_eventadded);
        } else {
            $msgBox = error($m_eventfailedadd);
        }
    } else {
        $msgBox = error($m_eventfailedadd);
    }
} else if (isset($_POST['add_expense'])) {
    $dict = [
        'event_id' => '0',
        'item_amount' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        $dict['item_notes'] =
            !isset($_POST['item_notes']) || trim($_POST['item_notes']) == ''
                ? null
                : trim($_POST['item_notes']);

        if (
            $db->insert('events_expense_history', [
                'event_id' => $dict['event_id'],
                'user_id' => $_SESSION['user_id'],
                'amount' => $dict['item_amount'],
                'notes' => $dict['item_notes'],
            ])
        ) {
            $msgBox = success($m_eventexpenseadded);
            $_SESSION['msgBox'] = $msgBox;
            header('Location: ?p=events/addexpense&id=' . $dict['event_id']);
            exit();
        } else {
            $msgBox = error($m_eventexpensefailedadd);
        }
    } else {
        $msgBox = error($m_eventexpensefailedadd);
    }
} else if (isset($_POST['add_member'])) {
    $dict = [
        'event_id' => '0',
        'user_id' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {

        if (
            $db->insert('events_members', [
                'event_id' => $dict['event_id'],
                'user_id' => $dict['user_id'],
            ])
        ) {
            $msgBox = success($m_eventmemberadded);
            $_SESSION['msgBox'] = $msgBox;
            header('Location: ?p=events/modmember&id=' . $dict['event_id']);
            exit();
        } else {
            $msgBox = error($m_eventmemberfailedadd);
        }
    } else {
        $msgBox = error($m_eventmemberfailedadd);
    }
} else if (isset($_POST['remove_member'])) {
    $dict = [
        'event_id' => '0',
        'user_id' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {

        if (
            $db->delete('events_members', [
                'event_id' => $dict['event_id'],
                'user_id' => $dict['user_id'],
            ])
        ) {
            $msgBox = success($m_eventmemberremoved);
            $_SESSION['msgBox'] = $msgBox;
            // header('Location: ?p=events/modmember&id=' . $dict['event_id']);
            exit();
        } else {
            $msgBox = error($m_eventmemberfailedremove);
        }
    } else {
        $msgBox = error($m_eventmemberfailedremove);
    }
} else if (isset($_POST['remove_expense'])) {
    $dict = [
        'expenseId' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {

        if (
            $db->delete('events_expense_history', [
                'id' => $dict['expenseId'],
            ])
        ) {
            $msgBox = success($m_eventexpenseremoved);
            $_SESSION['msgBox'] = $msgBox;
            // header('Location: ?p=events/modmember&id=' . $dict['event_id']);
            exit();
        } else {
            $msgBox = error($m_eventexpensefailedremove);
        }
    } else {
        $msgBox = error($m_eventexpensefailedremove);
    }
}

if (isset($msgBox)) {
    $_SESSION['msgBox'] = $msgBox;
}
header('Location: ?p=events', true, 303);
exit();

?>
