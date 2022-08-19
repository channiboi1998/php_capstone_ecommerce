$(document).ready(function() {

    /**
     * This JS Method is for the instantiation of the product gallery
     */
    $('.carousel.product-gallery').flickity({
        /**
         * Flickity Options
         */
        "groupCells": true, 
        "wrapAround": true, 
        "autoplay": 1500, 
        "prevNextButtons": false 

    });

    /**
     * This method is for the search bar when changed, submit the form automatically | Not AJAX yet because from the wireframe the url is visible
     */
    $(document).on('change', 'form.search-name #search_name', function() {

        $('form.search-name').attr('action', window.location.origin + '/products?search_name=' + $(this).val());
        $('form.search-name').submit();

    });


    /**
     * Add to cart form ajax
     */
    $(document).on('submit', 'form.add-to-cart', function() {

        $.post($(this).attr('action'), $(this).serialize(), function(res) {

            SnackBar({
                status: "success",
                width: "320px",
                position: "tc",
                message: "Item added to cart!",
                timeout: false,
            });

            refresh_cart_total();
            console.log('is working');

        });

        return false;

    });

    /**
     * Method for deleting cart item in session | On click on delete icon
     */
    $(document).on('click', '#table-cart-items .bi-trash', function() {
        
        $.get($(this).attr('data-url'), $(this).serialize(), function(res) {
            $('#table-cart-items').html(res);
            refresh_cart_total();
        });

    });


    function refresh_cart_total() {

        $.get(window.location.origin + '/orders/fetch_cart_total', $(this).serialize(), function(res) {
            $('#cart_count').text(res);
        });

    }

    refresh_cart_total();

    $(document).on('change', 'form.edit-cart-quantity input.quantity', function() {

        $(this).parent().submit();

    });

    $(document).on('submit', 'form.edit-cart-quantity', function() {

        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#table-cart-items').html(res);
            refresh_cart_total();
        });

        return false;

    });

    /**
     * Function to check if `same_as_billing` is checked for the checkout order form
     */
    function same_as_billing() {

        if($('input#same_as_billing').is(":checked")) {

            console.log('yep its checked');
            $('.checkout-cart-page div.billing-information input[type="text"]').addClass('disabled');

        } else {

            $('.checkout-cart-page div.billing-information input[type="text"]').removeClass('disabled');

        }

    }
    /**
     * Instantiate the same_as_billing method for new page load
     */
    same_as_billing();


    /**
     * Everytime the `input#same_as_billing` on the checkout page is clicked, call the `same_as_billing` method
     */
    $(document).on('change', 'input#same_as_billing', function() {

        same_as_billing();

    });

    /**
     * Stripe JS
     */
     var $stripeForm = $(".form-validation");
     $('form.form-validation').bind('submit', function (e) {
         var $stripeForm = $(".form-validation"),
             inputSelector = ['input[type=email]', 'input[type=password]',
                 'input[type=text]', 'input[type=file]',
                 'textarea'
             ].join(', '),
             $inputs = $stripeForm.find('.required').find(inputSelector),
             $errorMessage = $stripeForm.find('div.error'),
             valid = true;
         $errorMessage.addClass('hide');
         $('.has-error').removeClass('has-error');
         $inputs.each(function (i, el) {
             var $input = $(el);
             if ($input.val() === '') {
                 $input.parent().addClass('has-error');
                 $errorMessage.removeClass('hide');
                 e.preventDefault();
             }
         });
         if (!$stripeForm.data('cc-on-file')) {
             e.preventDefault();
             Stripe.setPublishableKey($stripeForm.data('stripe-publishable-key'));
             Stripe.createToken({
                 number: $('.card-number').val(),
                 cvc: $('.card-cvc').val(),
                 exp_month: $('.card-expiry-month').val(),
                 exp_year: $('.card-expiry-year').val()
             }, stripeResponseHandler);
         }
     });
     function stripeResponseHandler(status, res) {
         if (res.error) {
             $('.error')
                 .removeClass('hide')
                 .find('.alert')
                 .text(res.error.message);
         } else {
             var token = res['id'];
             $stripeForm.find('input[type=text]').empty();
             $stripeForm.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
             $stripeForm.get(0).submit();
         }
     }

});