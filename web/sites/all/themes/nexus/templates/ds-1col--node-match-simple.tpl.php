<?php

/**
 * @file
 * Display Suite 1 column template.
 */

global $user;
$pool_id = NULL;
$node_type = NULL;
if ($node = menu_get_object()) {
  // Get the nid
  $pool_id = $node->nid;
  $node_type = $node->type;
}
?>

<<?php print $ds_content_wrapper;
print $layout_attributes; ?> class="ds-1col <?php print $classes; ?> clearfix">

<?php if (isset($title_suffix['contextual_links'])): ?>
  <?php print render($title_suffix['contextual_links']); ?>
<?php endif; ?>

<?php
if ($pool_id !== NULL && $node_type === 'pool' || is_user_editor($user)) {
  if (isset($content['group_matches'])) {
    unset($content['group_matches']);
  }

  $fields = [];
  foreach (GRUNFES_TEAM_KEYS as $team_key) {
    $key = "field_team_${team_key}";
    $team_var = ${$key};

    if (isset($team_var) && !empty($team_var)) {
      $team_members = array_map(function ($obj) {
        return '<span>' . $obj['entity']->title . '</span>';
      }, $team_var);

      $text = join('', $team_members);

      $value = base64_encode(serialize(array_column($team_var, 'target_id')));

      $fields[$key] = (object) array(
        'raw' => $nid,
        'value' => $value,   // Ovde bi trebalo da mi vrati ime Wrestlera
        'text' => $text,
      );
    }
  }
  print render($content);

  print grunfes_render_matches($pool_id, $fields);
}
else {
  print render($content);
}
?>

</<?php print $ds_content_wrapper ?>>

<?php if (!empty($drupal_render_children)): ?>
  <?php print $drupal_render_children ?>
<?php endif; ?>
