<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sayfası</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<?php
$merchant_id = '471561';
$merchant_key = 'QecQuDdJ251JLQH3';
$merchant_salt = 'Xcc1LLUpKsB8J7ze';

$merchant_ok_url = "http://localhost/Yeni%20klas%C3%B6r/basarili";
$merchant_fail_url = "http://localhost/Yeni%20klas%C3%B6r/basarisiz";

$user_basket = htmlentities(
    json_encode(
        array(
            array("Altis Renkli Deniz Yatağı - Mavi", "18.00", 1),
            array("Pharmasol Güneş Kremi 50+ Yetişkin & Bepanthol Cilt Bakım Kremi", "33,25", 2),
            array("Bestway Çocuklar İçin Plaj Seti Beach Set ÇANTADA DENİZ TOPU-BOT-KOLLUK", "45,42", 1)
        )
    )
);

srand(time());
$merchant_oid = rand();

$test_mode = "0";

//3d'siz işlem
$non_3d = "0";

//Ödeme süreci dil seçeneği tr veya en
$client_lang = "tr";

//non3d işlemde, başarısız işlemi test etmek için 1 gönderilir (test_mode ve non_3d değerleri 1 ise dikkate alınır!)
$non3d_test_failed = "0";

if (isset($_SERVER["HTTP_CLIENT_IP"])) {
    $ip = $_SERVER["HTTP_CLIENT_IP"];
} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
    $ip = $_SERVER["REMOTE_ADDR"];
}

$user_ip = "192.168.1.13";

$email = "testnon3d@paytr.com";

// 100.99 TL ödeme
$payment_amount = "100.99";
$currency = "TL";
//
$payment_type = "card";

$card_type = "bonus";       // Alabileceği değerler; advantage, axess, combo, bonus, cardfinans, maximum, paraf, world, saglamkart
$installment_count = "0";

$post_url = "https://www.paytr.com/odeme";

$hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $installment_count. $currency. $test_mode. $non_3d;
$token = base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
?>

