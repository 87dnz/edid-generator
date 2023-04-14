<?php
function dump_hex($file) {
    $data = file_get_contents($file);
    if ($data === false) {
         throw new Exception("Can't Read file: $file");
    }

    echo '{';
    echo "\r\n";
    echo '  // ' . $file;
    echo "\r\n";
    foreach (str_split($data) as $i => $v) {
        if ($i % 16 === 0) {
            echo "  ";
        }
        echo '0x' . bin2hex($v) . ', ';
        if ($i % 16 === 15) {
            echo "\r\n";
        }
    }
    echo '},';
    echo "\r\n";
}

    dump_hex("800x600.bin");
    dump_hex("1024x768.bin");
    dump_hex("1280x720.bin");
    dump_hex("1280x1024.bin");
    dump_hex("1360x768.bin");
    dump_hex("1600x900.bin");
    dump_hex("1600x1200.bin");
    dump_hex("1920x1080.bin");
?>

