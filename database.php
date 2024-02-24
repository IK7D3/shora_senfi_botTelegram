<?php

$API_KEY = "1990491767:AAEcTDlz06RAzf_8pmrVifq8GtEFabw7McA";
define('API_KEY', $API_KEY);

$inData = file_get_contents('php://input');
$tdata = json_decode($inData);


/* message data */
$message            =   $tdata->message;
$message_id         =   $message->message_id;
$reply_to_message   =   $message->reply_to_message;
$reply_text         =   $reply_to_message->text;
$text               =   $message->text;
$chat               =   $message->chat;
$chat_type          =   $chat->type;
$chat_id            =   $chat->id;
$chat_first_name    =   $chat->first_name;
$chat_username      =   $chat->username;


/* callback data */
$callback_query     =   $tdata->callback_query;
$callback_query_data     =   $callback_query->data;
$callback_from      =   $callback_query->from;
$callback_message   =   $callback_query->message;
$callback_message_id   =   $callback_message->message_id;
$callback_message_inline_keyboard   =   $callback_message->reply_markup->inline_keyboard;
$callback_user_id   =   $callback_from->id;
$callback_username   =   $callback_from->username;

/* database */
$hostName       =   "localhost";
$dbUser         =   "imanbott_user_one";
$dbPassword     =   "iman12346I";
$dbName         =   "imanbott_Database_test1";
$connection = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
mysqli_set_charset($connection, 'UTF8MB4');

$sql = "SELECT * FROM Users WHERE `username` = '$chat_username'";
$res = $connection->query($sql);


if (mysqli_num_rows($res) || $callback_query) {
    
}else{
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "پیدا نشد" 
    ]);
    $sql = "INSERT INTO Users (`first_name`, `username`, `telegram_id`) VALUES ('$chat_first_name', '$chat_username', '$chat_id')";
    $connection->query($sql);
}


// $mohammad = "meow";

// $sql = "SELECT * FROM Users WHERE `name` = '$mohammad'";
// $res = $connection->query($sql);
// $row = $res->fetch_assoc();

// if (mysqli_num_rows($res)) {
//     bot("sendMessage", [
//         "chat_id" => $chat_id,
//         "text" => "پیدا شد"
//     ]);
//     bot("sendMessage", [
//         "chat_id" => $chat_id,
//         "text" => "id = ".$row['id']."\nLast Name= ". $row['lastname']
//     ]);
// }else{
//     bot("sendMessage", [
//         "chat_id" => $chat_id,
//         "text" => "پیدا نشد" 
//     ]);

// }
if ($callback_query) {
    switch ($callback_query_data) {
        case 'start_ersal':
            sendMessage_form($callback_user_id, $callback_message_id);
            break;
        case 'sendMessage_form_shoraha':
            sendMessage_form_shoraha($callback_user_id, $callback_message_id);
            break;
        case 'sendMessage_form_bazgasht':
            reStartCommand($callback_user_id, $callback_message_id);
            break;
        case 'sendMessage_form_shoraha_bazgasht':
            sendMessage_form($callback_user_id, $callback_message_id);
            break;
        
        case 'sendMessage_form_komision':
            sendMessage_form_komision($callback_user_id, $callback_message_id);
            break;
        case 'sendMessage_form_komision_bazgasht':
            sendMessage_form($callback_user_id, $callback_message_id);
            break;
        case 'kanalvgroh_form_bazgasht':
            reStartCommand($callback_user_id, $callback_message_id);
            break;
        default:
            # code...
            break;
    }

}

if ($text == "/start") {
    startCommand($chat_id, $message_id);
}


