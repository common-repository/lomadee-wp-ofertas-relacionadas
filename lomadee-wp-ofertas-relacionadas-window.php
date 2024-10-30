<?php
$wpconfig = realpath("../../../wp-config.php");
if( !file_exists( $wpconfig ) ) :
    echo "Could not found wp-config.php. Error in path :\n\n". $wpconfig ;
    die;
endif;

require_once($wpconfig);

global $wpdb;
global $plugin_lomadee_wp_related_offers;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php _e( 'Lomadee WP - Ofertas Relacionadas', 'lomadeewpro' ); ?></title>
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-content/plugins/lomadee-wp-ofertas-relacionadas/assets/js/tinymce.js?v="<?php echo filemtime ( get_option('siteurl') . '/wp-content/plugins/lomadee-wp-ofertas-relacionadas/assets/js/tinymce.js' ); ?>></script>
        <base target="_self" />
    </head>
    <body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
        <form name="lomadeewpro" action="#">
            <div class="tabs">
		<ul>
                    <li class="current" id="general_tab"><span><a onmousedown="return false;" href="javascript:mcTabs.displayTab('general_tab','general_panel');"><?php _e('Relate offers', 'lomadeewpro'); ?></a></span></li>
		</ul>
            </div>
            <div class="panel_wrapper">
                <div class="panel current" id="general_panel">
                    <table cellspacing="0" cellpadding="4" border="0">
                        <tbody>
                            <tr>
                                <td class="nowrap">
                                    <label for="lomadeewpro_category"><?php _e("Category", 'lomadeewpro'); ?></label>
                                </td>
                                <td>
                                    <select id="lomadeewpro_category" name="lomadeewpro_category" style="width: 300px">
                                        <option value=""></option>
                                        <?php
                                            $categories = $plugin_lomadee_wp_related_offers->get_categories();
                                            if( $categories ) :
                                                foreach( $categories as $category_id => $category ) : ?>
                                                    <option value="<?php echo $category_id; ?>"><?php echo $category; ?></option>
                                                <?php endforeach; ?>
                                            <?php else :?>
                                                <option value=""><?php _e('No category found.', 'lomadeewpro'); ?></option>
                                            <?php endif; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                               <td nowrap="nowrap"><label for="lomadeewpro_keywords"><?php _e("Keywords", 'lomadeewpro'); ?></label></td>
                               <td>
                                    <input type="text" id="lomadeewpro_keywords" name="lomadeewpro_keywords" style="width: 300px"/>
                               </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mceActionPanel">
                <div style="float: left">
                    <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'lomadeewpro'); ?>" onclick="tinyMCEPopup.close();" />
                </div>
                <div style="float: right">
                    <input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'lomadeewpro'); ?>" onclick="insertlomadeewprocode();" />
                </div>
            </div>
        </form>
    </body>
</html>