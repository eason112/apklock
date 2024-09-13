<?php
session_start(); // 启用会话

// 文件来保存应用的状态
$statusFile = 'status.txt';

// 初始化状态文件，如果不存在则创建
if (!file_exists($statusFile)) {
    file_put_contents($statusFile, '0');
}

$status = file_get_contents($statusFile);

// 检查当前状态
if ($status == '0') {
    // 状态为 0，允许启动并将状态设置为 1
    file_put_contents($statusFile, '1');
    echo json_encode(['status' => 'success']);
} else {
    // 状态为 1，应用正在使用
    echo json_encode(['status' => 'failure', 'message' => 'Application is already in use']);
}
?>
