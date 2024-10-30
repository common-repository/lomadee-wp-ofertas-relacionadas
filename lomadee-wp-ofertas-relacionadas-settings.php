<?php
// Avoid to load this file directly
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) and ( __FILE__ == basename( $_SERVER['SCRIPT_FILENAME'] ) ) )
    exit();

$base_name = plugin_basename('lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-settings.php');
$base_page = 'admin.php?page='.$base_name;

if( isset( $_POST['action'] ) )
    $update = $plugin_lomadee_wp_related_offers->update_settings();

$options = $plugin_lomadee_wp_related_offers->get_settings();

$source_id_test_message = $plugin_lomadee_wp_related_offers->source_id_test( $options['source_id'] );
?>
<div class="wrap">

    <div class="icon32">
        <img src="<?php echo $plugin_lomadee_wp_related_offers->get_icon_manager(); ?>" alt="BuscaPé" />
        <br>
    </div>

    <h2><?php _e( 'Lomadee WP - Ofertas Relacionadas', 'lomadeewpro' ); ?></h2>
    <?php if ( isset( $update ) and $update ) : ?>
        <div class="updated fade" id="message">
            <p> <?php _e('Settings updated successfully!','lomadeewpro') ?> </p>
        </div>
    <?php endif; ?>
    <form action="<?php echo $base_page; ?>" method="post">
        <h3><?php _e('Request options', 'lomadeewpro'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label for="source-id"><?php _e('Source ID', 'lomadeewpro'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="source-id" name="source_id" class="regular-text" value="<?php echo $options['source_id']; ?>" />
                        <span class="description"><?php _e('The Source ID is used to indentify the publisher that uses this plugin. <a href="http://br.lomadee.com/">Get a Source ID</a>.', 'lomadeewpro'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="country-code"><?php _e('Country', 'lomadeewpro'); ?></label>
                    </th>
                    <td>
                        <select id="country-code" name="country_code" class="regular-text">
                            <?php foreach( (array)$plugin_lomadee_wp_related_offers->get_countries() as $country_code => $country_name ) : ?>
                            <option value="<?php echo $country_code; ?>"<?php echo( $country_code == $options['country_code'] ) ? ' selected="selected"' : ''; ?>><?php echo $country_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="description"><?php _e('Origin country of products.', 'lomadeewpro'); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php _e('Display options', 'lomadeewpro'); ?></h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label for="standard-view"><?php _e('Standard view', 'lomadeewpro'); ?></label>
                    </th>
                    <td>
                        <input type="radio" id="standard-view-automatic" name="standard_view" value="automatic" <?php echo( 'automatic' == $options['standard_view'] ) ? 'checked="checked"' : ''; ?>/> <label for="standard-view-automatic"><?php _e('Automatic. Uses the post tags related to the post.', 'lomadeewpro'); ?></label><br />
                        <input type="radio" id="standard-view-manual" name="standard_view" value="manual" <?php echo( 'manual' == $options['standard_view'] ) ? 'checked="checked"' : ''; ?>/> <label for="standard-view-manual"><?php _e('Manual. Uses a shortcode like it [lomadeewpro category=\'xxx\' keywords=\'keyword, keyword2\'] defined in a button in the post editor toolbar.', 'lomadeewpro'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="products-about"><?php _e('About the products', 'lomadeewpro'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('About the products', 'lomadeewpro'); ?></span>
                            </legend>
                            <label for="view-product-image">
                                <input type="checkbox" id="view-product-image" name="view_product_image"<?php echo ( $options['view_product_image'] == 'on' ) ? ' checked="checked"' : ''; ?> />
                                <?php _e( 'Display images of products', 'lomadeewpro' ); ?>
                            </label><br />
                            <label for="view-product-names">
                                <input type="checkbox" id="view-product-names" name="view_product_names"<?php echo ( $options['view_product_names'] == 'on' ) ? ' checked="checked"' : ''; ?> />
                                <?php _e( 'Display product names', 'lomadeewpro' ); ?>
                            </label><br />
                            <label for="view-product-values">
                                <input type="checkbox" id="view-product-values" name="view_product_values"<?php echo ( $options['view_product_values'] == 'on' ) ? ' checked="checked"' : ''; ?> />
                                <?php _e( 'Display price of products', 'lomadeewpro' ); ?>
                            </label><br />
                            <label for="view-product-values-plots">
                                <input type="checkbox" id="view-product-values-plots" name="view_product_values_plots"<?php echo ( $options['view_product_values_plots'] == 'on' ) ? ' checked="checked"' : ''; ?> />
                                <?php _e( 'Display price plots of products', 'lomadeewpro' ); ?>
                            </label><br />
                            <label for="view-product-button-buy">
                                <input type="checkbox" id="view-product-button-buy" name="view_product_button_buy"<?php echo ( $options['view_product_button_buy'] == 'on' ) ? ' checked="checked"' : ''; ?> />
                                <?php _e( 'Display the buttons "Buy"', 'lomadeewpro' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="number-of-products"><?php _e('Number of products to display', 'lomadeewpro'); ?></label>
                    </th>
                    <td>
                        <select id="number-of-products" name="number_of_products" class="regular-text">
                            <?php for( $i=1; $i<11; $i++ ) : ?>
                            <option value="<?php echo $i; ?>"<?php echo ( $i == $options['number_of_products'] ) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="<?php _e('Save settings','lomadeewpro') ?> " class="button-primary" name="action" />
        </p>
    </form>
</div>
