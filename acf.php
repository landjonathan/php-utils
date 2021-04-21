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
    if (get_field($field, $id)) return get_field($field, $id);
  }

  return false;
}

/**
 * @param $link
 * @param string $class
 * @param string $attrs
 * @param bool|string $inner_element
 * @return false|string
 */
function get_rich_link ($link, $class = '', $attrs = '', $inner_element = false) {
  if ($link['link']) $link = $link['link'];
  elseif ($link['rich_link']) $link = $link['rich_link'];

  $type = $link['type'];
  switch ($type) {
    case 'page':
      $url = $link['page'];
      if (!$url) break;
      $label = $link['label'] ?: get_the_title(url_to_postid($url));
      if ($inner_element) $label = "<{$inner_element}>{$label}</{$inner_element}>";
      if ($anchor = $link['anchor']) $url .= "#{$anchor}";
      return "<a href='{$url}' class='{$class}' $attrs>{$label}</a>";

    case 'link':
      $url = $link['url'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      if ($inner_element) $label = "<{$inner_element}>{$label}</{$inner_element}>";
      $target = $link['new_tab'] ? "target='_blank'" : '';
      return "<a href='{$url}' {$target} class='{$class}' $attrs>{$label}</a>";

    case 'file':
      $url = $link['file'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      if ($inner_element) $label = "<{$inner_element}>{$label}</{$inner_element}>";
      return "<a href='{$url}' class='{$class}' $attrs>{$label}</a>";

    case 'email':
      $url = $link['address'];
      if (!$url) break;
      $label = $link['label'] ?: $url;
      if ($inner_element) $label = "<{$inner_element}>{$label}</{$inner_element}>";
      if ($subject = urlencode($link['subject'])) $url .= "?subject={$subject}";
      return "<a href='mailto:{$url}' class='{$class}' $attrs>{$label}</a>";

  }
  return false;
}

if (function_exists('acf_add_local_field_group')) {
  acf_add_local_field_group([
    'key' => 'group_common',
    'title' => 'Common',
    'fields' => [
      [
        'key' => 'field_rich_link',
        'label' => 'Link',
        'name' => 'rich_link',
        'type' => 'group',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => [
          'width' => '',
          'class' => '',
          'id' => '',
        ],
        'layout' => 'block',
        'sub_fields' => [
          [
            'key' => 'field_5d641a79b625c',
            'label' => 'Type',
            'name' => 'type',
            'type' => 'button_group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
              'width' => '30',
              'class' => '',
              'id' => '',
            ],
            'choices' => [
              'none' => 'None',
              'page' => 'Page',
              'link' => 'Link',
              'file' => 'File',
              'email' => 'E-mail',
            ],
            'allow_null' => 0,
            'default_value' => 'page',
            'layout' => 'horizontal',
            'return_format' => 'value',
          ],
          [
            'key' => 'field_5d641a79b625d',
            'label' => 'Page',
            'name' => 'page',
            'type' => 'page_link',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'page',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '50',
              'class' => '',
              'id' => '',
            ],
            'post_type' => '',
            'taxonomy' => '',
            'allow_null' => 1,
            'allow_archives' => 1,
            'multiple' => 0,
          ],
          [
            'key' => 'field_5d641a79b625e',
            'label' => 'Anchor (optional)',
            'name' => 'anchor',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'page',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '20',
              'class' => '',
              'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ],
          [
            'key' => 'field_5d641a79b625f',
            'label' => 'URL',
            'name' => 'url',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'link',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '50',
              'class' => '',
              'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
          ],
          [
            'key' => 'field_5d641a79b6260',
            'label' => 'New Tab',
            'name' => 'new_tab',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'link',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '20',
              'class' => '',
              'id' => '',
            ],
            'message' => '',
            'default_value' => 1,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
          ],
          [
            'key' => 'field_5d641abdb6262',
            'label' => 'File',
            'name' => 'file',
            'type' => 'file',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'file',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '70',
              'class' => '',
              'id' => '',
            ],
            'return_format' => 'url',
            'library' => 'all',
            'min_size' => '',
            'max_size' => '',
            'mime_types' => '',
          ],
          [
            'key' => 'field_5d657d8d7e505',
            'label' => 'Address',
            'name' => 'address',
            'type' => 'email',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'email',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '35',
              'class' => '',
              'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
          ],
          [
            'key' => 'field_5d657dac7e506',
            'label' => 'Subject',
            'name' => 'subject',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '==',
                  'value' => 'email',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '35',
              'class' => '',
              'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ],
          [
            'key' => 'field_5d641a79b6261',
            'label' => 'Label',
            'name' => 'label',
            'type' => 'text',
            'instructions' => 'Leave empty to use URL or page title.',
            'required' => 0,
            'conditional_logic' => [
              [
                [
                  'field' => 'field_5d641a79b625c',
                  'operator' => '!=',
                  'value' => 'none',
                ],
              ],
            ],
            'wrapper' => [
              'width' => '',
              'class' => '',
              'id' => '',
            ],
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ],
        ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'comment',
          'operator' => '==',
          'value' => 'attachment',
        ],
      ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'modified' => 1612189207,
  ]);
}