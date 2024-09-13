<?php
// 启动应用的逻辑
$statusFile = 'status.txt';

// 初始化状态文件，如果不存在则创建
if (!file_exists($statusFile)) {
    file_put_contents($statusFile, '0');
}

// 尝试打开文件并获取锁
$fileHandle = fopen($statusFile, 'c+');
if (flock($fileHandle, LOCK_EX)) { // 获取排他锁
    $status = trim(fread($fileHandle, filesize($statusFile)));

    if ($status == '0') {
        // 状态为 0，允许启动并将状态设置为 1
        ftruncate($fileHandle, 0); // 清空文件内容
        rewind($fileHandle); // 移动文件指针到开头
        fwrite($fileHandle, '1');
        fflush($fileHandle); // 刷新输出到文件

        $response = ['status' => 'success'];
    } else {
        // 状态为 1，应用正在使用
        $response = ['status' => 'failure', 'message' => 'Application is already in use'];
    }

    flock($fileHandle, LOCK_UN); // 释放锁
} else {
    $response = ['status' => 'error', 'message' => 'Unable to obtain file lock'];
}

fclose($fileHandle);

header('Content-Type: application/json');
echo json_encode($response);
?>
