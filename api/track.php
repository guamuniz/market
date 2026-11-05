<?php
require_once __DIR__.'/../includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['ok'=>false],405);
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$event = (string)($payload['event'] ?? 'click');
$target = isset($payload['target']) ? (string)$payload['target'] : null;
track_event($event, $target);
json_response(['ok'=>true]);
