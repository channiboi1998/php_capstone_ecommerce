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

    })


});