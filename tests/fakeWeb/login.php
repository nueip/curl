<?php
include_once('data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // login check
    $name2id = array_column($userList, 'id', 'username');
    $user = $userList[$name2id[$_POST['username']]] ?? null;

    $loginFlag = (isset($user)
        && $_POST['username'] === $user['username']
        && $_POST['password'] === $user['password']);

    $result = $loginFlag
        ? [ 'code' => 200, 'message' => 'login success']
        : [ 'code' => 400, 'message' => 'Login fail'];
    // response success or fail
} else {
    // response fail
    $result = [
        'code' => 400,
        'message' => 'undefined Method',
    ];
}

http_response_code($result['code']);
echo json_encode($result);