<?php
/**
 * Button Component class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace HFG\Core\Components;

use HFG\Core\Settings\Config;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;
use Neve\Core\Styles\Dynamic_Selector;
use Neve_Pro\Core\Settings;

/**
 * Class SearchResponsive
 *
 * @package HFG\Core\Components
 */
class CartIcon extends Abstract_Component {

	const COMPONENT_ID    = 'header_cart_icon';
	const SIZE_ID         = 'icon_size';
	const COLOR_ID        = 'color';
	const HOVER_COLOR_ID  = 'hover_color';
	const ICON_SELECTOR   = 'icon_selector';
	const CART_TOTAL      = 'cart_total';
	const CART_LABEL      = 'cart_label';
	const CART_FOCUS      = 'cart_focus';
	const MINI_CART_STYLE = 'mini_cart_style';
	const AFTER_CART_HTML = 'after_cart_html';
	const LABEL_SIZE_ID   = 'label_size';

	/**
	 * Button constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Cart Icon', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 1 );
		$this->set_property( 'icon', 'store' );
		$this->set_property( 'default_selector', '.builder-item--' . $this->get_id() );
		$this->set_property( 'is_auto_width', true );
		$this->set_property(
			'default_padding_value',
			array(
				'mobile'       => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'tablet'       => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'desktop'      => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'mobile-unit'  => 'px',
				'tablet-unit'  => 'px',
				'desktop-unit' => 'px',
			)
		);

		if ( function_exists( 'do_blocks' ) ) {
			add_filter( 'neve_post_content', 'do_blocks' );
		}
		add_filter( 'neve_post_content', 'wptexturize' );
		add_filter( 'neve_post_content', 'convert_smilies' );
		add_filter( 'neve_post_content', 'convert_chars' );
		add_filter( 'neve_post_content', 'wpautop' );
		add_filter( 'neve_post_content', 'shortcode_unautop' );
		add_filter( 'neve_post_content', 'do_shortcode' );
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @return bool
	 * @since   1.0.1
	 * @access  public
	 */
	public function is_active() {
		return class_exists( 'WooCommerce', false );
	}

	/**
	 * Define settings for this component.
	 */
	public function add_settings() {

		do_action( 'nv_cart_icon_component_controls' );

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::SIZE_ID,
				'group'                 => $this->get_id(),
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'absint',
				'default'               => 15,
				'label'                 => __( 'Icon Size', 'neve' ),
				'type'                  => 'Neve\Customizer\Controls\React\Range',
				'options'               => [
					'input_attrs' => [
						'min'        => 10,
						'max'        => 100,
						'defaultVal' => 15,
					],
				],
				'live_refresh_selector' => $this->default_selector . ' .nv-cart svg',
				'live_refresh_css_prop' => array(
					'type'    => 'svg-icon-size',
					'default' => 15,
				),
				'section'               => $this->section,
				'conditional_header'    => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::COLOR_ID,
				'group'                 => self::COMPONENT_ID,
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'sanitize_hex_color',
				'label'                 => __( 'Color', 'neve' ),
				'type'                  => 'neve_color_control',
				'section'               => $this->section,
				'live_refresh_selector' => true,
				'live_refresh_css_prop' => [
					[
						'selector' => $this->default_selector . ' svg',
						'prop'     => 'fill',
						'fallback' => 'inherit',
					],
					[
						'selector' => $this->default_selector . ' .cart-icon-label',
						'prop'     => 'color',
						'fallback' => 'inherit',
					],
				],
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::HOVER_COLOR_ID,
				'group'                 => self::COMPONENT_ID,
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'sanitize_hex_color',
				'label'                 => __( 'Hover Color', 'neve' ),
				'type'                  => 'neve_color_control',
				'section'               => $this->section,
				'live_refresh_selector' => true,
				'live_refresh_css_prop' => [
					[
						'selector' => $this->default_selector . ':hover svg',
						'prop'     => 'fill',
						'fallback' => 'inherit',
					],
					[
						'selector' => $this->default_selector . ':hover .cart-icon-label',
						'prop'     => 'color',
						'fallback' => 'inherit',
					],
				],
			]
		);
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_style( array $css_array = array() ) {
		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ' .nv-cart svg',
			Dynamic_Selector::KEY_RULES    => [
				\Neve\Core\Settings\Config::CSS_PROP_WIDTH => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::SIZE_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::SIZE_ID ),
				],
				\Neve\Core\Settings\Config::CSS_PROP_HEIGHT => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::SIZE_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::SIZE_ID ),
				],
				\Neve\Core\Settings\Config::CSS_PROP_FILL_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::COLOR_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::COLOR_ID ),
				],
			],
		];

		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ' .cart-icon-label',
			Dynamic_Selector::KEY_RULES    => [
				\Neve\Core\Settings\Config::CSS_PROP_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::COLOR_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::COLOR_ID ),
				],
			],
		];

		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ':hover svg',
			Dynamic_Selector::KEY_RULES    => [
				\Neve\Core\Settings\Config::CSS_PROP_FILL_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::HOVER_COLOR_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::HOVER_COLOR_ID ),
				],
			],
		];

		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ':hover .cart-icon-label',
			Dynamic_Selector::KEY_RULES    => [
				\Neve\Core\Settings\Config::CSS_PROP_COLOR => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::HOVER_COLOR_ID,
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::HOVER_COLOR_ID ),
				],
			],
		];


		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $this->default_selector . ' .cart-icon-label',
			Dynamic_Selector::KEY_RULES    => [
				\Neve\Core\Settings\Config::CSS_PROP_FONT_SIZE => [
					Dynamic_Selector::META_KEY     => $this->get_id() . '_' . self::LABEL_SIZE_ID,
					Dynamic_Selector::META_SUFFIX  => 'px',
					Dynamic_Selector::META_DEFAULT => SettingsManager::get_instance()->get_default( $this->get_id() . '_' . self::LABEL_SIZE_ID ),
				],
			],
		];

		return parent::add_style( $css_array );
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'components/component-cart-icon' );
	}

	/**
	 * Check if pro features should load.
	 *
	 * @return bool
	 */
	static function should_load_pro_features() {
		if ( ! class_exists( '\Neve_Pro\Modules\Woocommerce_Booster\Customizer\Cart_Icon' ) ) {
			return false;
		}

		if ( ! apply_filters( 'nv_pro_woocommerce_booster_status', false ) || ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		return true;
	}
}
