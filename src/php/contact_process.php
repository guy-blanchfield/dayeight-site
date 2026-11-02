<?php

// honeypot from https://www.phptutorial.net/php-tutorial/php-contact-form/
    // check the honeypot
    // $honeypot = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
    // if ($honeypot) {
    //     header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    //     exit;
    // }

// do we need to add the honeypot input to the json and check that here?

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {

    // get the post data
    // security issues of php://input
    // https://stackoverflow.com/questions/44572354/is-php-input-secure-if-not-how-to-secure-it
    // seems like it should ok as long as whatever data we get from it is sanitized
    $json = file_get_contents("php://input");
    // convert the json to a php array
    $data = json_decode($json, true);

    // real secret key
    $secret_key = '6LdkTv8eAAAAANsTWmPxf0fXk9sb_D3H0XE2_Nui'; 
    // test secret key
    // $secret_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    $response_token = $data["token"];

    // from https://www.texelate.co.uk/blog/use-php-to-validate-google-recaptcha
    
    $querydata = [

        'secret' => $secret_key,
        'response' => $response_token
    
    ];
    
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($querydata));
    
    $response = curl_exec($curl);
    $response = json_decode($response, true);
    
    if ($response['success'] === false) {
    
        // Failure
        echo 'Verification failed' . "\r\n";
        echo 'Success is: ' . $response["success"] . "\r\n";
        echo 'Error codes: ' . $response["error-codes"] . "\r\n";
        echo '$response is: ';
        print_r ($response);
    
    } else {
    
        // Success

        echo 'Verification succeeded!' . "\r\n";
        echo 'Success is: ' . $response["success"] . "\r\n";
        $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
        $sender = filter_var($data["email"], FILTER_SANITIZE_EMAIL);

        // leave email validation for now, don't want to be rejecting mistyped emails, sanitize should be fine
        // // validate e-mail
        // // filter_var validate_email returns the filtered data on success, FALSE on failure
        // if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
        //     $sender = $sender;
        // } else {
        //     $sender = 'No valid email address supplied';
        // }

        $msg = filter_var($data["msg"], FILTER_SANITIZE_STRING);
        
        // add the name to the end of the message
        $msg .= "\r\n\r\n" . "From: "  . $name;
        // right, edge doesn't seem to allow php mail()
        // unless the sender domain matches the site domain
        // so we need some way of fixing that while
        // still alowing us to get the users email address

        // let's try putting it in $subject

        // $headers = "From: " . $name . " " . $sender;
        // $headers = 'From:' . $sender;
        $headers = 'From:contact@guyblanchfield.co.uk';

        // always going to be me
        $recipient = "guyblanchfield@gmail.com";
        // always going to be the same
        $subject = "Message from ".$sender." Through Your Contact Form";

        // send the message
        mail($recipient, $subject, $msg, $headers);
        // mail($recipient, $subject, $msg, $headers);

        // echo all this stuff out for now
        echo $name . ' ' . $sender . ' ' . $msg . ' ' . $headers . ' ' . $recipient . ' ' . $subject;
    
    }

    

        

    
    // dump the array of the data to the response
    // var_dump($data);

    // output the array as readable text
    // foreach($data as $k => $v) {
    //     echo $k . ": " . $v;
    //     echo "<br>";
    // }

    // maybe check for the identifying var (if we set one in contact.js)
    // e.g.
    // $src = filter_var($data["src"], FILTER_SANITIZE_STRING);
    // if ($src !== 'grb_contact_js') { 
    //      header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    //      exit; 
    //}
    // really needs to be done by randomly generating value for the user's
    // session and adding that as a hidden input in the form
    // see https://stackoverflow.com/questions/7137545/post-request-origins/7137693

    
    // send the message
    // mail($recipient, $subject, $msg, $headers);
    
}


