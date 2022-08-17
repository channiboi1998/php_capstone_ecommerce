$(document).ready(function() {


    /*******
     * JS private Method for preparing the hidden form when user is managing categories on create-product form
     */
    function prepare_hidden_form() {

        $html = '';
        $('.modal.fade.show input.update-category').each(function() {
            $html += '<form action="http://localhost/products/update_category/'+ $(this).attr('category-id') +'" class="hidden hidden-form-update-category">'; 
                $html += '<input type="text" name="category_name" id="'+ $(this).attr('category-id') +'" value="'+ $(this).val() +'" readonly="">';
            $html += '</form>';
        });
        $('.hidden-form.categories').html($html);   

    }
    prepare_hidden_form();


    $(document).on('click', '#add_new_product', function() {
        $.get($(this).attr('href'), $(this).serialize(), function(res) {
            $('#new_product').html(res);
            $('.add-button').click();
        });
        return false;
    });

    /*******
     * JS Method for deleting a category
     */
     $(document).on('click', '.delete-category', function() {

        var result = confirm("Are you sure you want to delete this category? If you proceed to delete, the other instances of this category on other products will be removed.");

        if (result == true) {

            /**
             * Had to do this trick so that whenevera user pre-selected a categories, and deleted a particular category, user won't have to re-populate their selected categories | Don't worry future me, because the category is still being deleted on the database
             */
            $(this).parent().remove();

            $.get($(this).attr('href'), $(this).serialize(), function(res) {
                prepare_hidden_form();              
            });
        }

        return false;

     });


    /*******
     * JS Method for updating each categories
     */
    $(document).on('change', 'input.update-category', function() {
        /**
         * If a category input value is changed by the user, find the specified field in the hidden UPDATE category form and pass the new values into it | Submit the hidden form 
         */
        prepare_hidden_form();
        var $input = "#" + $(this).attr('category-id');
        var parent = $('.hidden-form.categories').find($input).val($(this).val()).parent();
        
        /**
         * Had to do this so that whenever the user updates a category, it will add all of the pre-selected categories into the hidden form, so that user won't have to repopulate
         */
        $(parent).find('input[type="checkbox"]').remove();
        $('.category input[type="checkbox"]:checked').each(function() {
            var hidden_selected_category = '<input type="checkbox" name="categories[]" value="' + $(this).val() + '" checked>';
            $(hidden_selected_category).appendTo(parent);
        });

        //$(hidden_selected_categories).appendTo(parent);

        parent.submit();

    });

    $(document).on('submit', 'form.hidden-form-update-category', function() {

        /**
         * Once the UPDATE category hidden form is submitted, do the ajax
         */
        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('.category-list').html(res);
        });

        return false;
    });


    /**
     * Trigger focus once the update bootstrap icon is clicked
     */
    $(document).on('click', '.category .bi-pencil', function() {
        $(this).parent().find('.update-category').focus();
    });


    /*******
     * JS Method for creating new category
     */
     $(document).on('click', 'a.add-new-category', function() {

        $('form.hidden-form-new-category .hidden-new-category-name').val($('.modal.fade.show input.new-category-name').val());
        $('.modal.fade.show input.new-category-name').val('');

        /**
         * Had to do this so that whenever the user creates a new category, it will append all of the pre-selected categories into the hidden form, so that user wont have to repopulate
         */       
        $('form.hidden-form-new-category').find('input[type="checkbox"]').remove();

        $('.category input[type="checkbox"]:checked').each(function() {
            var hidden_selected_category = '<input type="checkbox" name="categories[]" value="' + $(this).val() + '" checked>';
            $(hidden_selected_category).appendTo('form.hidden-form-new-category');
        });        

        $('form.hidden-form-new-category').submit();

    });

    $(document).on('submit', 'form.hidden-form-new-category', function() {

        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('.category-list').html(res);
            prepare_hidden_form();          
        });

        return false;
        
    });


    /*******
     * JS Method AJAX for creating new product
     */
    $(document).on('submit', 'form.new-product', function() {
        
        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            $('#load-partial-add-new-product-modal').html(res);
            ajax_products_list_paginate_refresh();
        });
    
        return false;
    });


    /*******
     * JS For hidden upload image form | Had to use long-cut AJAX because short-cut AJAX is having issues with file upload
     */
    $(document).on('change', '.modal-body #upload-images', function() {
        var clonedInput = $(this).clone();
        $('form.upload-images').html(clonedInput);
        $('form.upload-images').submit();
    });

    $(document).on('submit', 'form.upload-images', function(e) {

        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
                $(res).appendTo(".product-images");
                $(".product-images").sortable();
            }
        });

    });


    /*******
     * JS For making the product-feature-image sortable
     */
    $(".product-images").sortable();

    /*******
     * JS Removing the uploaded product images when trying to create/edit a product
     */    
    $(document).on('click', '.product-images i.bi.bi-trash', function() {

        $(this).parent().parent().remove();

    });


    /*******
     * JS Removing the product on the product list
     */       
    $(document).on('click', 'a.delete-product', function() {

        var result = confirm("Are you sure you want to delete this product?");

        if (result == true) {

            $(this).parent().parent().remove();
            
            $.get($(this).attr('href'), $(this).serialize(), function() {
                //This method is to run the `delete` function on the controller | Delete producct via GET method
                ajax_products_list_paginate_refresh();
            });

        }

        return false;

    });

    $(document).on('click', 'a.edit-product', function() {

        $.get($(this).attr('href'), $(this).serialize(), function (res) {
            $('#edit_product').html(res);
            $('.edit-button').click();
            $(".product-images").sortable();
        });

        return false;

    });

    $(document).on('submit', 'form.edit-product', function() {

        $.post($(this).attr('action'), $(this).serialize(), function(res) {
            console.log(res);
            ajax_products_list_paginate_refresh();
        })

        return false;
    });


    function ajax_products_list_paginate_refresh() {

        var url = window.location.origin + '/products/ajax_products_list_paginate_refresh';
        $.get(url, $(this).serialize(), function(res) {

            $('#dynamic-products-list-paginate').html(res);

        });

    }

});