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
 * Try to return a field value an array of post IDs, returning the first if finds
 * @example get_field_from_cascade('info', [get_the_id(), 14, 522])
 * @param string $field The field's name
 * @param int[] $posts Post id to use
 * @return mixed
 */
function get_field_from_cascade ($field, $posts) {
  foreach ($posts as $id) {
    if (get_field($id)) return get_field($id);
  }
}