/* global axiscomposer_admin_meta_boxes_pagebuilder, quicktags, QTags */
jQuery( function ( $ ) {

	// Field validation error tips
	$( document.body )
		.on( 'ac_add_error_tip', function( e, element, error_type ) {
			var offset = element.position();

			if ( element.parent().find( '.ac_error_tip' ).size() === 0 ) {
				element.after( '<div class="ac_error_tip ' + error_type + '">' + axiscomposer_admin_meta_boxes_pagebuilder[error_type] + '</div>' );
				element.parent().find( '.ac_error_tip' )
					.css( 'left', offset.left + element.width() - ( element.width() / 2 ) - ( $( '.ac_error_tip' ).width() / 2 ) )
					.css( 'top', offset.top + element.height() )
					.fadeIn( '100' );
			}
		})
		.on( 'ac_remove_error_tip', function( e, element, error_type ) {
			element.parent().find( '.ac_error_tip.' + error_type ).remove();
		})
		.on( 'click', function() {
			$( '.ac_error_tip' ).fadeOut( '100', function() { $( this ).remove(); } );
		})
		.on( 'blur', '.ac_input_class[type=text], .ac_input_id[type=text]', function() {
			$( '.ac_error_tip' ).fadeOut( '100', function() { $( this ).remove(); } );
		})
		.on( 'keyup change', '.ac_input_class[type=text], .ac_input_id[type=text]', function() {
			var value    = $( this ).val();
			var regex    = new RegExp( '[^a-zA-Z0-9-_]+', 'gi' );
			var newvalue = value.replace( regex, '' );

			if ( value !== newvalue ) {
				$( this ).val( newvalue );
				$( document.body ).triggerHandler( 'ac_add_error_tip', [ $( this ), 'i18n_css_class_id_error' ] );
			} else {
				$( document.body ).triggerHandler( 'ac_remove_error_tip', [ $( this ), 'i18n_css_class_id_error' ] );
			}
		});

	// Tooltips
	var tiptip_args = {
		'attribute' : 'data-tip',
		'fadeIn' : 50,
		'fadeOut' : 50,
		'delay' : 200
	};
	$( '.tips, .help_tip' ).tipTip( tiptip_args );

	// Tabs
	$( 'ul.ac-tabs' ).show();
	$( 'div.panel-wrap' ).each( function() {
		$( this ).find( 'div.panel:not(:first)' ).hide();
	});
	$( 'ul.ac-tabs a' ).click( function() {
		var panel_wrap = $( this ).closest( 'div.panel-wrap' );
		$( 'ul.ac-tabs li', panel_wrap ).removeClass( 'active' );
		$( this ).parent().addClass( 'active' );
		$( 'div.panel', panel_wrap ).hide();
		$( $( this ).attr( 'href' ) ).show();
		return false;
	});
	$( 'div.panel-wrap' ).each( function() {
		$( this ).find( 'ul.ac-tabs li' ).eq( 0 ).find( 'a' ).click();
	});

	// Date Picker
	$( document.body ).on( 'ac-init-datepickers', function() {
		$( '.date-picker-field, .date-picker' ).datepicker({
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			showButtonPanel: true
		});
	}).trigger( 'ac-init-datepickers' );

	// Auto resize WordPress editor
	$( document.body ).on( 'ac-init-wp-editor', function() {
		var $supports_editor_expand = ( 'editorExpand' in window && window.editorExpand !== null );
		if ( $supports_editor_expand && $( '#editor-expand-toggle' ).prop( 'checked' ) ) {
			window.editorExpand.off();
			window.editorExpand.on();
		}
	});

	// Enhanced Modal Elements
	$( document.body )

		.on( 'ac-enhanced-modal-elements-init', function() {

			// Select availability
			$( 'select.availability' ).change( function() {
				if ( $( this ).val() === 'specific' ) {
					$( this ).closest( 'tr' ).next( 'tr' ).show();
				} else {
					$( this ).closest( 'tr' ).next( 'tr' ).hide();
				}
			}).change();

			// Input availability
			$( 'input.availability' ).on( 'keyup change', function() {
				if ( $( this ).val() !== '' ) {
					$( this ).closest( 'tr' ).next( 'tr' ).show();
				} else {
					$( this ).closest( 'tr' ).next( 'tr' ).hide();
				}
			}).change();

			// TinyMCE Editor
			$( 'textarea.axiscomposer-tinymce' ).each( function() {
				var $el      = this.id,
					$this    = $( this ),
					parents  = $this.parents( '.wp-editor-wrap:eq(0)' ),
					textarea = parents.find( 'textarea.axiscomposer-tinymce' ),
					switcher = parents.find( '.wp-switch-editor' ).removeAttr( 'onclick' ),
					settings = {
						id: this.id,
						buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close'
					};

				// Fix Quick tags
				quicktags(settings);
				QTags._buttonsInit();

				// Modify behaviour for html editor
				switcher.bind( 'click', function() {
					var button = $( this );
					if ( button.is( '.switch-tmce' ) ) {
						parents.removeClass( 'html-active' ).addClass( 'tmce-active' );
						window.tinyMCE.execCommand( 'mceAddEditor', true, $el );
						window.tinyMCE.get( $el ).setContent( window.switchEditors.wpautop( textarea.val() ), { format: 'raw' } );
					} else {
						var value = textarea.val();
						if ( window.tinyMCE.get( $el ) ) {
							value = window.tinyMCE.get( $el ).getContent();
						}

						parents.removeClass( 'tmce-active' ).addClass( 'html-active' );
						window.tinyMCE.execCommand( 'mceRemoveEditor', true, $el );
						textarea.val( window.switchEditors._wp_Nop( value ) );
					}
				});

				// Activate the visual editor
				switcher.filter( '.switch-tmce' ).trigger( 'click' );

				// Trigger events
				$( document.body ).on( 'ac-enhanced-form-tinymce-update', function() {
					switcher.filter( '.switch-html' ).trigger( 'click' );
				})

				.on( 'ac-enhanced-form-tinymce-remove', function() {
					$( document.body ).trigger( 'ac-init-wp-editor' );
					window.tinyMCE.execCommand( 'mceRemoveEditor', true, $el );
				});
			});

			// Regular color pickers
			$( ':input.color-picker-field, :input.color-picker' ).filter( ':not(.enhanced)' ).each( function() {
				var colorpicker_args = {
					palettes: [ '#000000', '#ffffff', '#B02B2C', '#edae44', '#eeee22', '#83a846', '#7bb0e7', '#745f7e', '#5f8789', '#d65799', '#4ecac2' ]
				};

				$( this ).wpColorPicker( colorpicker_args ).addClass( 'enhanced' );
			});

			// Regular select boxes
			$( document.body ).trigger( 'ac-enhanced-select-init' );
		})

		// AxisComposer Backbone modal
		.on( 'ac_backbone_modal_before_update', function() {
			$( document.body ).trigger( 'ac-enhanced-form-tinymce-update' );
		})

		.on( 'ac_backbone_modal_before_remove', function() {
			$( document.body ).trigger( 'ac-enhanced-form-tinymce-remove' );
			$( ':input.color-picker-field, :input.color-picker' ).wpColorPicker( 'close' );
		})

		.trigger( 'ac-enhanced-modal-elements-init' );

	// Depedencies check
	$( document.body ).on( 'change', '.ac-enhanced-form input[type=hidden], .ac-enhanced-form input[type=text], .ac-enhanced-form input[type=checkbox], .ac-enhanced-form textarea, .ac-enhanced-form select, .ac-enhanced-form radio', function() {
		var current = $( this ),
			scope   = current.parents( '.ac-backbone-modal-content:eq(0)' );

		if ( ! scope.length ) {
			scope = $( document.body );
		}

		var element     = this.id.replace( /axiscomposerTB-/, '' ),
			dependent   = scope.find( '.field-container[data-check-element="' + element + '"]' ),
			is_hidden   = current.parents( '.field-container:eq(0)' ).is( '.axiscomposer-hidden' ),
			first_value = this.value;

		if ( current.is( 'input[type=checkbox]' ) && ! current.prop( 'checked') ) {
			first_value = '';
		}

		if ( ! dependent.length ) {
			return true;
		}

		dependent.each( function() {
			var	visible     = false,
				current     = $( this ),
				operator    = current.data( 'check-operator' ),
				final_value = current.data( 'check-value' ).toString();

			if ( ! is_hidden ) {
				switch( operator ) {
					case 'equals':
						visible = ( first_value === final_value ) ? true : false;
					break;

					case 'not':
						visible = ( first_value !== final_value ) ? true : false;
					break;

					case 'is_larger':
						visible = ( first_value > final_value ) ? true : false;
					break;

					case 'is_smaller':
						visible = ( first_value < final_value ) ? true : false;
					break;

					case 'contains':
						visible = ( first_value.indexOf( final_value ) !== -1 ) ? true : false;
					break;

					case 'doesnot_contain':
						visible = ( first_value.indexOf( final_value ) === -1 ) ? true : false;
					break;

					case 'is_empty_or':
						visible = ( ( first_value === '' ) || ( first_value === final_value ) ) ? true : false;
					break;

					case 'not_empty_and':
						visible = ( ( first_value !== '' ) || ( first_value !== final_value ) ) ? true : false;
					break;
				}
			}

			if ( visible === true && current.is( '.axiscomposer-hidden' ) ) {
				current.css({ display: 'none' }).removeClass( 'axiscomposer-hidden' ).find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
				current.slideDown();
			} else if ( visible === false && ! current.is( '.axiscomposer-hidden' ) ) {
				current.css({ display: 'block' }).addClass( 'axiscomposer-hidden' ).find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
				current.slideUp();
			}
		});
	});
});
