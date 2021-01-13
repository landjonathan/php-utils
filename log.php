<?php
/**
 * Logs a value to the Javascript console, by injecting a <code>script</code> tag
 * @param $var
 */
function var_log ($var) {
  $json = json_encode($var);
  echo "<script>console.log({$json})</script>";
}

/**
 * Logs an array of values in a group to the Javascript console
 * @param string $label
 * @param array $items
 */
function var_log_group ($label = '', $items = []) {
//  $trace = json_encode(debug_backtrace());
  echo "<script>console.group('$label');";
//  echo "console.log({'trace': $trace});";
  foreach ($items as $key => $value) { $json = json_encode($value); echo "console.log('$key', $json);"; }
  echo "console.groupEnd();</script>";
}