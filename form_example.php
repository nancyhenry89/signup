<?php
if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
    $signup_ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
}else{
    $signup_ip = '';
    $country_code = '';
}
    
?>
<!DOCTYPE html>
<html>
<head>
  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
</head>
<body>
    <form id="formoid" action="register.php" title="" method="post">
        <div style="display:none">
            <input type="text" id="signup_ip" name="signup_ip" value="<?=$signup_ip; ?>">
            <input type="text" id="account_manager_id" name="account_manager_id" value="<?php if(isset($_GET['account_manager_id'])){echo $_GET['account_manager_id'];}?>">
            <input type="text" id="refferal" name="refferal" value="<?php if(isset($_GET['refferal'])){echo $_GET['refferal'];} ?>">
            <input type="text" id="country" name="country" value="<?=$country_code; ?>">
        </div>
        <div>
            <label class="title">company</label>
            <input type="text" id="company" name="company" >
        </div>
        <div>
            <label class="title">first_name</label>
            <input type="text" id="first_name" name="first_name" >
        </div>
        <div>
            <label class="title">last_name</label>
            <input type="text" id="last_name" name="last_name" >
        </div>
        <div>
            <label class="title">email</label>
            <input type="text" id="email" name="email" >
        </div>
        <div>
            <label class="title">password</label>
            <input type="text" id="password" name="password" >
        </div>
        <div>
            <label class="title">password_confirmation</label>
            <input type="text" id="password_confirmation" name="password_confirmation" >
        </div>
        <div>
            <label class="title">skype</label>
            <input type="text" id="skype" name="skype" >
        </div>
        <div>
            <input type="submit" id="submitButton"  name="submitButton" value="Submit">
        </div>
 </form>
<script type='text/javascript'>
    /* attach a submit handler to the form */
    $("#formoid").submit(function(event) {

      /* stop form from submitting normally */
      event.preventDefault();

      /* get the action attribute from the <form action=""> element */
      var $form = $( this ),
          url = $form.attr( 'action' );

      /* Send the data using post with element id name and name2*/
      var posting = $.post( url, {
          company: $('#company').val(),
          country: $('#country').val(), 
          address1: $('#address1').val(),
          address2: $('#address2').val(),
          region: $('#region').val(),
          zipcode: $('#zipcode').val(),
          phone: $('#phone').val(),
          title: $('#title').val(),
          first_name: $('#first_name').val(),
          last_name: $('#last_name').val(),
          email: $('#email').val(),
          password: $('#password').val(),
          password_confirmation: $('#password_confirmation').val(),
          skype: $('#skype').val(),
          signup_ip: $('#signup_ip').val(),
          account_manager_id: $('#account_manager_id').val(),
          refferal: $('#refferal').val()
      } );
      /* Alerts the results */
      posting.done(function( data ) {
        console.log(data);
      });
    });
</script>

</body>
</html> 