// کامنت /start
function startCommand($chat_id, $message_id){
    $inlineKeyboard =[
        [
            ['text' => 'ارسال پیام', 'callback_data'=> 'start_ersal'],
        ],
        [
            ['text' => 'کانال و گروه‌های پرکاربُرد', 'callback_data'=> 'start_channel'],
        ],
        [
            ['text' => 'تقویم آموزشی', 'callback_data'=> 'start_taghvim'],
            ['text' => 'گلستان و سامیاد', 'callback_data'=> 'start_golestan'],
        ],
        [
            ['text' => 'راهنما', 'callback_data'=> 'start_rahnama'],
        ]
    ];
    bot("sendMessage", [
        "chat_id" => $chat_id,
        "text" => "سلام عزیزم. به ربات ما خوش اومدی.",
        "reply_parameters" => json_encode(['message_id'=>$message_id]),
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}
function reStartCommand($callback_user_id, $callback_message_id){
    $inlineKeyboard =[
        [
            ['text' => 'ارسال پیام', 'callback_data'=> 'start_ersal'],
        ],
        [
            ['text' => 'کانال و گروه‌های پرکاربُرد', 'callback_data'=> 'start_channel'],
        ],
        [
            ['text' => 'تقویم آموزشی', 'callback_data'=> 'start_taghvim'],
            ['text' => 'گلستان و سامیاد', 'callback_data'=> 'start_golestan'],
        ],
        [
            ['text' => 'راهنما', 'callback_data'=> 'start_rahnama'],
        ]
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "سلام عزیزم. به ربات ما خوش اومدی.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}


// دکمه ارسال پیام
function sendMessage_form($callback_user_id, $callback_message_id){
    $inlineKeyboard = [
        [
            ['text' => 'شوراها', 'callback_data'=> 'sendMessage_form_shoraha'],
            ['text' => 'کمیسیون‌ها', 'callback_data'=> 'sendMessage_form_komision'],
        ],
        [
            ['text' => 'وظایف کمیسیون‌ها و شوراها', 'callback_data'=> 'sendMessage_form_vazayef'],
        ],
        [
            ['text' => 'بازگشت', 'callback_data'=> 'sendMessage_form_bazgasht'],

        ]
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "گزینه موردنظر خود را از منوی پایین انتخاب کنید.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}
function sendMessage_form_shoraha($callback_user_id, $callback_message_id){
    $inlineKeyboard = [
        [
            ['text' => 'خوابگاه خواهران', 'callback_data'=> 'sendMessage_form_shoraha_khaharan'],
            ['text' => 'خوابگاه برداران', 'callback_data'=> 'sendMessage_form_shoraha_baradaran'],
        ],
        [
            ['text' => 'علوم انسانی', 'callback_data'=> 'sendMessage_form_shoraha_ensani'],
            ['text' => 'علوم پایه', 'callback_data'=> 'sendMessage_form_shoraha_paye'],
        ],
        [
            ['text' => 'هنر و معماری', 'callback_data'=> 'sendMessage_form_shoraha_honar'],
            ['text' => 'فنی مهندسی', 'callback_data'=> 'sendMessage_form_shoraha_fani'],
        ],
        [
            ['text' => 'بازگشت', 'callback_data'=> 'sendMessage_form_shoraha_bazgasht'],

        ]
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "وظایف هر بخشی را که میخواهید مشاهده کنید از منوی پایین انتخاب فرمایید.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}
function sendMessage_form_komision($callback_user_id, $callback_message_id){
    $inlineKeyboard = [
        [
            ['text' => 'آموزش', 'callback_data'=> 'sendMessage_form_komision_amoozesh'],
            ['text' => 'حقوقی', 'callback_data'=> 'sendMessage_form_komision_hoghoghi'],
            ['text' => 'رسانه', 'callback_data'=> 'sendMessage_form_komision_resaneh'],
        ],
        [
            ['text' => 'تغذیه', 'callback_data'=> 'sendMessage_form_komision_taghzie'],
            ['text' => 'ترابری', 'callback_data'=> 'sendMessage_form_komision_tarabari'],
        ],
        [
            ['text' => 'خدمات دانشگاهی', 'callback_data'=> 'sendMessage_form_komision_khadamat'],
        ],
        [
            ['text' => 'بازگشت', 'callback_data'=> 'sendMessage_form_komision_bazgasht'],

        ]
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "وظایف هر بخشی را که میخواهید مشاهده کنید از منوی پایین انتخاب فرمایید.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}

// دکمه کانال و گروهها
function kanalvgroh_form($callback_user_id, $callback_message_id){
    $inlineKeyboard = [
        [
            ['text' =>  'انجمن‌های علمی', 'callback_data'=> 'kanalvgroh_form_anjoman'],
            ['text' => 'کانال‌ها', 'callback_data'=> 'kanalvgroh_form_kanalha'],
        ],
        [
            ['text' => 'بازگشت', 'callback_data'=> 'kanalvgroh_form_bazgasht'],
        ]
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "گزینه موردنظر خود را از منوی پایین انتخاب کنید.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}


// خود بات
function bot($method, $data=[]){
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);

    return $result;
}