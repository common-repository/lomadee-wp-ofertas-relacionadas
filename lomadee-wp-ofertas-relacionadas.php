<?php
/*
Plugin Name: Lomadee WP - Ofertas Relacionadas
Plugin URI: http://developer.buscape.com/blog/aplicativos/lomadee-wp-ofertas-relacionadas/
Description: Exiba ofertas relacionas aos seus posts e aumente seu faturamento com publicidade. <a href="http://www.lomadee.com" title="Lomadee">Lomadee</a>
Author: Equipe Lomadee - com consultoria especializada Apiki
Version: 1.2
Author URI: http://www.lomadee.com/
*/

// Avoid to load this file directly
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) and ( __FILE__ == basename( $_SERVER['SCRIPT_FILENAME'] ) ) )
    exit();

class Lomadee_Wp_Related_Offers{

    /**
     * Capability name
     *
     * @var string
     */
    private $_capability = 'manager_lomadee_wp_ro';

    /**
     * Option name
     *
     * @var string
     */
    private $_option_name = 'lomadeewp_ro_option';

    /**
     * Application ID oficial of the author Apiki WordPress
     *
     * @var string
     */
    private $_application_id = '6f7074624f3134735179673d';

    /**
     * Image URL used when a product not have an image
     *
     * @var string
     */
    private $_url_no_image = ' http://imagem.buscape.com.br/bp5/imagemn.gif';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action( 'init', array( &$this, 'textdomain') );
        add_action( 'init', array( &$this, 'include_wrapper_php' ) );
        add_action( 'init', array( &$this, 'add_button_editor' ) );
        add_action( 'admin_menu', array( &$this, 'menu' ) );
        add_action( 'admin_head', array( &$this, 'add_lomadee_image' ) );
        add_action( "wp_print_styles", array( &$this, 'css' ) );
        add_action( 'admin_notices', array( &$this, 'settings_alert' ) );
        add_action( 'activate_lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas.php', array( &$this ,'install' ) );

        // If standard view is automatic, display the products in content automatically
        if( $this->_is_automatic() and $this->_source_id_exists() )
            add_action( 'the_content', array( &$this, 'display_related_offers_automatically' ) );

