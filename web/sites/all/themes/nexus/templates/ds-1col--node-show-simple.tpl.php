<?php
global $user;

$is_editor = is_user_editor($user);
$show_id = $nid;

$entity_id = NULL;
if ($node = menu_get_object()) {
  $entity_id = $node->nid;
}

$is_member = $is_editor;
$pool_locked = !$is_editor;

$pool_node = node_load($entity_id);

if ($pool_node) {
  $pool_locked = boolval($pool_node->field_closed['und'][0]['value']);
  $is_member = og_is_member('node', $entity_id);
}
else {
  $entity_id = $show_id;
}

$redirect_url = drupal_get_path_alias(current_path());
$show_expired = isset($_SESSION['show_expired'])
  ? $_SESSION['show_expired']
  : FALSE;
?>
<<?php print $ds_content_wrapper;
print $layout_attributes; ?> class="ds-1col <?php print $classes; ?> clearfix">

<?php if (isset($title_suffix['contextual_links'])): ?>
  <?php print render($title_suffix['contextual_links']); ?>
<?php endif; ?>

<form action="<?php print "/my_picks/{$entity_id}"; ?>" method="POST"
      class="clearfix">
  <?php print $ds_content; ?>
  <input type="hidden" name="redirectUrl"
         value="<?php print $redirect_url; ?>"/>
  <?php if (is_user_editor($user) || !$show_expired): ?>
    <input type="hidden" name="show_id" value="<?php print $show_id; ?>"/>
    <input type="submit" value="<?php print t('Save Picks'); ?>"/>
  <?php endif; ?>
  <?php unset($_SESSION['show_expired']); ?>
</form>

<?php if ($is_editor && can_show_be_published($show_id)): ?>
  <form action="<?php print "/publish_show/{$show_id}"; ?>" method="POST">
    <input type="hidden" name="redirectUrl"
           value="<?php print $redirect_url; ?>"/>
    <input type="submit" value="Publish Show Results"/>
  </form>
<?php endif; ?>

</<?php print $ds_content_wrapper ?>>

<?php if (!empty($drupal_render_children)): ?>
  <?php print $drupal_render_children ?>
<?php endif; ?>
