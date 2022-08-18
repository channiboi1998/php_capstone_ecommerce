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


});