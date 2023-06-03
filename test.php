<?php
    $subject = "Catalog online | Ti-a fost creat contul";
    $message = '
    <html>
    <body style="font-family: Segoe UI,Arial,sans-serif;font-weight: 400;">
        <div style="margin:0 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
            <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                <h2> Creare cont catalog online</h2>
            </div>
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Detalii</div> Pe aceasta cale dorim sa te informam ca in urma cu putin timp ti-a fost creat un cont pentru catalogul online.
            </div>
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Detalii</div> Mai jos ai detaliile contului tau , acestea sunt stiute doar de tine si NU pot sa fie resetate / schimbate , ai grija de ele
            </div>
            <div style="margin : 16px;border-bottom: 5px solid #ebf2f7;">
                <h2> Detalii cont</h2>
            </div>
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Detalii</div> Contul tau de : a fost creeat cu succes
            </div>
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Username</div> 
            </div>
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: green;border-radius: 5px;">Parola</div>
            </div>
        </div>
        <div style="margin:10px 20% 0 20%;border: 5px solid #ebf2f7;border-radius: 8px;height:auto;padding : 5px;display:block;">
            <div style="margin : 16px;">
                <div style="color: #fff;display:inline-block;padding-left: 8px; padding-right: 8px;text-align: center;background-color: #F2CC8F;border-radius: 5px;">Creator</div> Platforma creeata de <a href="https://www.instagram.com/_alexmihai_/">Udrescu Alexandru Mihai</a>
            </div>
        </div>
    </body>
    </html>
    ';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <catalog-online@alex-mihai.ro>' . "\r\n";

    mail("udrescualexandrumihai@gmail.com", $subject, $message, $headers);

?>