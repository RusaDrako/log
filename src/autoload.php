<?php

namespace RusaDrako\log;

$arr_load = [
	'/log.php',
];

foreach($arr_load as $k => $v) {
	require_once(__DIR__ . '/' . $v);
}
