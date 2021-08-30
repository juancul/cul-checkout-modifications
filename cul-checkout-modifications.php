<?php
/**
 * A plugin that adds collection information to the woocommerce subscription edit page
 *
 * @package cul-checkout-modifications
 *
 *
 * Plugin Name:       CUL - Modifications in checkout
 * Description:       Plugin that Remove checkout fields, adds checkout messages and shows content depending on the products in the cart (offer, monthly plans)
 * Version:           1.0
 * Author:            CUL
 */

//Find the offer related product in the active cart
function cul_find_offer_product_in_cart() {

    $products = WC()->cart->cart_contents;
    $cart_titles = '';
    foreach ($products as $product) {
        $cart_titles .= $product['data']->get_title();
    }

    if (strpos($cart_titles, 'Oferta por alquiler') !== false) {
        return true;
    }

    else {
      return false;
    }
  
}

function cul_find_marketplace_product_in_cart() {

    $products = WC()->cart->cart_contents;
    $category_titles = '';
    foreach ($products as $product) {
        $product_id = get_post_parent($product['data']->get_id())->ID;
        
        
        $term_list = json_encode(wp_get_post_terms($product_id,'product_cat',array('fields'=>'slugs')));
        $term_titles = "";
        $term_titles .= $term_list;
    }

    if (strpos($term_titles , 'rayco') !== false) {
        return true;
    }

    else {  
      return false;
    }
  
}

function is_juancul_admin() {
    $current_user = wp_get_current_user();

    if ($current_user->user_email == "juan+a@vivecul.com") {
        return true;
    }

    else {
      return false;
    }
    
}

/* Hide checkout fields for renewl payment*/

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields) {
    $cart_data = WC()->session->get('cart');
    $cart = $cart_data[array_key_first($cart_data)];
    if(isset($cart['subscription_renewal']) && isset($cart['subscription_renewal']['subscription_id'])) {
        //unset($fields['billing']);
        echo '<script>
                setTimeout(function(){ 
                    var address_1 = document.getElementById("billing_address_1").value.length;
                    var nhood = document.getElementById("billing_nhood").value.length;
                    var phone = document.getElementById("billing_phone").value.length;
                    var docid = document.getElementById("billing_docid").value.length;
            
                    if (address_1 != 0 && nhood != 0 && phone != 0 && docid != 0){
                      
                      document.getElementsByClassName("woocommerce-billing-fields__field-wrapper")[0].style.display = "none";              
                    }
                    }, 10);
                </script>

                <style> 
                    /*.woocommerce-billing-fields__field-wrapper { 
                        display:none;!important; 
                    }*/
                    .woocommerce-shipping-totals { 
                        display:none;!important; 
                    }
                    .woocommerce-additional-fields { 
                        display:none;!important; 
                    }
                    .woocommerce-billing-fields h3 { 
                        display:none;!important; 
                    }
                    .cart-notice { 
                        display:none;!important; 
                    }
                </style>';
    }
    else if (cul_find_offer_product_in_cart() === true) {
        $notice = '<div class="woocommerce-info">
                    <strong><span class="cart-notice" style="color: #a374dd">Este es un pago para oferta.</span></strong>
                </div>';
        echo $notice.' <script>
                setTimeout(function(){ 
                    var address_1 = document.getElementById("billing_address_1").value.length;
                    var nhood = document.getElementById("billing_nhood").value.length;
                    var phone = document.getElementById("billing_phone").value.length;
                    var docid = document.getElementById("billing_docid").value.length;
            
                    if (address_1 != 0 && nhood != 0 && phone != 0 && docid != 0){
                      
                      document.getElementsByClassName("woocommerce-billing-fields__field-wrapper")[0].style.display = "none";              
                    }
                    }, 10);
                </script>

                <style> 
                    /*.woocommerce-billing-fields__field-wrapper { 
                        display:none;!important; 
                    }*/
                    .woocommerce-shipping-totals { 
                        display:none;!important; 
                    }
                    .woocommerce-additional-fields { 
                        display:none;!important; 
                    }
                    .woocommerce-billing-fields h3 { 
                        display:none;!important; 
                    }
                    .plan-commitment { 
                        display:none;!important; 
                    }
                </style>';
    }
    //don't close rentals for juan+a@vivecul.com
    else if (is_juancul_admin() === true) {
        $notice = '<div class="woocommerce-info">
                    <strong><span class="cart-notice" style="color: #a374dd">Checkout solo para juan+a@vivecul.com.</span></strong>
                </div>';
        echo $notice;
    }

    else if (cul_find_marketplace_product_in_cart() === true) {
        $notice = '';
        echo $notice;
    }

    else {
       /* echo '<!-- Begin Inspectlet Asynchronous Code -->
            <script type="text/javascript">
            (function() {
            window.__insp = window.__insp || [];
            __insp.push(["wid", 1558197494]);
            var ldinsp = function(){
            if(typeof window.__inspld != "undefined") return; window.__inspld = 1; var insp = document.createElement("script"); insp.type = "text/javascript"; insp.async = true; insp.id = "inspsync"; insp.src = ("https:" == document.location.protocol ? "https" : "http") + "://cdn.inspectlet.com/inspectlet.js?wid=1558197494&r=" + Math.floor(new Date().getTime()/3600000); var x = document.getElementsByTagName("script")[0]; x.parentNode.insertBefore(insp, x); };
            setTimeout(ldinsp, 0);
            })();
            </script>
            <!-- End Inspectlet Asynchronous Code -->';*/
        //Close all rentals
        echo ' <style>
                    .mwb_upsell_offer_parent_wrapper { 
                        display:none;!important; 
                    }
                </style>
                <style> 
                    .woocommerce-billing-fields__field-wrapper { 
                        display:none;!important; 
                    }
                    .woocommerce-shipping-totals { 
                        display:none;!important; 
                    }
                    .woocommerce-additional-fields { 
                        display:none;!important; 
                    }
                    .woocommerce-billing-fields h3 { 
                        display:none;!important; 
                    }
                    .place-order { 
                        display:none;!important; 
                    }
                    .woocommerce-checkout-review-order { 
                        display:none;!important; 
                    }
                    .showlogin { 
                        display:none;!important; 
                    }
                    nsl-container { 
                        display:none;!important; 
                    }
                    #account_password_field { 
                        display:none;!important; 
                    }
                    .cart-rental-message { 
                        display:none;!important; 
                    }
                </style>
                <div class="woocommerce-error">
                    <span class="cart-notice" style="color: #ffffff">En este momento no estamos recibiendo solicitudes nuevas de alquiler. ¡Vuelve pronto.!</span>
                </div>';
    }
    return $fields;
}