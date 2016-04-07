<?php
use NinjaWars\core\data\Player;
use NinjaWars\core\extensions\NWTemplate;

/**
 * Displays a template wrapped in the header and footer as needed.
 *
 * Example use:
 * display_page('add.tpl', 'Homepage', get_current_vars(get_defined_vars()), array());
 * @todo move to template class
 */
function display_page($template, $title=null, $local_vars=array(), $options=null) {
    // Updates the quickstat via javascript if requested.
    $quickstat = @$options['quickstat'];
    $quickstat = ($quickstat ? $quickstat : @$local_vars['quickstat']);
    $body_classes = isset($options['body_classes'])? $options['body_classes'] : 
        (isset($local_vars['body_classes'])? $local_vars['body_classes'] : null);

    $is_index = @$options['is_index'];

    // *** Initialize the template object ***
    $tpl = new NWTemplate();
    $tpl->assignArray($local_vars);

    $user_id = self_char_id(); // Character id.
    $player = Player::find($user_id);
    $public_char_info = ($player ? $player->publicData() : []); // Char info to pass to javascript.

    $tpl->assign('logged_in', $user_id);
    $tpl->assign('user_id', $user_id);
    $tpl->assign('title', $title);
    $tpl->assign('quickstat', $quickstat);
    $tpl->assign('is_index', $is_index);
    $tpl->assign('json_public_char_info', ($public_char_info ? json_encode($public_char_info) : null));
    $tpl->assign('body_classes', $body_classes);
    $tpl->assign('main_template', $template);

    $tpl->fullDisplay();
}

/** Will return the rendered content of the template.
 * Example use: $parts = get_certain_vars(get_defined_vars(), array('whitelisted_object');
 * echo render_template('account_issues.tpl', $parts);
 * @todo move to template class
 */
function render_template($template_name, $assign_vars=array()) {
    // Initialize the template object.
    $tpl = new NWTemplate();
    $tpl->assignArray($assign_vars);

    // call the template
    return $tpl->fetch($template_name);
}

/**
 * Pulls out standard vars except arrays and objects.
 * $var_list is get_defined_vars()
 * $whitelist is an array with string names of arrays/objects to allow.
 * @deprecated in favor of passing specific vars to template
 */
function get_certain_vars($var_list, $whitelist=array()) {
    $non_arrays = array();

    foreach ($var_list as $loop_var_name => $loop_variable) {
        if (
            (!is_array($loop_variable) && !is_object($loop_variable))
            || in_array($loop_var_name, $whitelist)) {
            $non_arrays[$loop_var_name] = $loop_variable;
        }
    }

    return $non_arrays;
}

