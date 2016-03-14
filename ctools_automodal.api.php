<?php
/**
 * @file
 * Api Ctools automodal
 *
 * @author
 * Name: Boolboost
 * Web: http://boolboost.com/
 * Email: bool.boost@gmail.com
 */

/**
 * Ajax commands alter
 *
 * @param $commands
 * @param $form_state
 */
function hook_ctools_automodal_commands_alter(&$commands, &$form_state) {
  if (!empty($form_state['executed'])) {
    $commands = array();
    $commands[] = ajax_command_remove('#messages-wrapper');
    $commands[] = ajax_command_before('#content-wrapper', '<div id="messages-wrapper">' . theme('status_messages') . '</div>');
    $commands[] = ctools_modal_command_dismiss();
  }
}

/**
 * Registration new form
 *
 * @param $path
 * @param $item
 * @return array|bool
 */
function hook_ctools_automodal_items_alter($path, $item) {
  $path_ctools = "$path/%ctools_js";
  $return = array($path_ctools => $item);

  switch ($item['page callback']) {
    case 'eck__entity__add':
      $return[$path_ctools]['page callback'] = '_ctools_automodal__callback__eck__entity__add';
      $return[$path_ctools]['page arguments'][] = substr_count($path, '/') + 1;
      $return[$path_ctools]['type'] = MENU_CALLBACK;
      break;

    case 'eck__entity__edit':
      $return[$path_ctools]['page callback'] = '_ctools_automodal__callback__eck__entity__edit';
      $return[$path_ctools]['page arguments'][] = substr_count($path, '/') + 1;
      $return[$path_ctools]['type'] = MENU_CALLBACK;
      break;

    default:
      return FALSE;
  }

  return $return;
}

/**
 * Example eck form add entity content
 *
 * @param $entity_type_name
 * @param $bundle_name
 * @return mixed
 */
function _ctools_automodal__callback__eck__entity__add($entity_type_name, $bundle_name) {
  // remove ajax of args
  $args = func_get_args();
  $ajax = array_pop($args);

  // code module eck
  $entity_type = entity_type_load($entity_type_name);
  $bundle = bundle_load($entity_type_name, $bundle_name);
  $entity = entity_create($entity_type->name, array('type' => $bundle->name));

  // drupal_get_form to ctools_automodal_get_form from $ajax end
  return ctools_automodal_get_form("eck__entity__form_add_{$entity_type_name}_{$bundle_name}", $entity, $ajax);
}

/**
 * Example eck form edit entity content
 *
 * @param $entity_type_name
 * @param $bundle_name
 * @param $id
 * @return mixed
 */
function _ctools_automodal__callback__eck__entity__edit($entity_type_name, $bundle_name, $id) {
  // remove ajax of args
  $args = func_get_args();
  $ajax = array_pop($args);

  // code module eck
  if (is_numeric($id)) {
    $entity = entity_load_single($entity_type_name, $id);
  }
  elseif (is_object($id) and $id->entityType() === $entity_type_name) {
    $entity = $id;
  }

  // drupal_get_form to ctools_automodal_get_form from $ajax end
  return ctools_automodal_get_form("eck__entity__form_edit_{$entity_type_name}_{$bundle_name}", $entity, $ajax);
}