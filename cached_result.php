<?php
/**
 * @param string $filename
 * @param int $expiration In seconds
 * @param callable $fetch_function A function that returns a string result (e.g. <code>return file_get_contents(...)</code>)
 * @param string $directory
 * @return string Returns the result or an empty string
 */
function cached_result ($filename, $expiration, $fetch_function, $directory = 'wp-content/uploads/cache') {
  if (!is_dir($directory))
    mkdir($directory);

  $filepath = $directory . '/' . $filename;
  $time = time();
  $filetime = file_exists($filepath) ? filemtime($filepath) : $time - $expiration - 1;

  // if expiry passed since the cache was written refresh list
  if (($time - $expiration) > $filetime) {
    // get fresh result
    $result = $fetch_function();
    file_put_contents($filepath, $result);

    if (!is_prod()) {
      $filetime_string = date('H:i:s', $filetime);
      $time_string = date('H:i:s', $time);
      var_log("Busted {$filename} cache from {$filetime_string} UTC, and refreshed at {$time_string} UTC");
    }
  } else {
    $result = file_get_contents($filepath);

    if (!is_prod()) {
      $time_string = date('H:i:s', $filetime);
      var_log("Serving cached {$filename} from {$time_string} UTC");
    }
  }

  return $result ?: '';
}