<?php


if ( ! class_exists( 'NMPED_Toggle' ) ) {
	/**
	 * Shortcode class.
	 *
	 * @package fusion-builder
	 * @since 1.0
	 */
	class NMPED_Toggle {

		/**
		 * Counter for accordians.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $accordian_counter = 1;

		/**
		 * Counter for collapsed items.
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $collapse_counter = 1;

		/**
		 * The ID of the collapsed item.
		 *
		 * @access private
		 * @since 1.0
		 * @var string
		 */
		private $collapse_id;

		/**
		 * Parent SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		private $parent_args;

		/**
		 * Child SC arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		private $child_args;

		/**
		 * Constructor.
		 *
		 * @access public
		 * @since 1.0
		 */
		public function __construct($args = array()) {
			if($args) {
				$this->child_args = $args['child_args'];
				$this->parent_args = $args['parent_args'];
				$this->accordion_counter = $args['accordion_counter'];
				$this->collapse_counter = $args['collapse_counter'];
				$this->collapse_id = $args['collapse_id'];
			}

		}

		/**
		 * Render the parent shortcode
		 *
		 * @param  array  $args    Shortcode parameters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		/*public function render_parent( $args, $content = '' ) {

			global $fusion_settings;

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'hide_on_mobile'    => fusion_builder_default_visibility( 'string' ),
					'divider_line'      => $fusion_settings->get( 'accordion_divider_line' ),
					'boxed_mode'        => ( '' !== $fusion_settings->get( 'accordion_boxed_mode' ) ) ? $fusion_settings->get( 'accordion_boxed_mode' ) : 'no',
					'border_size'       => intval( $fusion_settings->get( 'accordion_border_size' ) ) . 'px',
					'border_color'      => ( '' !== $fusion_settings->get( 'accordian_border_color' ) ) ? $fusion_settings->get( 'accordian_border_color' ) : '#cccccc',
					'background_color'  => ( '' !== $fusion_settings->get( 'accordian_background_color' ) ) ? $fusion_settings->get( 'accordian_background_color' ) : '#ffffff',
					'hover_color'       => ( '' !== $fusion_settings->get( 'accordian_hover_color' ) ) ? $fusion_settings->get( 'accordian_hover_color' ) : $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) ),
					'type'              => ( '' !== $fusion_settings->get( 'accordion_type' ) ) ? $fusion_settings->get( 'accordion_type' ) : 'accordions',
					'class'             => '',
					'id'                => '',
				), $args
			);

			$defaults['border_size'] = FusionBuilder::validate_shortcode_attr_value( $defaults['border_size'], 'px' );

			extract( $defaults );

			$this->parent_args = $defaults;

			$style_tag = $styles = '';

			if ( '1' == $this->parent_args['boxed_mode'] || 'yes' === $this->parent_args['boxed_mode'] ) {

				if ( ! empty( $this->parent_args['hover_color'] ) ) {
					$styles .= '#accordion-' . get_the_ID() . '-' . $this->accordian_counter . ' .fusion-panel:hover{ background-color: ' . $this->parent_args['hover_color'] . ' }';
				}

				$styles .= ' #accordion-' . get_the_ID() . '-' . $this->accordian_counter . ' .fusion-panel {';

				if ( ! empty( $this->parent_args['border_color'] ) ) {
					$styles .= ' border-color:' . $this->parent_args['border_color'] . ';';
				}

				if ( ! empty( $this->parent_args['border_size'] ) ) {
					$styles .= ' border-width:' . $this->parent_args['border_size'] . ';';
				}

				if ( ! empty( $this->parent_args['background_color'] ) ) {
					$styles .= ' background-color:' . $this->parent_args['background_color'] . ';';
				}
			}
			$styles .= ' }';

			if ( $styles ) {

				$style_tag = '<style type="text/css" scoped="scoped">' . $styles . '</style>';

			}

			$html = sprintf(
				'%s<div %s><div %s>%s</div></div>',
				$style_tag,
				FusionBuilder::attributes( 'toggle-shortcode' ),
				FusionBuilder::attributes( 'toggle-shortcode-panelgroup' ),
				do_shortcode( $content )
			);

			$this->accordian_counter++;

			return $html;

		}*/

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function attr() {

			$attr = fusion_builder_visibility_atts( $this->parent_args['hide_on_mobile'], array(
				'class' => 'accordian fusion-accordian',
			) );

			if ( $this->parent_args['class'] ) {
				$attr['class'] .= ' ' . $this->parent_args['class'];
			}

			if ( $this->parent_args['id'] ) {
				$attr['id'] = $this->parent_args['id'];
			}

			return $attr;

		}

		/**
		 * Builds the panel-group attributes.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function panelgroup_attr() {
			return array(
				'class' => 'panel-group',
				'id'    => 'accordion-' . get_the_ID() . '-' . $this->accordian_counter,
			);
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode parameters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		/*public function render_child( $args, $content = '' ) {

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'open'  => 'no',
					'title' => '',
				), $args
			);

			extract( $defaults );

			$this->child_args = $defaults;
			$this->child_args['toggle_class'] = '';

			if ( 'yes' === $open ) {
				$this->child_args['toggle_class'] = 'in';
			}

			$this->collapse_id = substr( md5( sprintf( 'collapse-%s-%s-%s', get_the_ID(), $this->accordian_counter, $this->collapse_counter ) ), 15 );

			$html = sprintf(
				'<div %s><div %s><h4 %s><a %s><div %s><i %s></i></div><div %s>%s</div></a></h4></div><div %s><div %s>%s</div></div></div>',
				FusionBuilder::attributes( 'toggle-shortcode-panel' ),
				FusionBuilder::attributes( 'panel-heading' ),
				FusionBuilder::attributes( 'panel-title toggle' ),
				FusionBuilder::attributes( 'toggle-shortcode-data-toggle' ),
				FusionBuilder::attributes( 'fusion-toggle-icon-wrapper' ),
				FusionBuilder::attributes( 'toggle-shortcode-fa-icon' ),
				FusionBuilder::attributes( 'fusion-toggle-heading' ),
				$title,
				FusionBuilder::attributes( 'toggle-shortcode-collapse' ),
				FusionBuilder::attributes( 'panel-body toggle-content' ),
				do_shortcode( $content )
			);

			$this->collapse_counter++;

			return $html;

		}*/

		/**
		 * Builds the panel attributes.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function panel_attr() {

			$attr = array(
				'class' => 'fusion-panel panel-default',
			);

			if ( '1' == $this->parent_args['boxed_mode'] || 'yes' === $this->parent_args['boxed_mode'] ) {
				$attr['class'] .= ' fusion-toggle-no-divider fusion-toggle-boxed-mode';
			} elseif ( '0' == $this->parent_args['divider_line'] || 'no' === $this->parent_args['divider_line'] ) {
				$attr['class'] .= ' fusion-toggle-no-divider';
			}

			return $attr;

		}

		/**
		 * Builds the font-awesome icon attributes.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function fa_icon_attr() {
			return array(
				'class' => 'fa-fusion-box',
			);
		}

		/**
		 * Builds the data-toggle attributes.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function data_toggle_attr() {

			$attr = array();

			if ( 'yes' === $this->child_args['open'] ) {
				$attr['class'] = 'active';
			}

			$attr['data-toggle'] = 'collapse';
			if ( 'toggles' !== $this->parent_args['type'] ) {
				$attr['data-parent'] = sprintf( '#accordion-%s-%s', get_the_ID(), $this->accordian_counter );
			}
			$attr['data-target'] = '#' . $this->collapse_id;
			$attr['href']        = '#' . $this->collapse_id;

			return $attr;

		}

		/**
		 * Builds the collapse attributes.
		 *
		 * @access public
		 * @since 1.0
		 * @return array
		 */
		public function collapse_attr() {
			return array(
				'id'    => $this->collapse_id,
				'class' => 'panel-collapse collapse ' . $this->child_args['toggle_class'],
			);
		}

		
	}
	$nmped_toggle = new NMPED_Toggle();
}
