<?php /** @noinspection PhpMissingParamTypeInspection */
/**
 * Try to return a field value from post, than category, then options
 * @param string $field The field's name
 * @return mixed
 */
function get_field_from_post_or_category_or_option ($field) {
  return
    get_field($field) ?:
      get_field($field, get_the_category()[0]) ?:
        get_field($field, 'option');
}

/**
 * Try to return a field value from post, then options
 * @param string $field The field's name
 * @param null|int $post Post id to use
 * @return mixed
 */
function get_field_from_post_or_option ($field, $post = null) {
  return
    get_field($field, $post) ?:
      get_field($field, 'option');
}

/**
 * Try to return a field value an array of post IDs, returning the first if finds.
 *
 * e.g. <code>get_field_from_cascade('info', [get_the_id(), 14, 522])</code>
 * @param string $field The field's name
 * @param int[] $posts Post id to use
 * @return mixed
 */
function get_field_from_cascade ($field, $posts) {
  foreach ($posts as $id) {
    if (get_field($id)) return get_field($id);
  }

  return false;
}

/**
 * @param $link
 * @return false|string
 */
function get_rich_link ($link) {
  if ($link['link']) $link = $link['link'];
  elseif ($link['rich_link']) $link = $link['rich_link'];

  $type = $link['type'];
  switch ($type) {
    case 'page':
      $url = $link['page'];
      if (!$url) break;
      $label = $link['label'] ?: get_the_title(url_to_postid($url));
      if ($anchor = $link['anchor']) $url .= "#{$anchor}";
      return "<a href='{$url}'>{$label}</a>";

    case 'link':
      $url = $link['url'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      $target = $link['new_tab'] ? "target='_blank'" : '';
      return "<a href='{$url}' {$target}>{$label}</a>";

    case 'file':
      $url = $link['file'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      return "<a href='{$url}'>{$label}</a>";

    case 'email':
      $url = $link['address'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      if ($subject = urlencode($link['subject'])) $url .= "?subject={$subject}";
      return "<a href='mailto:{$url}'>{$label}</a>";

  }
  return false;
}