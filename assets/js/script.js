$(document).ready(function() {
    

    /*******************************************************************************************************************
     * JS Method for deleting a category
     */
     $(document).on('click', '.delete-category', function() {

        var result = confirm("Are you sure you want to delete this category? If you proceed to delete, the other instances of this category on other products will be removed.");
        if (result == true) {
            $.get($(this).attr('href'), $(this).serialize(), function(res) {
                $('#new_product').html(res);
            });
        }

        return false;

     });

    /*******************************************************************************************************************
     * JS Method for updating each categories
     */
    $(document).on('change', 'input.update-category', function() {
        /**
         * If a category input value is changed by the user, find the specified field in the hidden UPDATE category form and pass the new values into it | Submit the hidden form 
         */
        var $input = "." + $(this).attr('category-id');
        $('.hidden-form-update-category').find($input).val($(this).val()).parent().submit();
        
    });

    $(document).on('submit', 'form.hidden-form-update-category', function() {
        /**
         * Once the UPDATE category hidden form is submitted, do the ajax
         */
        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#new_product').html(res);
        });

        return false;
    });

    /**
     * Trigger focus once the update bootstrap icon is clicked
     */
    $(document).on('click', '.category .bi-pencil', function() {
        $(this).parent().find('.update-category').focus();
    });


    /*******************************************************************************************************************
     * JS Method for creating new category
     */
     $(document).on('click', 'a.add-new-category', function() {
        $('form.hidden-form-new-category .hidden-new-category-name').val($('input.new-category-name').val());
        $('form.hidden-form-new-category').submit();
    });

    $(document).on('submit', 'form.hidden-form-new-category', function() {

        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#new_product').html(res);
        });

        return false;
    });

    /*******************************************************************************************************************
     * JS Method AJAX for creating new product
     */
    $(document).on('submit', 'form.new_product', function() {
        
        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#new_product').html(res);
        });
    
        return false;
    })

});