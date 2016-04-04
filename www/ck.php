<?php

$pin = intval($_GET['pin']);
$read = shell_exec('gpio read ' . $pin);

$s = new stdClass;
$s->pin = $pin;
$s->read = trim($read);

echo json_encode($s);
