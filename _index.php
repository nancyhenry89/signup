<?php
session_start();
$token = md5(uniqid(microtime(), true));
$_SESSION['sec_token'] = $token;
?>


<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Boutique | Sign up</title>
    <meta name="description" content="Welcome to the Boutique Family">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f5f7">
    <meta name="application-name" content="Boutique"/>
    <meta name="msapplication-TileColor" content="#f7f5f7"/>
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="preload" href="css/style.css" as="style">
    <link rel="stylesheet" href="css/style.css">

    <script>
        if (!'fetch' in window)
            document.write('<script src="js/polyfill/fetch.js">');

        var redirect = false;
        <?php
        if (isset($_GET['network'])) {
            switch ($_GET['network']) {
                case "lotto":
                    echo "redirect = \"http://lotto.boutique\";";
                    break;
                case "affiliate":
                    echo "redirect = \"http://affiliate.boutique\";";
                    break;
                case "roi":
                    echo "redirect = \"http://roi.boutique\";";
                    break;
            }
        }
        
        echo 'var aff_id = "", roi_id = "";';
        if (isset($_GET['aff_manager'])) echo 'aff_id = '.$_GET['aff_manager'].';';
        if (isset($_GET['roi_manager'])) echo 'roi_id = '.$_GET['roi_manager'].';';

    ?>
            
    </script>

</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your 7 years old Internet Explorer</a> to improve your experience!</p>
<![endif]-->


<main>
<div class="msg-overlay"></div>
    <header>
        <figure></figure>
    </header>
    <section class="steps">
        <div class="step active first">
            <div class="step-number">1</div>
            <div class="step-label">Account Details</div>
        </div>
        <div class="step second">
            <div class="step-number ">2</div>
            <div class="step-label">Personal Details</div>
        </div>
    </section>
    <section>
        <form class="boutiqueSignUp" action="#" method="post">
            <div class="field-cont step-two">
            <label class="field-title">Company</label>
            <label>
            <input id="company" type="text" name="company" placeholder="" required>
            <p class="error">* please enter details </p>
            </label>
            </div>

        <div class="field-cont step-two">
            <label class="field-title">First Name</label>
            <label>
            <input id="fname" type="text" name="fname" placeholder="" required>
            <p class="error">* please enter details </p>
            </label>
        </div>

        <div class="field-cont step-two">
            <label class="field-title">Last Name</label>
            <label>
            <input id="lname" type="text" name="lname" placeholder="" required>
            <p class="error">* please enter details </p>
            </label>
        </div>
        <div class="field-cont step-one">
            <label class="field-title">Email</label>
            <label>
            <div class="email loading"></div>
            <input id="email" type="email" name="email" placeholder="" required>
            <p class="error">* email is not valid</p>
            <p class="server-error">* email already in use. Please use a different email.</p>
            </label>
         </div>
         <div class="field-cont step-one">
            <label class="field-title">Password</label>
            <label>
            <input id="pass" type="password" name="password" placeholder="" required>
            <p class="error">* please enter details </p>
            </label>
    </div>
    <div class="field-cont step-one">
            <label>
            <label class="field-title">Verify Password</label>
            <input id="confirm-pass" type="password" name="password_confirmation" placeholder="" required>
            <p class="error">* passwords don't match </p>
            </label>
    </div>
    <div class="field-cont step-two">
        <label class="field-title">Skype</label>
            <label>
            <input id="skype" type="text" name="skype" placeholder="">
            </label>
    </div>
    <div class="step-two">
        <div class="checkbox-list">
            <label class="checkbox-title">Vertical</label>
                 <label class="container">MMO
                    <input value="MMO" name="verticalCheck" type="checkbox" checked="checked">
                    <span class="checkmark"></span>
                </label>
                <label class="container">CFD
                    <input value="CFD"  name="verticalCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Casino
                    <input value="Casino" name="verticalCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Lotto
                    <input value="Lotto" name="verticalCheck"  type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Other
                    <input value="other" name="" type="checkbox" onclick="if (this.checked){ $('#otherVertical').show();}else{$('#otherVertical').hide();}">
                    <span class="checkmark"></span>
                </label>
                <textarea class="other" data="verticalCheck" id="otherVertical" name="otherVertical" style="display:none"></textarea>

            </div>
            <div class="checkbox-list">
            <label class="checkbox-title">Media type</label>
                 <label class="container">SEO
                    <input value="SEO" name="mediaCheck" type="checkbox" checked="checked">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Media
                    <input value="Media" name="mediaCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Email
                    <input value="Email" name="mediaCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Marketing
                    <input value="Marketing" name="mediaCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">PPC
                    <input value="PPC" name="mediaCheck" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label name="mediaCheck" class="container">FB
                    <input name="mediaCheck" value="FB" type="checkbox">
                    <span class="checkmark"></span>
                </label>
                <label class="container">Other
                    <input value="Other" name="" type="checkbox" onclick="if (this.checked){ $('#otherMedia').show();}else{$('#otherMedia').hide();}">
                    <span class="checkmark"></span>
                </label>
                <textarea class="other" data="mediaCheck" id="otherMedia" name="otherMedia" style="display:none"></textarea>
            </div>
        </div>
        </div>
    </div>
            <?php
                // hidden form values
                if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
                    $signup_ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
                    $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
                } else {
                    $signup_ip = '0';
                    $country_code = '0';
                }
            ?>
            <input type="hidden" src="verticalCheck" name="vertical" value="">
            <input type="hidden" src="mediaCheck" name="media_type" value="">
            <input type="hidden" name="signup_ip" value="<?=$signup_ip; ?>">
			<!--<input type="hidden" name="account_manager_id" value="<?php if(isset($_GET['account_manager_id'])) {echo $_GET['account_manager_id'];}?>">-->
            <input type="hidden" name="referral" value="<?php if(isset($_GET['referral'])){echo $_GET['referral'];} ?>">
            <input type="hidden" name="network" value="<?php if(isset($_GET['network'])){echo $_GET['network'];} ?>">
            <input type="hidden" name="country" value="<?=$country_code; ?>">
            <input type="hidden" name="stoken" value="<?=$_SESSION['sec_token']; ?>">
            <button id="cont" class="step-one" type="submit" value="Continue">Continue
            </button>
            <button class="step-two" id="subBtn" type="submit" value="Send">Send
            </button>

        </form>
        <div id="message"><span class="close" onclick="document.querySelector('#message');">&times;</span></div>
    </section>
    <footer>
        <div class="logos">
            <div><img src="img/logo-roi.png"/></div>
            <div><img src="img/logo-aff.png"/></div>
            <div><img src="img/logo-lotto.png"/></div>
        </div>
    </footer>
</main>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="js/ui.js?v=200"></script>
<script src="js/form.js?v=200"></script>
</body>
</html>



