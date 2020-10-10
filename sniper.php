<?php 


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/tableview.php');
require_once(__DIR__ . '/output/renderer.php');

global $PAGE;
$d = new DateTime();
$d->setTime(0,0);

$totem = new \block_totem\tableview([
    'blockid' => 21,
    'date' => $d->getTimestamp(),
    'collapsible' => TRUE,
    'collapsed' => TRUE,
    'showDate' => TRUE]);

echo $PAGE->get_renderer('block_totem')->render($totem);