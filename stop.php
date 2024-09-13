<?php
session_start(); // 启用会话

// 文件来保存应用的状态
$statusFile = 'status.txt';

// 重置状态文件为 0
file_put_contents($statusFile, '0');
echo json_encode(['status' => 'success']);
?>
