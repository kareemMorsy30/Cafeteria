<?php
    require 'utils/DBConnection.php';
    $conn = database_connection\DBConnection::getInstance();

    $stat = $conn->prepare('SELECT * FROM users WHERE id = ?');

    $stat->execute([$_GET['id']]);

    foreach($stat->fetchAll() as $result) {
        $passValue = $result['password'];
    };

    if ($passValue == $_POST['pass']) {
        $hash = $_POST['pass'];
    } else {
        $options = [
            'cost' => 12
          ];
        $hash = password_hash($_POST['pass'], PASSWORD_BCRYPT, $options);
    }

    $sql = 'UPDATE users SET name=?, email=?, password=?, roomNo=?, ext=?, picture=?, privilege=?
            where id=?';
    $stat = $conn->prepare($sql);
    $stat->execute([$_POST['name'], $_POST['email'], $hash, $_POST['roomNo'], $_POST['ext'], 'upload/'.$_FILES['profil']['name'], 'user', $_GET['id']]);

    header('location:AllUsers.php?updated=1');