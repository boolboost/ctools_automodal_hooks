# Patch for module ctools_automodal

Add hooks:

- hook_ctools_automodal_commands_alter(&$commands, &$form_state) - ajax commands alter
- hook_ctools_automodal_items_alter($path, $item) - registration new form

## Example
Find file "ctools_automodal.api.php"
