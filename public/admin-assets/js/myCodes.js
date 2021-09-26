/**
 *  uses for delete row and delete from database
 * @param  url  string = URL OF ROUTE LIKE : /admin/xxx/
 * @param cls string = THE CLASS NAME OF INPUT
 * @param msg  string = show msg after success.ONLY RELATED WORD
 * @returns {boolean}
 */
(function ($) {
    $.fn.simpleMoneyFormat = function() {
        this.each(function(index, el) {
            var elType = null; // input or other
            var value = null;
            // get value
            if($(el).is('input') || $(el).is('textarea')){
                value = $(el).val().replace(/,/g, '');
                elType = 'input';
            } else {
                value = $(el).text().replace(/,/g, '');
                elType = 'other';
            }
            // if value changes
            $(el).on('paste keyup', function(){
                value = $(el).val().replace(/,/g, '');
                formatElement(el, elType, value); // format element
            });
            formatElement(el, elType, value); // format element
        });
        function formatElement(el, elType, value){
            var result = '';
            var valueArray = value.split('');
            var resultArray = [];
            var counter = 0;
            var temp = '';
            for (var i = valueArray.length - 1; i >= 0; i--) {
                temp += valueArray[i];
                counter++
                if(counter == 3){
                    resultArray.push(temp);
                    counter = 0;
                    temp = '';
                }
            };
            if(counter > 0){
                resultArray.push(temp);
            }
            for (var i = resultArray.length - 1; i >= 0; i--) {
                var resTemp = resultArray[i].split('');
                for (var j = resTemp.length - 1; j >= 0; j--) {
                    result += resTemp[j];
                };
                if(i > 0){
                    result += ','
                }
            };
            if(elType == 'input'){
                $(el).val(result);
            } else {
                $(el).empty().text(result);
            }
        }
    };
}(jQuery));

function deleteAjax(url, cls, msg = '') {
    $("." + cls).on('click', function (e) {
        e.preventDefault();

        var obj = $(this); // first store $(this) in obj
        var id = $(this).data("id");
        
        Swal.fire({
            title: 'Bạn chắc chắn muốn xóa bản ghi này?',
            text: "Bản ghi sẽ bị xóa vĩnh viễn!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url+"/"+id,
                    method: "post",
                    dataType: "Json",
                    data: {
                        id: id,
                    },
                    // contentType: false,
                    // // cache: false,
                    

                    // processData: false,
                    success: function (results) {
                        // console.log(results.success);

                        if (results.status == 1 || results.success==true) {
                            Swal.fire(
                                results.message,
                                '',
                                'success'
                            )
                            Swal.fire(
                                'Bạn Đã Xóa Thành Công',
                                '',
                                'success'

                            );
                            $(obj).closest("tr").remove(); //delete row
                        } else {
                            Swal.fire(
                                'Đãa có lỗi xảy ra!!!',
                                '<h3>' + results.message + '</h3>',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        Swal.fire(
                            'Đã scó lỗi xảy ra!!!',
                            '<h3>' + xhr.responseText.message + '</h3>',
                            'error'
                        );
                    }
                });
            }
        })

        //        if (!confirm('ARE YOU SURE TO DELETE IT?')) {
        //            return false
        //        }
    });
}


/**
 * USE FOR SAVE REQUESTS WITH AJAX + validate it
 * @param url string = URL OF ROUTE
 * @param data array = DATA TO PASS
 * @param formId/null int = FROM ID FOR VALIDATION
 * @param rules/null  array = RULES FOR VALIDATION
 * @param msg/''  string = show msg after success
 * @returns {boolean}
 */

function upload_ajax(url, data, formId = null, rules = null, msg) {
    var bool = false;

    if (formId) {
        var $form = $('#' + formId);
        //add phone validation
        jQuery.validator.addMethod("phone", function (phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 10 &&
                phone_number.match(/^\+[0-9]{12}$/);
        }, "Please specify a valid phone number");
        //add post code validation
        jQuery.validator.addMethod("post_code", function (value, element) {
            return this.optional(element) || /^\d{10}(?:-\d{4})?$/.test(value);
        }, "Please provide a valid postal Code.");
        //add text only
        jQuery.validator.addMethod("lettersonly", function (value, element) {
            return this.optional(element) || /^[a-z," "]+$/i.test(value);
        }, "Letters and spaces only please");
        $form.validate({
            rules: rules,
            // message: msg,
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block`,"text-danger" class to the error element
                error.addClass("text-danger");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }

            },
            success: function (label, element) {
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $( '<span class="form-control-feedback icon icon-check"></span>' ).insertAfter( $( element ) );
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                $( element ).next( "span" ).addClass( "icon-clear" ).removeClass( "icon-check" );
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
                $( element ).next( "span" ).addClass( "icon-check" ).removeClass( "icon-clear" );
            }
        });

        //check if the input is valid
        if (!$form.valid()) return false;

    }

    $.ajaxSetup(
        {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        async: false,
        url: url,
        method: "POST",
        data: data,
        cache: false,
        beforeSend: function () {
            $(".preview").show();
        },
        success: function (result) {
            if (result.success === true){
                alert(result.message);
                bool = true;
                $(".preview").hide();
                return;
            }
            console.log(result);
            $(".preview").hide();
        },
        error: function (request, status, error) {
            var json = $.parseJSON(request.responseText);
            console.log(json);
            if (json.success === false){
                alert(json.message);
                $(".preview").hide();
                return
            }
            $(".preview").hide();
            $("#error_result").empty();
            $.each(json.errors, function (key, value) {
                $('.alert-danger').show().append('<p>' + value + '</p>');
            });
            $('html, body').animate(
                {
                    scrollTop: $("#error_result").offset().top,
                },
                500,
            );
            // alert('server not responding....' + json.message);
            console.log(error,request,status);
        }
    });

    return (bool);

}