        add_shortcode('lomadeewpro', array( &$this, 'shortcode' ) );
    }

    /**
     * Install this plugin creating capability and options
     */
    public function install()
    {
        // Se a cURL do PHP estiver desabilitada o plugin não se instalará
        if( !function_exists( 'curl_init') ) :
            $message  = __( "<h1>Lomadee WP - Ofertas Relacionadas</h1>", 'lomadeewpro' );
            $message .= __( '<div id="message" class="error"><p>This plugin requires that PHP cURL is enabled. Learn more about <a href="http://www.php.net/manual/pt_BR/book.curl.php">cURL</a> and contact your hosting service.</p></div>', 'lomadeewpro' );
            deactivate_plugins('lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas.php');
            wp_die( $message );
            exit;
        endif;
        
        $this->_create_capability();
        $this->_create_options();
    }

    /**
     * Create menu
     */
    public function menu()
    {
        if ( function_exists( 'add_menu_page' ) )
            add_menu_page(
                __('Lomadee WP Ofertas Relacionadas','lomadeewpro'),
                __('Lomadee Ofertas','lomadeewpro'),
                $this->_capability,
                'lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-settings.php',
                null,
                $this->get_icon_menu()
            );
    }

    /**
     * Load i18n
     */
    public function textdomain()
    {
        load_plugin_textdomain( 'lomadeewpro', false , '/lomadee-wp-ofertas-relacionadas/languages/' );
    }

    /**
     * Include the wrapper php Apiki_BuscaPe_API
     */
    public function include_wrapper_php()
    {
        include_once WP_PLUGIN_DIR . '/lomadee-wp-ofertas-relacionadas/includes/apiki_buscape_api/Apiki_Buscape_API.php';
    }

    /**
     * Include CSS
     */
    public function css()
    {
        wp_enqueue_style( 'lomadeewp-ro', WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/assets/css/style.css' );
    }

    /**
     * Add the Lomadee plugin image
     */
    public function add_lomadee_image()
    {
        ?><style>
            .desc a[title=Lomadee] {
                background: url('<?php echo WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/assets/images/lomadee-plugin.png';?>') no-repeat transparent;
                display: block !important;
                text-indent: -99999px;
                height: 38px;
            }
        </style><?php
    }

    /**
     * Read the shortcode and call function to show products manually
     *
     * @param array $atts
     * @return string Related products
     */
    public function shortcode( $atts )
    {
        extract($atts);

        return $this->display_related_offers_manually( $category, $keywords );
    }

    /**
     * Add button in editor of posts and pages
     */
    public function add_button_editor()
    {
        add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin'), 5 );
	add_filter( 'mce_buttons', array( &$this, 'register_button') , 5 );
    }

    /**
     * Add plugin tinymce for show button in editor
     *
     * @param array $plugin_array
     * @return string
     */
    public function add_tinymce_plugin( $plugin_array )
    {
        $plugin_array['lomadeewpro'] = WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/assets/js/editor_plugin.js';
        return $plugin_array;
    }

    /**
     * Register button of this plugin in editor
     *
     * @param array $buttons
     * @return array
     */
    public function register_button( $buttons )
    {
        array_push( $buttons, "separator", "lomadeewpro");
        return $buttons;
    }

    /**
     * Show alert if the source id is empty
     */
    public function settings_alert()
    {
        $is_page_settings   = ( strpos( esc_url( $_SERVER['REQUEST_URI'] ), 'lomadee-wp-ofertas-relacionadas-settings') === false ) ? false : true;
        $settings_page      = admin_url( 'admin.php?page=lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-settings.php' );
        $settings           = $this->get_settings();
        $source_id          = ( isset( $_POST['source_id'] ) ) ? $_POST['source_id'] : $settings['source_id'];
        $message_test       = $this->source_id_test( $source_id );

        if( empty( $message_test ) )
            return;

        $alert = ( $message_test ) == __('Source ID is invalid!') ? 'error' : 'updated';

        if( !$is_page_settings )
            printf( '<div class="%s"><p>%s</p></div>', $alert, sprintf( __( 'Lomadee WP - Ofertas Relacionadas: <strong>%s</strong> Go to <a href="%s">Settings page</a> to configure it.', 'lomadeewpro' ), $message_test, $settings_page ) );
        elseif( $is_page_settings )
            printf( '<div class="%s"><p>%s</p></div>', $alert, sprintf( __( 'Lomadee WP - Ofertas Relacionadas: <strong>%s</strong>', 'lomadeewpro' ), $message_test ) );
    }

    /**
     * Get countries supported of BuscaPé API
     *
     * @return array
     */
    public function get_countries()
    {
        return array(
            'BR' => __('Brazil','lomadeewpro'),
            'AR' => __('Argentina','lomadeewpro'),
            'CL' => __('Chile','lomadeewpro'),
            'CO' => __('Colombia','lomadeewpro'),
            'MX' => __('Mexico','lomadeewpro'),
            'PE' => __('Peru','lomadeewpro'),
            'VE' => __('Venezuela','lomadeewpro')
        );
    }

    /**
     * Get countries data with internacionalization according with country code
     *
     * @return array
     */
    public function get_countries_data_i18n()
    {
        return array(
            'BR' => array(
                'currency'  =>  'R$',
                'buy'       =>  'Comprar',
                'cash'      =>  'à vista'
            ),
            'AR' => array(
                'currency'  =>  '$',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            ),
            'CL' => array(
                'currency'  =>  '$',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            ),
            'CO' => array(
                'currency'  =>  '$',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            ),
            'MX' => array(
                'currency'  =>  '$',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            ),
            'PE' => array(
                'currency'  =>  'S/.',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            ),
            'VE' => array(
                'currency'  =>  'Bs.F',
                'buy'       =>  'Comprar',
                'cash'      =>  'al contado'
            )
        );
    }

    /**
     * Get country currency string internacionalization according with country code
     *
     * @param string $country
     * @return string
     */
    public function get_country_currency_i18n()
    {
        $countries_i18n = $this->get_countries_data_i18n();
        $wrapper_php = $this->_instance_apiki_buscape_api();
        $countryCode = $wrapper_php->getCountryCode();

        return $countries_i18n[$countryCode]['currency'];
    }

    /**
     * Get country buy string internacionalization according with country code
     *
     * @param string $country
     * @return string
     */
    public function get_country_buy_i18n()
    {
        $countries_i18n = $this->get_countries_data_i18n();
        $wrapper_php = $this->_instance_apiki_buscape_api();
        $countryCode = $wrapper_php->getCountryCode();

        return $countries_i18n[$countryCode]['buy'];
    }

    /**
     * Get country cash string internacionalization according with country code
     *
     * @param string $country
     * @return string
     */
    public function get_country_cash_i18n()
    {
        $countries_i18n = $this->get_countries_data_i18n();
        $wrapper_php = $this->_instance_apiki_buscape_api();
        $countryCode = $wrapper_php->getCountryCode();

        return $countries_i18n[$countryCode]['cash'];
    }

    /**
     * Get icon menu URL
     *
     * @return string Image url
     */
    public function get_icon_menu()
    {
        return WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/assets/images/lomadee-icon-menu.png';
    }

    /**
     * Get icon URL manager
     *
     * @return string Image url
     */
    public function get_icon_manager()
    {
        return WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/assets/images/lomadee-icon-manager.png';
    }

    /**
     * Get settings defined in options page of this plugin
     *
     * @return array
     */
    public function get_settings()
    {
        return get_option( $this->_option_name );
    }

    /**
     * Get categories in BuscaPé API
     *
     * @return array
     */
    public function get_categories()
    {
        $wrapper_php = $this->_instance_apiki_buscape_api();
        $category_list = json_decode( $wrapper_php->findCategoryList(), true );

        foreach( (array)$category_list['top5category'] as $category )
            $arr_category_list[ $category['top5category']['id'] ] = $category['top5category']['name'];

        return $arr_category_list;
    }

    /**
     * Update settings
     *
     * @return bool
     */
    public function update_settings()
    {
        if( !isset( $_POST ) )
            return;

        extract( $_POST, EXTR_SKIP );

        $settings = array(
            'source_id'             => $source_id,
            'country_code'          => $country_code,
            'standard_view'         => $standard_view,
            'number_of_products'    => $number_of_products,
            'view_product_names'        => $view_product_names,
            'view_product_values'       => $view_product_values,
            'view_product_values_plots' => $view_product_values_plots,
            'view_product_button_buy'   => $view_product_button_buy,
            'view_product_image'        => $view_product_image
        );

        return update_option( $this->_option_name, $settings );
    }

    /**
     * Display related products automatically using tags like keywords
     *
     * @param string $content
     * @return string
     */
    public function display_related_offers_automatically( $content )
    {
        $tags = strip_tags( get_the_tag_list('', ',') );

        if( empty( $tags ) )
            return $content;

        $wrapper_php = $this->_instance_apiki_buscape_api();

        $_products_list = $wrapper_php->findOfferList( array( 'keyword' => $tags ) );
        $product_list   = ( !empty( $_products_list ) ) ? json_decode( $_products_list, true ) : '';
        
        if( !$this->_is_integration_valid( $product_list ) )
            return;
        
        $products = $this->_set_products_array_data( $product_list );

        return $content . $this->_output_products($products);
    }

    /**
     * Display related products manually using categories and keywords defined in
     * window box editor
     *
     * @param int $category
     * @param string $keywords
     * @return string
     */
    public function display_related_offers_manually( $category = '', $keywords = '' )
    {
        $wrapper_php = $this->_instance_apiki_buscape_api();

        $_keywords  = explode(',', $keywords );
        $keywords   = array();
        foreach( (array)$_keywords as $key )
            $keywords[] = trim($key);

        $keywords = implode(',', $keywords);

        $args = array();
        
        if( !empty( $category ) )
            $args['categoryId'] = $category;

        if( !empty( $keywords ) )
            $args['keyword'] = $keywords;

        $_product_list = $wrapper_php->findOfferList( $args );
        $product_list  = ( !empty( $_product_list ) ) ? json_decode( $_product_list, true ) : '';
        
        if( !$this->_is_integration_valid( $product_list ) )
            return;
        
        $products = $this->_set_products_array_data($product_list);

        return $this->_output_products($products);
    }
    
    /**
     * Get offer link
     * 
     * @since 1.2
     * @param int $offer_id Offer ID
     * @return string Offer link 
     */
    public function get_offer_link( $offer_id )
    {
        $wrapper_php = $this->_instance_apiki_buscape_api();
        
        $service_name               = 'findOfferList';
        $service_param_offerId      = '?offerId=' . intval( $offer_id );
        $service_param_json         = '&format=json';
        $service_param_source_id    = '&sourceId=' . $wrapper_php->getSourceId();
        
        $url = sprintf( 'http://%s.buscape.com/service/%s/%s/%s/%s%s%s%s', $wrapper_php->getEnvironment(), $service_name, $wrapper_php->getApplicationId(), $wrapper_php->getCountryCode(), $service_param_offerId, $service_param_json, '', $service_param_source_id );
        
        if( $wrapper_php->getEnvironment() == 'bws' )
            $url = $url . '&clientIp=' . $this->_get_real_ip_addr();

        // Método limpa a URL requisitada à API do BuscaPé
        $url = esc_url_raw($url);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, ( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : "Mozilla/4.0" );
        $response = curl_exec($curl);
        curl_close($curl);
        
        $_offer_data    = ( !empty( $response ) ) ? json_decode( $response, true ) : '';
        $offer_data     = $this->_set_products_array_data( $_offer_data );
        
        return $offer_data[0]['link'];
    }

    /**
     * Test the source id informed
     *
     * @param string $source_id
     * @return string|void
     */
    public function source_id_test( $source_id )
    {
        if( empty( $source_id ) )
            return __( "The Source ID can't be empty!", 'lomadeewpro' );

        $is_page_settings = ( strpos( esc_url( $_SERVER['REQUEST_URI'] ), 'lomadee-wp-ofertas-relacionadas-settings') === false ) ? false : true;

        if( $is_page_settings ) :

            $wrapper_php = $this->_instance_apiki_buscape_api( '', $source_id );
            $string_returned = json_decode( $wrapper_php->findCategoryList(), true );

            if( !empty( $string_returned['details']['message'] ) and  ( $string_returned['details']['message'] == 'SourceId is invalid' ) )
                return __('Source ID is invalid!', 'lomadeewpro');

        endif;
    }

    /**
     * Check if this integration is valid
     *
     * @param array $return
     * @return boool
     */
    private function _is_integration_valid( $return )
    {
        if( !empty( $return ) and $return['details']['message'] == 'SourceId is invalid' )
            return false;

        return true;
    }

    /**
     * Create the capability
     */
    private function _create_capability()
    {
        $role = get_role('administrator');
        if( !$role->has_cap( $this->_capability ) )
            $role->add_cap($this->_capability );
    }

    /**
     * Create the options
     */
    private function _create_options()
    {
        $options = get_option( $this->_option_name );
        
        extract( $options, EXTR_SKIP );

        $buscapewp_rp_default = array(
            'source_id'                 => ( $source_id ) ? $source_id : '',
            'country_code'              => ( $country_code ) ? $country_code : 'BR',
            'standard_view'             => ( $standard_view ) ? $standard_view : 'automatic',
            'number_of_products'        => ( $number_of_products ) ? $number_of_products : 5,
            'view_product_names'        => ( $view_product_name ) ? $view_product_names : 'on',
            'view_product_values'       => ( $view_product_values ) ? $view_product_values : 'on',
            'view_product_values_plots' => ( $view_product_values_plots ) ? $view_product_values_plots : 'on',
            'view_product_button_buy'   => ( $view_product_button_buy ) ? $view_product_button_buy : 'on',
            'view_product_image'        => ( $view_product_image ) ? $view_product_image : 'on',
        );

        if( $options )
            update_option( $this->_option_name, $buscapewp_rp_default );
        else
            add_option( $this->_option_name, $buscapewp_rp_default );
    }

    /**
     * Check if the standard view defined is automatic
     *
     * @return bool
     */
    private function _is_automatic()
    {
        $option = $this->get_settings();
        if( $option['standard_view'] == 'automatic' )
            return true;

        return false;
    }

    /**
     * Check if the source id was informed
     *
     * @return bool
     */
    private function _source_id_exists()
    {
        $option = $this->get_settings();
        if( !empty( $option['source_id'] ) )
            return true;

        return false;
    }

    /**
     * Build structure of related products and show
     *
     * @param array $products
     * @return string
     */
    private function _output_products( $products )
    {
        if( empty( $products ) )
            return;

        $options = $this->get_settings();

        $output  = '';
        $output .= '<div class="lomadee-wp-related-offers">';
            $output .= '<div class="lomadee-wp-related-offers-header"><h6>' . __('Busca inteligente, compra consciente', 'lomadeewpro') . '</h6></div>';
            $output .= '<div class="lomadee-wp-related-offers-list">';

        foreach( (array)$products as $key => $product ) :
                $key++;
                $output .= ( $key%$options['number_of_products']==0 ) ? '<div class="lomadee-wp-related-offers-item lomadee-wp-related-offers-item-last">' : '<div class="lomadee-wp-related-offers-item">';
                if( $options['view_product_image'] ) :
                    $output .= '<div class="lomadee-wp-related-offers-item-image">';
                        $output .= sprintf('<a href="%s" title="%s" target="_blank">', WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-redirect.php?offer_id=' . $product['id'], $product['name'] );
                        $output .= sprintf('<img src="%s" width="100" height="100" alt="%s" />', $product['thumb'], $this->_format_product_name( $product['name'] ) );
                        $output .= '</a>';
                    $output .= '</div>';
                endif;
                    $output .= '<div class="lomadee-wp-related-offers-item-data">';
                    if( $options['view_product_names'] ) :
                        $output .= '<p class="lomadee-wp-related-offers-item-data-name">';
                            $output .= sprintf( '<a href="%s" title="%s" target="_blank">', WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-redirect.php?offer_id=' . $product['id'], $product['name'] );
                            $output .= $this->_format_product_name( $product['name'] );
                            $output .= '</a>';
                        $output .= '</p>';
                    endif;
                    if( $options['view_product_values'] or $options['view_product_values_plots'] ) :
                        $output .= '<p class="lomadee-wp-related-offers-item-data-pricev">';
                            $output .= sprintf( '<a href="%s" title="%s" target="_blank">', WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-redirect.php?offer_id=' . $product['id'], $product['name'] );
                            $output .= ( $options['view_product_values'] ) ? $this->_format_price( $product['price'] ) : '';
                            $output .= ( $options['view_product_values_plots'] ) ? '<span>' . $this->_format_parcel( $product['parcel'] ) . '</span>' : '';
                            $output .= '</a>';
                        $output .= '</p>';
                    endif;
                    $output .= '</div>';
                    if( $options['view_product_button_buy'] ) :
                    $output .= '<div class="lomadee-wp-related-offers-item-button">';
                        $output .= sprintf( '<a href="%s" title="" target="_blank">', WP_PLUGIN_URL . '/lomadee-wp-ofertas-relacionadas/lomadee-wp-ofertas-relacionadas-redirect.php?offer_id=' . $product['id'] );
                        $output .= $this->get_country_buy_i18n();
                        $output .= '</a>';
                    $output .= '</div>';
                    endif;
                $output .= '</div>';

        endforeach;

            $output .= '</div>';
            $output .= '<p class="lomadee-wp-related-offers-links"><a href="http://www.buscape-inc.com" title="BuscaPé">BuscaPé</a> | <a href="http://www.lomadee.com" title="Lomadee">Lomadee</a></p>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Format the product name
     *
     * @param string $name
     * @param int $words
     * @return string
     */
    private function _format_product_name( $name, $lenght = 4 )
    {
        $_words = explode( ' ', $name );
        foreach( (array)$_words as $word ) :
            if( trim( $word ) != '-' )
                $words[] = $word;
        endforeach;
        $words = array_slice( $words, 0, $lenght );
        $new_name = implode( ' ', $words );

        return $new_name;
    }

    /**
     * Format price to show
     *
     * @param float $price
     * @return string
     */
    private function _format_price( $price )
    {
        if( $price != 'Consulte' )
            return $this->get_country_currency_i18n () . ' ' . number_format( $price, 2, ',', '.' );

        return $price;

    }

    /**
     * Format parcel to show
     *
     * @param array $parcel
     * @param string $prefix
     * @return string
     */
    private function _format_parcel( $parcel, $prefix = '' )
    {
        if( !empty ( $parcel['number'] ) )
            return sprintf( '%s%dx %s', $prefix, $parcel['number'], $this->_format_price ( $parcel['value'] ) );

        return $this->get_country_cash_i18n();
    }

    /**
     * Structure the 5 products in an array
     *
     * @param array $product_list
     * @return array
     */
    private function _set_products_array_data( $product_list )
    {
        if( empty( $product_list['offer'] ) )
            return;

        foreach( (array)$product_list['offer'] as $product ) :
            
            $products[] = array(
                'id'    => $product['offer']['id'],    
                'name'  => $product['offer']['offername'],
                'thumb' => ( !empty( $product['offer']['thumbnail']['url'] ) ) ? $product['offer']['thumbnail']['url'] :  $this->_url_no_image,
                'link'  => $product['offer']['links'][0]['link']['url'],
                'price' => $product['offer']['price']['value'],
                'parcel'=> array(
                    'value' => ( !empty( $product['offer']['price']['parcel']['value'] ) ) ? $product['offer']['price']['parcel']['value'] : '',
                    'number'=> ( !empty( $product['offer']['price']['parcel']['number'] ) ) ? $product['offer']['price']['parcel']['number'] : ''
                )
            );
        endforeach;

        if( empty( $products ) )
            return;

        // Get the number of products option and return
        $option = $this->get_settings();
        $products = array_slice( (array)$products, 0, $option['number_of_products'] );

        return $products;
    }

    /**
     * Instanc the wrapper Apiki_BuscaPe_API
     *
     * @return Apiki_Buscape_API
     */
    private function _instance_apiki_buscape_api( $application_id = '', $source_id = '' )
    {
        $options = $this->get_settings();

        if( empty( $application_id ) )
            $application_id = $this->_application_id;

        if( empty( $source_id ) )
            $source_id = $options['source_id'];

        $wrapper_php = new Apiki_Buscape_API( $application_id, $source_id );
        $wrapper_php->setFormat('json');
        $wrapper_php->setCountryCode( $options['country_code'] );

        return $wrapper_php;
    }
    
    /**
     * Get real IP address
     * 
     * @since 1.2
     * @return type 
     */
    private function _get_real_ip_addr()
    {
        if( !empty( $_SERVER['HTTP_X_IP'] ) ) //to check ip is pass from BIG IP
            $ip = $_SERVER['HTTP_X_IP'];
        elseif( !empty($_SERVER['HTTP_CLIENT_IP'] ) ) //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
	
        return $ip;
    }
}

/**
 * Instance this plugin
 */
$plugin_lomadee_wp_related_offers = new Lomadee_Wp_Related_Offers();
?>