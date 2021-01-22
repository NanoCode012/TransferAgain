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
} elseif (isset($_POST['add_expense'])) {
    $dict = [
        'event_id' => '0',
        'item_amount' => '0',
    ];

    checkDictwithPOST($dict, $msgBox);

    if (!isset($msgBox)) {
        $dict['item_disc'] =
            !isset($_POST['item_disc']) || trim($_POST['item_disc']) == ''
                ? ''
                : trim($_POST['item_disc']);

        if (
            $db->insert('events_expense_history', [
                'event_id' => $dict['event_id'],
                'user_id' => $_SESSION['user_id'],
                'amount' => $dict['item_amount'],
                'notes' => $dict['item_disc'],
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
}

if (isset($msgBox)) {
    $_SESSION['msgBox'] = $msgBox;
}
header('Location: ?p=events', true, 303);
exit();

?>
