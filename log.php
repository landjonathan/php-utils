<?php
/**
 * Logs a value to the Javascript console, by injecting a <code>script</code> tag
 * @param $var
 */
function var_log ($var) {
  $json = json_encode($var);
  echo "<script>console.log({$json})</script>";
}