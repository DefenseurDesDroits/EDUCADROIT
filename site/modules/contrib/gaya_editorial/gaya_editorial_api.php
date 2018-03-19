<?php

/**
 * Implements HOOK_ge_module_api
 * - Provide a new list of function can be called in the field_ge_module
 * - The callback must be return a html or array randable.
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 * @return array
 */
function HOOK_ge_module_api() {
  return array('callback_function_ge_module' => 'Callback de test');
}

/**
 * Callback of gaya_editorial_ge_module_test
 * This callback must be return a html or array randable.
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 * @return array renderable
 */
function callback_function_ge_module() {
  return array('#markup' => '<div class="">some html</div>');
}