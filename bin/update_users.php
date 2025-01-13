<?php

if (!file_exists($TMP_DIRECTORY . '/' . 'update')) {
    exit(0);
}
unlink($TMP_DIRECTORY . '/' . 'update');

$stmt = $mysqli->prepare('SELECT * FROM user_shares');
$stmt->execute();
$result = $stmt->get_result();
$shares = $result->fetch_all(MYSQLI_ASSOC);

$file_data = file_get_contents('header.conf');

foreach ($shares as $share) {
    if (!file_exists('/home/' . $share)) {
        $password = 'qwerty';
        exec('useradd -g users -m -s /bin/bash ' . $share);
        exec("echo \"$share:$password\" | sudo chpasswd");
        exec("echo \"$password\"; echo \"$password\" ) | sudo smbpasswd -s -a \"$share\"");
    }

    $user_data = file_get_contents('home_template.conf');
    $user_data = str_replace($user_data, '%SHARE%', $share);
    $file_data .= "\n\n" . $user_data;
}

file_put_contents('/etc/samba/smb.conf', $file_data);

exec('systemctl reload smbd');
exec('systemctl reload nmbd');

