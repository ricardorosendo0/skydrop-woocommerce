
<?php
/**
 * Uninstall - removes all options from DB when user deletes the plugin via WordPress backend.
 * @author Yaser Almasri  for future support contact <andres@skydrop.com.mx>
 * @since 0.1.1
 *
 */
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
	exit();
}
delete_option( 'woocommerce_skydrop_shipping_method_settings' );
