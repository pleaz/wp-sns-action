<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://google.com
 * @since      1.0.0
 *
 * @package    Sns_Action
 * @subpackage Sns_Action/admin/partials
 */

?>

<div id="sns-action" class="wrap">

    <div class="container">
        <h2 class="mb-4"><?php echo esc_html( get_admin_page_title() ); ?></h2>

        <div id="error"></div>

        <div class="container">

            <div class="row">

                <div class="col">
                    <form method="post" action="options.php">
                        <?php settings_fields( 'snsaction-settings' ); ?>
                        <?php do_settings_sections( 'snsaction-settings' ); ?>
                        <div class="form-group">
                            <label for="snsaction_aws_key">AWS KEY:</label>
                            <input type="text" class="form-control" id="snsaction_aws_key" name="snsaction_aws_key" value="<?php echo get_option( 'snsaction_aws_key' ); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="snsaction_aws_secret">AWS SECRET:</label>
                            <input type="password" class="form-control" id="snsaction_aws_secret" name="snsaction_aws_secret" placeholder="Enter Secret" required>
                        </div>
                        <div class="form-group">
                            <label for="snsaction_aws_region">AWS REGION:</label>
                            <input type="text" class="form-control" id="snsaction_aws_region" name="snsaction_aws_region" value="<?php echo get_option( 'snsaction_aws_region' ); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="snsaction_aws_account">AWS ACCOUNT:</label>
                            <input type="text" class="form-control" id="snsaction_aws_account" name="snsaction_aws_account" value="<?php echo get_option( 'snsaction_aws_account' ); ?>" required>
                        </div>
                        <div class="form-group">

                            <label for="snsaction_prefix">ENV PREFIX:</label>
                            <select class="form-control" id="snsaction_prefix" name="snsaction_prefix">
                                <option value="prod"<?php if (get_option( 'snsaction_prefix' ) == 'prod') { ?> selected<?php } ?>>Prod</option>
                                <option value="stage"<?php if (get_option( 'snsaction_prefix' ) == 'stage') { ?> selected<?php } ?>>Stage</option>
                            </select>
                        </div>
                        <?php submit_button(); ?>
                    </form>
                </div>

            </div>

        </div>

    </div>

</div>
