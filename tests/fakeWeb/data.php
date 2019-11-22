<?php
// 假資料包
$userList = [
    [
        'id' => 5241,
        'username' => 'admin',
        'password' => '123456',
        'email' => 'admin@example.com',
    ], [
        'id' => 6542,
        'username' => 'user1',
        'password' => 'user1_pass',
        'email' => 'user1@example.com',
    ], [
        'id' => 6543,
        'username' => 'user2',
        'password' => 'user2_pass',
        'email' => 'user2@example.com',
    ],
];

$userList = array_column($userList, null, 'id');
