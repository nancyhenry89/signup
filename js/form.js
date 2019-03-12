function checkIfEmailExists() {
    document.querySelector('.email.loading').style.display = 'block';
    var email = document.querySelector('#email');
    fetch('./register.php?checkemail='+email.value, { credentials: "same-origin" } )
    .then (function (response) { 
            response.text().then(function(result) {
                document.querySelector('.email.loading').style.display = 'none';
                if (result=="-1") {
                    email.parentElement.classList.add("show-servererror");
                    email.classList.add("notValid"); 
                    //document.querySelector('.email-taken').style.display = 'block';
                }
                else {
                    email.parentElement.classList.remove("show-servererror");
                    email.classList.remove("notValid"); 
                    //document.querySelector('.email-taken').style.display = 'none';
                }
            });
    });
    return false;
}

function disableForm() {
    var form = document.querySelector('form.boutiqueSignUp');
    form.setAttribute('disabled','');
    [].forEach.call(form.querySelectorAll('form.boutiqueSignUp input'), function(input) {
        input.setAttribute('disabled','');
    });
    document.querySelector('#subBtn').setAttribute('disabled','');
}
function enableForm() {
    var form = document.querySelector('form.boutiqueSignUp');
    form.removeAttribute('disabled');
    [].forEach.call(form.querySelectorAll('form.boutiqueSignUp input'), function(input) {
        input.removeAttribute('disabled');
    });
    document.querySelector('#subBtn').removeAttribute('disabled');
}

function showMessage() {
    disableForm();
    var msg = document.querySelector('#message');
    msg.style.display = 'table';
    return msg; 
}
function dismissMessage() {
    enableForm();
    var msg = document.querySelector('#message');
    msg.style.display = 'none';
    msg.innerHTML = "";
}


// pass status as a DOM element
function registerResult(result,status) {
    //var status = document.querySelector('#roi-signup-status');
    status.classList.remove('inline-loading');
    if (result=='1') {
        status.classList.add('success');
        status.textContent = 'success.';
        return 1;
    }
    else {
        status.classList.add('failure');
        if (result=='-4') {
            status.textContent = 'Email already in use';
            $('.boutiqueSignUp').removeClass('two-active');
            $('.step').removeClass('active');
            $('.step.first').addClass('active');
        }
        else status.textContent = 'failure';
        return 0;
    }
}

function doRegister(api, manager_id, form, statusDOM ) {
    form.set ("account_manager_id", manager_id);
    console.log ("Form:",form);
    return new Promise( function (resolve,reject) {
        fetch("./register.php?api="+api+"&"+window.top.location.search.substr(1), { method: "POST", body: form, credentials: "same-origin" })
        .then( function(response) { 
            response.text().then(result=> { 
                resolve(registerResult(result, statusDOM));
            })
        })
        .catch(function(err) { alert ("There was an error with the request. Please try again later."); })
    });
}
document.querySelector('form.boutiqueSignUp').addEventListener(
    'submit',
    function(evt) {
        evt.preventDefault();
        if (document.querySelector('input.notValid')) return;
        var form = new FormData(this);
        var msg = showMessage();
        msg.innerHTML = 'Registering... <span id="roi-signup-status" class="signup-status inline-loading"></span><br>';
                        //'Registering you to Affiliate Boutique... <span id="aff-signup-status" class="signup-status inline-loading"></span>';


        if (window.top.location.search.indexOf("api=roi")>-1)
            doRegister("roi", aff_id, form, document.querySelector('#roi-signup-status')).then(handleRegisterResult);
        else
            doRegister("cake", aff_id, form, document.querySelector('#roi-signup-status')).then(handleRegisterResult);
        
                
            function handleRegisterResult(result) {
                if (result == "1") {
                    msg.innerHTML += "<div class='msg-cont'><div class='msg-icon'></div><span class='msg-title'>Thank you!</span> for registering to the <b>Boutique Family.</b> <br> We will get back to you soon.</div>";
                    if (redirect) {
                        msg.innerHTML += "<br>Back to  <a href=\""+redirect+"\">homepage</a>...";
                        setTimeout (function() {
                               window.top.location.href = redirect;
                        }, 10000);
                    }
                }
                else {
                    var close = document.createElement('span');
                    close.innerHTML = "&times;";
                    close.className = "close";
                    close.onclick = function() {
                        dismissMessage();
                        return false;
                    }
                    msg.appendChild(close);
                }
            }
    }
);
function formCheckboxArray(name){
    var arr=[];
    $('input[name="'+name+'"]').each(function(){
            if ($(this).is(':checked')) {
                arr.push($(this).attr('value'))
            }
    });
    arr.push($('textarea[data="'+name+'"]').val());
    array = arr + ""
    $('input[src='+name+']').val(array);
    return arr;
}
$('document').ready(function(){
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    $('#cont').click(function(){
        $('input[type="password"],input[type="email"]').each(function(){
            if( $(this).val().length === 0 ) {
                $(this).addClass('no');
                $(this).parents('label').addClass('show');
            }else{
                $(this).removeClass('no');
                $(this).parents('label').removeClass('show');

            }
            setTimeout(function(){
                if($('input').hasClass('no')){

                }else{
                $('.boutiqueSignUp').addClass('two-active');
                $('.step').removeClass('active');
                $('.step.second').addClass('active');
                }
            },1000)

        });
        
    });
    $('input[name="verticalCheck"]').change(
        function(){
            var verticalCheck=formCheckboxArray("verticalCheck");
            $('input[name="vertical"]').val(verticalCheck);
        });
    $('input[name="mediaCheck"]').change(
        function(){
               var mediaCheck=formCheckboxArray("mediaCheck");
               $('input[name="media_type"]').val(mediaCheck);
        });
        $('textarea[data="mediaCheck"]').on('change keyup paste', function(){
            var mediaCheck=formCheckboxArray("mediaCheck");
            $('input[name="media_type"]').val(mediaCheck);
        });
        $('textarea[data="verticalCheck"]').on('change keyup paste', function(){
            var verticalCheck=formCheckboxArray("verticalCheck");
            $('input[name="media_type"]').val(mediaCheck);
        });
});
