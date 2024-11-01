<?php 

if (isset($_POST['reset-stats-settings'])) {
    
    global $wpdb;

    $count = $wpdb->query(
        "DELETE FROM $wpdb->options
        WHERE option_name LIKE '\_transient_wsp\_%'"
    );

} 

?>
<form method="post" action="options.php">
    <?php settings_fields( 'sip-spwc-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <td><label><input type="checkbox" name="display_sales_single_product_page" value="true" <?php echo esc_attr( get_option('display_sales_single_product_page', false))?' checked="checked"':''; ?> /> Enable in Single Product Page   </label></td>
            <td colspan="3"><label><input type="checkbox" name="display_sales_shop_page" value="true" <?php echo esc_attr( get_option('display_sales_shop_page', false))?' checked="checked"':''; ?> /> Enable in Shop Page </label></td>
        </tr>
        <tr>
            <td>
              <div>
                <?php 
                    $settings       = array('teeny' => false, 'tinymce' => true, 'textarea_rows' => 12, 'tabindex' => 1 );
                    $editor_id      = "show_in_product_page_view_editor"; 
                    $editor_content = get_option('show_in_product_page_view_editor'); 
                    wp_editor( $editor_content, $editor_id, $settings );
                ?>
              </div>
            </td>
            <td>
                <div>
                <?php 
                    $settings       = array('teeny' => false, 'tinymce' => true, 'textarea_rows' => 12, 'tabindex' => 1 );
                    $editor_id      = "show_in_product_in_list_view_editor"; 
                    $editor_content = get_option('show_in_product_in_list_view_editor'); 
                    wp_editor( $editor_content, $editor_id, $settings );
                ?>
                </div>
            </td>
        </tr>
    </table>
    <?php 
        submit_button(); 
    ?>
</form>

<?php if( class_exists( 'SIP_Social_Proof_Add_On_WC' ) ) { ?>
    <form method="post" action="">
        <?php 
            submit_button( 'Reset Stats', 'secondary' , 'reset-stats-settings');
        ?>
    </form>
    <form method="post" action="options.php">
        <?php settings_fields( 'sip-spwc-hours-settings-group' ); ?>
        <table class="form-table">
          <tr valign="top">
            <td><label>Refresh stats every <input type="number" name="refresh_stats_every_x_hours" value="<?php echo get_option('refresh_stats_every_x_hours'); ?>" style="width: 50px;" /> hours</label></td>
          </tr> 
        </table>
        <?php 
            submit_button(); 
        ?>
    </form>
<?php } ?>