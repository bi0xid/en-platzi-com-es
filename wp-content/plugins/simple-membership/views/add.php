<?php SimpleWpMembership::enqueue_validation_scripts(array('ajaxEmailCall' => array('extraData'=>'&action=swpm_validate_email&member_id='.filter_input(INPUT_GET, 'member_id')))); ?>
<div class="swpm-registration-widget-form">
    <form id="swpm-registration-form" class="swpm-validate-form" name="swpm-registration-form" method="post" action="">
        <input type ="hidden" name="level_identifier" value="<?php echo $level_identifier ?>" />
        <table>
            <tr class="swpm-registration-username-row">
                <td><label for="user_name"><?php echo SwpmUtils::_('Username') ?></label></td>
                <td><input type="text" id="user_name" class="validate[required,custom[noapostrophe],custom[SWPMUserName],minSize[4],ajax[ajaxUserCall]]" value="<?php echo esc_attr($user_name); ?>" size="50" name="user_name" /></td>
            </tr>
            <tr class="swpm-registration-email-row">
                <td><label for="email"><?php echo SwpmUtils::_('Email') ?></label></td>
                <td><input type="text" id="email" class="validate[required,custom[email],ajax[ajaxEmailCall]]" value="<?php echo esc_attr($email); ?>" size="50" name="email" /></td>
            </tr>
            <tr class="swpm-registration-password-row">
                <td><label for="password"><?php echo SwpmUtils::_('Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password" value="" size="50" name="password" /></td>
            </tr>
            <tr class="swpm-registration-password-retype-row">
                <td><label for="password_re"><?php echo SwpmUtils::_('Repeat Password') ?></label></td>
                <td><input type="password" autocomplete="off" id="password_re" value="" size="50" name="password_re" /></td>
            </tr>
            <tr class="swpm-registration-firstname-row">
                <td><label for="first_name"><?php echo SwpmUtils::_('First Name') ?></label></td>
                <td><input type="text" id="first_name" value="<?php echo esc_attr($first_name); ?>" size="50" name="first_name" /></td>
            </tr>
            <tr class="swpm-registration-lastname-row">
                <td><label for="last_name"><?php echo SwpmUtils::_('Last Name') ?></label></td>
                <td><input type="text" id="last_name" value="<?php echo esc_attr($last_name); ?>" size="50" name="last_name" /></td>
            </tr>
            <tr class="swpm-registration-membership-level-row">
                <td><label for="membership_level"><?php echo SwpmUtils::_('Membership Level') ?></label></td>
                <td>
                    <?php
                    echo $membership_level_alias; //Show the level name in the form.
                    //Add the input fields for the level data.
                    echo '<input type="hidden" value="' . $membership_level . '" size="50" name="membership_level" id="membership_level" />';
                    //Add the level input verification data.
                    $swpm_p_key = get_option('swpm_private_key_one');
                    if (empty($swpm_p_key)) {
                        $swpm_p_key = uniqid('', true);
                        update_option('swpm_private_key_one', $swpm_p_key);
                    }
                    $swpm_level_hash = md5($swpm_p_key . '|' . $membership_level); //level hash
                    echo '<input type="hidden" name="swpm_level_hash" value="' . $swpm_level_hash . '" />';
                    ?>
                </td>
            </tr>           
        </table>        

        <div class="swpm-before-registration-submit-section" align="center"><?php echo apply_filters('swpm_before_registration_submit_button', ''); ?></div>

        <div class="swpm-registration-submit-section" align="center">
            <input type="submit" value="<?php echo SwpmUtils::_('Register') ?>" class="swpm-registration-submit" name="swpm_registration_submit" />
        </div>

        <input type="hidden" name="action" value="custom_posts" />

    </form>
</div>
