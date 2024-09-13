<?php
// 关闭应用的逻辑
$statusFile = 'status.txt';

// 尝试打开文件并获取锁
$fileHandle = fopen($statusFile, 'c+');
if (flock($fileHandle, LOCK_EX)) { // 获取排他锁
    $status = trim(fread($fileHandle, filesize($statusFile)));

    if ($status == '1') {
        // 状态为 1，将状态设置为 0
        ftruncate($fileHandle, 0); // 清空文件内容
        rewind($fileHandle); // 移动文件指针到开头
        fwrite($fileHandle, '0');
        fflush($fileHandle); // 刷新输出到文件

        $response = ['status' => 'success'];
    } else {
        // 状态为 0，应用已经关闭
        $response = ['status' => 'failure', 'message' => 'Application is not in use'];
    }

    flock($fileHandle, LOCK_UN); // 释放锁
} else {
    $response = ['status' => 'error', 'message' => 'Unable to obtain file lock'];
}

fclose($fileHandle);

header('Content-Type: application/json');
echo json_encode($response);
?>
