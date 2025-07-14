/**
 * script pour la vérification des enregistrements des utilisateurs
 */
$('#register-user').click(function()
{
    //1er: variable----2è:id en rouge----val pour récuperer la valeur du champ
var firstname = $('#firstname').val();
var lastname = $('#lastname').val();
var email = $('#email').val();
var password = $('#password').val();
//on ne déclare pas une variable avec tiret de 6
var password_confirm = $('#password-confirm').val();
var passwordLength = password.length;
var agreeTerms = $('#agreeTerms');

//console.log('yes'); pour vérifier que la fonction click fonctionne


if (firstname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(firstname))
    {
    $('#firstname').removeClass('is-invalid');
    $('#firstname').addClass('is-valid');
     $('#error-register-firstname').text("");
    //alert("working");

        if (lastname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(lastname))
        {
        $('#lastname').removeClass('is-invalid');
        $('#lastname').addClass('is-valid');
        $('#error-register-lastname').text("");

            if (email != "" && /^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(email))
                {
                    $('#email').removeClass('is-invalid');
                    $('#email').addClass('is-valid');
                    $('#error-register-email').text("");

                    if (passwordLength >=8)
                        {
                            $('#password').removeClass('is-invalid');
                            $('#password').addClass('is-valid');
                            $('#error-register-password').text("");

                            if (password == password_confirm)
                                {
                                     $('#password-confirm').removeClass('is-invalid');
                                    $('#password-confirm').addClass('is-valid');
                                    $('#error-register-password-confirm').text("");

                                    if (agreeTerms.is(':checked'))
                                        {
                                           $('#agreeTerms').removeClass('is-invalid');
                                            $('#error-register-agreeTerms').text("");

                                            //envoi du formulaire
                                            //alert('data-ok');

                                            var res = emailExistjs(email);
                                            /**
                                             * condition ternaire simple
                                             * (condition) ? vrai : fausse;
                                             *           ------
                                             *condition ternaire avec plusieurs instructions
                                             (condition) ? vraie (inst1, inst2) : fausse (inst1, inst2);
                                            */
                                            /* (res != "exist") ? $('#form-register').submit()
                                            : $('#email').addClass('is-invalid');
                                              $('#email').removeClass('is-valid');
                                              $('#error-register-email').text("This email address is already used"); */
                                            if (res != "exist")
                                                {
                                                    $('#form-register').submit();
                                                }
                                            else
                                                {
                                              $('#email').addClass('is-invalid');
                                              $('#email').removeClass('is-valid');
                                              $('#error-register-email').text("This email address is already used");

                                                }

                                        }
                                    else
                                        {
                                            $('#agreeTerms').addClass('is-invalid');
                                            $('#error-register-agreeTerms').text("You must agree our terms and conditions!");
                                        }
                                }
                            else
                                {
                                    $('#password-confirm').addClass('is-invalid');
                                    $('#password-confirm').removeClass('is-valid');
                                    $('#error-register-password-confirm').text("Your passwords must be identical!");
                                }
                        }
                    else
                        {
                            $('#password').addClass('is-invalid');
                            $('#password').removeClass('is-valid');
                            $('#error-register-password').text("You password must be at least 8 characters!");
                        }
                }
            else
                {
                    $('#email').addClass('is-invalid');
                    $('#email').removeClass('is-valid');
                    $('#error-register-email').text("You email address is not valid");
                }
        }
    else
        {
            $('#lastname').addClass('is-invalid');
            $('#lastname').removeClass('is-valid');
            $('#error-register-lastname').text("Last Name is not valid");
        }
    }
else
    {
    $('#firstname').addClass('is-invalid');//colorer en rouge pour montrer qu'il y a erreur
    $('#firstname').removeClass('is-valid');
    $('#error-register-firstname').text("First Name is not valid");
    }


});
//Evènement pour l'input termes et conditions
$('#agreeTerms').change(function () {
var agreeTerms = $('#agreeTerms');
if (agreeTerms.is(':checked'))
    {
        $('#agreeTerms').removeClass('is-invalid');
        $('#error-register-agreeTerms').text("");
    }
else
    {
         $('#error-register-agreeTerms').text("You must agree our terms and conditions!");
    }
});


function emailExistjs(email)
{

    var url = $('#email').attr('url-emailExist');
    var token = $('#email').attr('token');
    var res = "";

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            '_token':token,
            email:email
        },
        success:function(result){
            res = result.response;
        },
        async: false
    });

        return res;

}
