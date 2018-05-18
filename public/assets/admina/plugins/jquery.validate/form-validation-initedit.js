!function($) {
    

    var FormValidator = function() {
        this.$signupForm = $("#signupForm");
    };

    //init
    FormValidator.prototype.init = function() {
        //validator plugin
        $.validator.setDefaults({
            submitHandler: function() { saveto() }
        });
        // validate signup form on keyup and submit
        this.$signupForm.validate({
            rules: {
                firstname: "required",
                lastname: "required",
                name: {
                    required: true,
                    minlength: 2
                },
                surname: {
                    required: true,
                    minlength: 2
                },
                father_name: {
                    required: true,
                    minlength: 2
                },
                phone: {
                    required: true,
                    minlength: 5
                },
                city: {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: {
                    required: "Пожалуйста введите имя",
                    minlength: "Должно состоять как минимум из 2 символов"
                },
                surname: {
                    required: "Пожалуйста введите фамилие",
                    minlength: "Должно состоять как минимум из 2 символов"
                },
                father_name: {
                    required: "Пожалуйста введите отчество",
                    minlength: "Должно состоять как минимум из 2 символов"
                },
                city: {
                    required: "Пожалуйста введите город",
                    minlength: "Должно состоять как минимум из 2 символов"
                },
                email: {
                    required: "Пожалуйста введите вашу почту",
                    email:"Пожалуйста, введите действительный адрес электронной почты"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                }
            }
        });

        // propose username by combining first- and lastname
        $("#username").focus(function() {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if(firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });

        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.click(function() {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });

    },
    //init
    $.FormValidator = new FormValidator, $.FormValidator.Constructor = FormValidator
}(window.jQuery),


//initializing 
function($) {
    
    $.FormValidator.init()
}(window.jQuery);