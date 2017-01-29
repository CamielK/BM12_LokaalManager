<?php

$hash = '';
        for ($i = 0; $i < 20000; $i++) {
            $hash = hash('sha512', $hash . '' . 'admin');
        }
        echo $hash;

?>