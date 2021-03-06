<?php
/**
 * Get SVG contents from a URL, and falls back to an <code>img</code> tag
 * @param string $url
 * @param string $alt
 * @param string[] $attrs
 * @return string
 */
function svg_or_img ($url, $alt = '', $attrs = []) {
  if (!$url) return '';

  if (strpos($url, 'svg')) {
    // try plain URL
    $contents = file_get_contents(str_replace('https', 'http', $url));
    if ($contents) return $contents;

    // try stripping origin
    $parsed_url = parse_url($url);
    if ($parsed_url) {
      $path = $parsed_url['path'];
      $contents = file_get_contents($path);
      if ($contents) return $contents;

      $relative_path = '.' . $path;
      $contents = file_get_contents($relative_path);
      if ($contents) return $contents;
    }

    // if all else fails, try curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $contents = curl_exec($ch);
    curl_close($ch);
    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200)
      return $contents;
  }

  $attrs_string = '';
  foreach ($attrs as $attr => $val) {
    $attrs_string .= $attr . "='$val' ";
  }
  return "<img src='$url' alt='{$alt}' $attrs_string>";
}

/**
 * Returns a full classname, based on individual conditions for each class
 *
 * Each item in $list can be a classname, e.g. ['active'], or a classname and condition, e.g. ['active' => i > 0]
 * @param string[] $list
 * @return string
 */
function conditional_classes ($list = []) {
  $classList = [];
  foreach ($list as $className => $condition) {
    if (empty($className)) { // class name without condition, e.g. ['active']
      array_push($classList, $condition);
    } elseif ($condition) {
      array_push($classList, $className);
    }
  }
  return implode(' ', $classList);
}

/**
 * Wraps string with a <code>p</code> tag, only if required
 * @param string $p
 * @return string
 */
function p ($p) {
  $pTagged = (substr($p, 0, 2) === '<p'); // starts with <p
  return $pTagged ? $p : "<p>{$p}</p>";
}

/**
 * Strips specific HTML tags from a string
 * @param $str string
 * @param $tags string | string[]
 * @return string
 */
function strip_specific_tags ($str, $tags) {
  if (!is_array($tags)) { $tags = [$tags]; }
  foreach ($tags as $tag) {
    $_str = preg_replace('/<\/' . $tag . '>/i', '', $str);
    if ($_str != $str) {
      $str = preg_replace('/<' . $tag . '[^>]*>/i', '', $_str);
    }
  }
  return $str;
}