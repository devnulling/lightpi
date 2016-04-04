<?php
$pin = intval($_GET['pin']);
$mode = intval($_GET['mode']);
$cmd = 'gpio write '.$pin.' '.$mode;
$out = shell_exec($cmd);
$read =  shell_exec('gpio read '.$pin);

$s = new stdClass;
$s->pin = $pin;
$s->mode = $mode;
$s->read = trim($read);

echo json_encode($s);