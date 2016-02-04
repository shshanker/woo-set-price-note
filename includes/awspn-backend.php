<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Woo Set Price Note
 *
 * Allows user to get WooCommerce Set Price Note.
 *
 * @class   Woo_Set_Price_Note_Backend 
 */


class Woo_Set_Price_Note_Backend {

	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'Woo_Set_Price_Note_Backend';
		$this->method_title       = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );
		$this->method_description = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );

	
		// Actions
		// Display Fields
		add_action( 'woocommerce_product_options_pricing', array( $this, 'awspn_add_custom_general_field') );	
		
		// Save Fields
		add_action( 'woocommerce_process_product_meta', array( $this, 'awspn_add_custom_general_field_save') );

		
		// Filters
		// Add saved price note
		add_filter( 'woocommerce_get_price_html', array( $this, 'awspn_display_price_note'), 99, 2 );

		
	}

	
	/**
	 * Adding price note options on WooCommerce single product general section.
	 *
	 * @return void
	 */

	public static function awspn_add_custom_general_field() {
	 
	  
	  echo '<div class="options_group">';

			// Product per note separator 
			woocommerce_wp_text_input( 
				array( 
					'id'          => 'awspn_product_price_note_separator', 
					'label'       => __( 'Note Separator', 'woo-set-price-note' ), 
					'placeholder' => '/',
					'desc_tip'    => 'true',
					'description' => __( 'Enter separator between price and note, like, "/", "-", "per", etc', 'woo-set-price-note' ) 
				)
			);


			// Product per note text
			woocommerce_wp_text_input( 
				array( 
					'id'          => 'awspn_product_price_note', 
					'label'       => __( 'Price Note', 'woo-set-price-note' ), 
					'placeholder' => 'Piece',
					'desc_tip'    => 'true',
					'description' => __( 'Enter price note that you want to display with product price, like, Offer, Unit, Edition, etc.', 'woo-set-price-note' ) 
				)
			);


	  
	  echo '</div>';
		
	}


	/**
	 * Saving price note options on WooCommerce single product general section.
	 *
	 * @return void
	 */

	public static function awspn_add_custom_general_field_save( $post_id ){	
		
		
		$price_note_text = sanitize_text_field($_POST['awspn_product_price_note']);

		$price_note_separator = sanitize_text_field($_POST['awspn_product_price_note_separator']);

		// Product per note text
		if( !empty( $price_note_text ) ){

			update_post_meta( $post_id, 'awspn_product_price_note',  $price_note_text  );
		
		}

		// Product per note separator
		if( !empty( $price_note_separator )  ){			

			update_post_meta( $post_id, 'awspn_product_price_note_separator',  $price_note_separator  );
		
		}
		
	}

	/**
	 * Loading  price note options on WooCommerce product section.
	 *
	 * @return string
	 */


	public static function awspn_display_price_note( $price, $product ){
	    
	    $price_note_text = esc_attr( get_post_meta( $product->id, 'awspn_product_price_note', true ) ); 
	    $price_note_separator = esc_attr( get_post_meta( $product->id, 'awspn_product_price_note_separator', true ) ); 
	    
	   
	   
	   if( !empty( $price_note_text ) && isset( $price_note_text ) ){
			
	    	if( !empty( $price_note_separator ) && isset( $price_note_separator ) ){			
		    	
	    		return $price .'<span class="awspn_price_note" style="font-style:italic;"> '. $price_note_separator .' '. $price_note_text .'</span>';

			} else {

	    		return $price .'<span class="awspn_price_note" style="font-style:italic;"> / '. $price_note_text .'</span>';
			}

		} else {
	    	
	    	return $price;

		}
	}


}

$awspn_backend = new Woo_Set_Price_Note_Backend();