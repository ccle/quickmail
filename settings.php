<?php

// Written at Louisiana State University

defined('MOODLE_INTERNAL') || die;

if($ADMIN->fulltree) {
    require_once $CFG->dirroot . '/blocks/quickmail/lib.php';

    $select = array(0 => get_string('no'), 1 => get_string('yes'));
    
    // BEGIN UCLA MOD CCLE-4166
    $settings->add(new admin_setting_configcheckbox_with_lock(
                            'block_quickmail/allowstudents',
                            new lang_string('allowstudents', 'block_quickmail'),
                            new lang_string('allowstudentsdesc', 'block_quickmail'),
                            array('value' => 0, 'locked' => 1))
                   );
    //$allow = quickmail::_s('allowstudents');
    //$settings->add(
    //    new admin_setting_configselect('block_quickmail_allowstudents',
    //        $allow, $allow, 0, $select
    //    )
    //);
    // END UCLA MOD-4166

    $roles = $DB->get_records('role', null, 'sortorder ASC');

    $default_sns = array('editingteacher', 'teacher', 'student');
    // START UCLA MOD: CCLE-3964 - Quickmail block doesn't install cleanly on PHP 5.4
//    $defaults = array_filter($roles, function ($role) use ($default_sns) {
//        return in_array($role->shortname, $default_sns);
//    });
    $defaults = array();
    foreach ($roles as $index => $role) {
        if (in_array($role->shortname, $default_sns)) {
            $defaults[$index] = $role;
        }
    }
    // END UCLA MOD: CCLE-3964

    $only_names = function ($role) { return $role->shortname; };

    $select_roles = quickmail::_s('select_roles');
    $settings->add(
        new admin_setting_configmultiselect('block_quickmail_roleselection',
            $select_roles, $select_roles,
            array_keys($defaults),
            array_map($only_names, $roles)
        )
    );

    $settings->add(
        new admin_setting_configselect('block_quickmail_receipt',
        quickmail::_s('receipt'), quickmail::_s('receipt_help'),
        0, $select
        )
    );

    $options = array(
        0 => get_string('none'),
        'idnumber' => get_string('idnumber'),
        'shortname' => get_string('shortname')
    );

    $settings->add(
        new admin_setting_configselect('block_quickmail_prepend_class',
            quickmail::_s('prepend_class'), quickmail::_s('prepend_class_desc'),
            0, $options
        )
    );

    $groupoptions = array(
        'strictferpa' => get_string('strictferpa', 'block_quickmail'),
        'courseferpa' => get_string('courseferpa', 'block_quickmail'),
        'noferpa' => get_string('noferpa', 'block_quickmail')
    );

    $settings->add(
        new admin_setting_configselect('block_quickmail_ferpa',
            quickmail::_s('ferpa'), quickmail::_s('ferpa_desc'),
            'strictferpa', $groupoptions
        )
    );

}