<body>
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-6">Ödeme Formu</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <form id="paymentForm" action="<?php echo $post_url; ?>" method="post">
                            <div class="form-group">
                                <label for="cc_owner">
                                    <h6>Kart Sahibi</h6>
                                </label>
                                <input type="text" name="cc_owner" placeholder="Kart Sahibinin Adı" required
                                    class="form-control" maxlength="30" oninput="validateName(this)">
                                <small id="nameError" class="text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="card_number">
                                    <h6>Kart Numarası</h6>
                                </label>
                                <div class="input-group">
                                    <input type="text" name="card_number" placeholder="Kart Numarası"
                                        class="form-control" required maxlength="16" pattern="\d{16}"
                                        oninput="this.value = this.value.replace(/\D/g, ''); validateCardNumber(this);">
                                </div>
                                <small id="card_numberError" class="text-danger"></small>
                            </div>

                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>
                                            <h6>Son Kullanma Tarihi</h6>
                                        </label>
                                        <div class="input-group">
                                            <select id="expiry_month" name="expiry_month" class="form-control" required>
                                                <option value="">Ay</option>
                                                <option value="1">01</option>
                                                <option value="2">02</option>
                                                <option value="3">03</option>
                                                <option value="4">04</option>
                                                <option value="5">05</option>
                                                <option value="6">06</option>
                                                <option value="7">07</option>
                                                <option value="8">08</option>
                                                <option value="9">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                            <select id="expiry_year" name="expiry_year" class="form-control ml-2"
                                                required>
                                                <option value="">Yıl</option>
                                                <script>
                                                    const startYear = new Date().getFullYear();
                                                    const endYear = startYear + 20;
                                                    for (let year = startYear; year <= endYear; year++) {
                                                        const yearStr = year.toString().slice(-2);
                                                        document.write(`<option value="${yearStr}">${yearStr}</option>`);
                                                    }
                                                </script>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group mb-4">
                                        <label data-toggle="tooltip"
                                            title="Kartınızın arka yüzünde 3 rakam şeklinde bulunan güvenlik kodu.">
                                            <h6>CVV <i class="fa fa-question-circle d-inline"></i></h6>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="cvv" maxlength="3" class="form-control"
                                                id="cvv" oninput="validateCVV(this)" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" onclick="toggleCVVVisibility()">
                                                    <i class="fa fa-eye" id="toggleCVVIcon"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small id="cvvError" class="text-danger"></small>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>">
                            <input type="hidden" name="user_ip" value="<?php echo $user_ip; ?>">
                            <input type="hidden" name="merchant_oid" value="<?php echo $merchant_oid; ?>">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <input type="hidden" name="payment_type" value="<?php echo $payment_type; ?>">
                            <input type="hidden" name="payment_amount" value="<?php echo $payment_amount; ?>">
                            <input type="hidden" name="currency" value="<?php echo $currency; ?>">
                            <input type="hidden" name="test_mode" value="<?php echo $test_mode; ?>">
                            <input type="hidden" name="non_3d" value="<?php echo $non_3d; ?>">
                            <input type="hidden" name="merchant_ok_url" value="<?php echo $merchant_ok_url; ?>">
                            <input type="hidden" name="merchant_fail_url" value="<?php echo $merchant_fail_url; ?>">
                            <input type="hidden" name="user_name" value="Paytr Test">
                            <input type="hidden" name="user_address" value="test test test">
                            <input type="hidden" name="user_phone" value="05555555555">
                            <input type="hidden" name="user_basket" value="<?php echo $user_basket; ?>">
                            <input type="hidden" name="debug_on" value="1">
                            <input type="hidden" name="client_lang" value="<?php echo $client_lang; ?>">
                            <input type="hidden" name="paytr_token" value="<?php echo $token; ?>">
                            <input type="hidden" name="non3d_test_failed" value="<?php echo $non3d_test_failed; ?>">
                            <input type="text" name="installment_count" value="<?php echo $installment_count; ?>">
                            <input type="hidden" name="card_type" value="<?php echo $card_type; ?>">
                            <div class="card-footer">
                                <button type="submit" class="subscribe btn btn-info btn-block shadow-sm">
                                    Ödemeyi Tamamla
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function validateName(input) {
        const name = input.value.trim();
        const errorElement = document.getElementById('nameError');
        if (name === '') {
            errorElement.textContent = 'Kart sahibinin adı gereklidir.';
            return false;
        } else {
            errorElement.textContent = '';
            return true;
        }
    }

    function validateCardNumber(input) {
        const cardNumber = input.value.replace(/\s+/g, '');
        const errorElement = document.getElementById('card_numberError');

        if (cardNumber.length !== 16 || !luhnCheck(cardNumber)) {
            errorElement.textContent = 'Kart numarası geçersiz.';
            input.setCustomValidity('Kart numarası 16 rakam olmalı ve geçerli bir kart numarası olmalıdır.');
        } else {
            errorElement.textContent = '';
            input.setCustomValidity('');
        }
    }

    function luhnCheck(cardNumber) {
        let sum = 0;
        let doubleUp = false;
        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let curDigit = parseInt(cardNumber.charAt(i), 10);
            if (doubleUp) {
                curDigit *= 2;
                if (curDigit > 9) curDigit -= 9;
            }
            sum += curDigit;
            doubleUp = !doubleUp;
        }
        return (sum % 10) === 0;
    }



    function validateNumber(input) {
        const number = input.value.replace(/\D/g, '');
        input.value = number;
    }
    function validateCVV(input) {
        input.value = input.value.replace(/\D/g, '');
    }


    function toggleCVVVisibility() {
        const cvvInput = document.getElementById('cvv');
        const toggleIcon = document.getElementById('toggleCVVIcon');
        if (cvvInput.type === 'password') {
            cvvInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            cvvInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</html>