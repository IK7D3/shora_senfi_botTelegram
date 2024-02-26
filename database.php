<?php

$API_KEY = "6555676350:AAFOARICP3hRKhMkjIEv4ZPTl51TxR6deYE";
define('API_KEY', $API_KEY);

$inData = file_get_contents('php://input');
$tdata = json_decode($inData);


/* message data */
$message            =   $tdata->message;
$message_id         =   $message->message_id;
$reply_to_message   =   $message->reply_to_message;
$reply_text         =   $reply_to_message->text;
$text               =   $message->text;
$forward_from_chat  = $message->forward_from_chat;
$forward_from_chat_id  = $forward_from_chat->id;
$forward_from_message_id  = $message->forward_from_message_id;
$photo              =   $message->photo;
$photo_file_id      =   $photo[0]->file_id;
$video              =   $message->video;
$video_file_id      =   $video->file_id;
$caption            =   $message->caption;
$sticker            =   $message->sticker;
$sticker_file_id    =   $sticker->file_id;
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

$sql = "SELECT * FROM Users WHERE `telegram_id` = '$chat_id'";
$res = $connection->query($sql);
$row = $res->fetch_assoc();
$temp_telegram_id = $row['telegram_id'];



if (mysqli_num_rows($res) && $row['username'] != $chat_username) {
    $sql1 = "UPDATE Users SET `username` = '$chat_username' WHERE `telegram_id` = '$temp_telegram_id'";
    $res1 = $connection->query($sql1);

}elseif(mysqli_num_rows($res) != 0 || $callback_query){

}else{
    $sql = "INSERT INTO Users (`first_name`, `username`, `telegram_id`) VALUES ('$chat_first_name', '$chat_username', '$chat_id')";
    $connection->query($sql);
}

if ($chat_type == "group") {
    
}else{
    if ($text == "/start") {
        startCommand($chat_id, $message_id);
    }
    elseif ($callback_query) {
        switch ($callback_query_data) {
            case 'start_ersal':
                sendMessage_form($callback_user_id, $callback_message_id);
                break;
            case 'start_channel':
                kanalvgroh_form($callback_user_id, $callback_message_id);
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
            
            case 'sendMessage_form_shoraha_khaharan':
            case 'sendMessage_form_shoraha_baradaran':
            case 'sendMessage_form_shoraha_ensani':
            case 'sendMessage_form_shoraha_paye':
            case 'sendMessage_form_shoraha_honar':
            case 'sendMessage_form_shoraha_fani':
                $sql = "UPDATE Users SET step = 1 WHERE `telegram_id` = '$callback_user_id'";
                $res = $connection->query($sql);
                before1_ersalePayam($callback_query_data, $callback_user_id, $callback_message_id);
                break;
    
            case 'sendMessage_form_komision_amoozesh':
            case 'sendMessage_form_komision_hoghoghi':
            case 'sendMessage_form_komision_resaneh':
            case 'sendMessage_form_komision_taghzie':
            case 'sendMessage_form_komision_tarabari':
            case 'sendMessage_form_komision_khadamat':
                $sql = "UPDATE Users SET step = 1 WHERE `telegram_id` = '$callback_user_id'";
                $res = $connection->query($sql);
                before2_ersalePayam($callback_query_data, $callback_user_id, $callback_message_id);
                break;
    
            case 'before1_ersalePayam_bazgasht':
                $sql = "UPDATE Users SET step = 0 WHERE `telegram_id` = '$callback_user_id'";
                $res = $connection->query($sql);
                sendMessage_form_shoraha($callback_user_id, $callback_message_id);
                break;
    
            case 'before2_ersalePayam_bazgasht':
                sendMessage_form_komision($callback_user_id, $callback_message_id);
                break;
            
            case 'start_taghvim':
                bot("copyMessage", [
                    "chat_id" => $callback_user_id,
                    "from_chat_id" => -4103430097,
                    "message_id" => 51
                    // "text" => $inData
                ]);
                break;
            default:
                # code...
                break;
        }
    
    }else{
        $sql = "SELECT * FROM Users WHERE `telegram_id` = '$chat_id'";
        $res = $connection->query($sql);
        $row = $res->fetch_assoc();
        if (mysqli_num_rows($res) && $row['step'] == 1) {
            if ($photo) {
                ersalePayam($row['temp_department'], 'photo', $photo_file_id, $caption);
                ersalePayam_group('photo', $chat_username, $row['temp_department'], $photo_file_id, $caption);
                bot("sendMessage", [
                    "chat_id" => $chat_id,
                    "text" => "پیام ارسال شد!"
                ]);
                $sql = "UPDATE Users SET step = 0 WHERE `telegram_id` = '$chat_id'";
                $res = $connection->query($sql);
                startCommand($chat_id, $message_id);
            }elseif ($video) {
                ersalePayam($row['temp_department'], 'video', $video_file_id, $caption);
                ersalePayam_group('video', $chat_username, $row['temp_department'], $video_file_id, $caption);
                bot("sendMessage", [
                    "chat_id" => $chat_id,
                    "text" => "پیام ارسال شد!"
                ]);
                $sql = "UPDATE Users SET step = 0 WHERE `telegram_id` = '$chat_id'";
                $res = $connection->query($sql);
                startCommand($chat_id, $message_id);
            }elseif($text){
                ersalePayam($row['temp_department'], 'text' ,null, null, $text);
                ersalePayam_group('text', $chat_username, $row['temp_department'], null, null, $text);
                bot("sendMessage", [
                    "chat_id" => $chat_id,
                    "text" => "پیام ارسال شد!"
                ]);
                $sql = "UPDATE Users SET step = 0 WHERE `telegram_id` = '$chat_id'";
                $res = $connection->query($sql);
                startCommand($chat_id, $message_id);
            }else{
                bot("sendMessage", [
                "chat_id" => $chat_id,
                "text" => "پیام شما نباید شامل استیکر، گیف و ویس باشد‼️"
            ]);
            }
    
         
            
        }else{
            startCommand($chat_id, $message_id);
            
        }
    }
}


// شروع اصلی ربات


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
        "text" => "سلام! به ربات شورای صنفی دانشگاه یزد خوش اومدی.\nچه کاری برات انجام بدم؟",
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
        "text" => "سلام! به ربات شورای صنفی دانشگاه یزد خوش اومدی.\nچه کاری برات انجام بدم؟",
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
// دکمه شوراها
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
        "text" => "بخشی را که میخواهید به آن پیامی ارسال کنید از منوی پایین انتخاب کنید.",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}
// دکمه کمیسیون ها
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
        "text" => "بخشی را که میخواهید به آن پیامی ارسال کنید از منوی پایین انتخاب کنید.",
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

//  عمل ارسال پیام
function before1_ersalePayam($department_name, $callback_user_id, $callback_message_id){
    $title = titleConvert($department_name);
    global $connection;
    $sql = "UPDATE Users SET `temp_department` = '$department_name' WHERE `telegram_id` = '$callback_user_id'";
    $res = $connection->query($sql);
    $inlineKeyboard = [
        [
            ['text' => 'بازگشت', 'callback_data'=> 'before1_ersalePayam_bazgasht'],
        ],
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "🔸 ".$title." 🔸\nلطفا پیام خود را وارد کنید.\nتوجه داشته باشید پیام شما نمی‌تواند شامل استیکر، گیف و ویس باشد!",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}
function before2_ersalePayam($department_name, $callback_user_id, $callback_message_id){
    $title = titleConvert($department_name);

    global $connection;
    $sql = "UPDATE Users SET `temp_department` = '$department_name' WHERE `telegram_id` = '$callback_user_id'";
    $res = $connection->query($sql);
    $inlineKeyboard = [
        [
            ['text' => 'بازگشت', 'callback_data'=> 'before2_ersalePayam_bazgasht'],
        ],
    ];
    bot("editMessageText", [
        "chat_id" => $callback_user_id,
        "message_id" => $callback_message_id,
        "text" => "🔸 ".$title." 🔸\nلطفا پیام خود را وارد کنید.\nتوجه داشته باشید پیام شما نمی‌تواند شامل استیکر، گیف و ویس باشد!",
        "reply_markup" => json_encode(['inline_keyboard'=>$inlineKeyboard])
    ]);
}

// ارسال پیام به اشخاص
function ersalePayam($department_name, $type, $file_id=null, $caption=null, $text=null){
    global $connection;
    $sql = "SELECT * FROM IDreceivers WHERE `department_name` = '$department_name'";
    $res = $connection->query($sql);
    $row = $res->fetch_assoc();

    switch ($type) {
        case 'photo':
            bot("sendPhoto", [
                "chat_id" => $row['telegram_id'],
                "photo" => $file_id,
                "caption" => $caption
            ]);
            break;
        case 'video':
            bot("sendVideo", [
                "chat_id" => $row['telegram_id'],
                "video" => $file_id,
                "caption" => $caption
            ]);
            break;
        case 'text':
            bot("sendMessage", [
                "chat_id" => $row['telegram_id'],
                "text" => $text
            ]);
            break;
        default:
            bot("sendMessage", [
                "chat_id" => $row['telegram_id'],
                "text" => $text
            ]);
            break;
    }
    
}

// ارسال پیام به گروه
function ersalePayam_group($type, $chat_username, $department_name, $file_id=null, $caption=null, $text=null){
    $title = titleConvert($department_name);
    switch ($type) {
        case 'photo':
            bot("sendPhoto", [
                "chat_id" => -4103430097,
                "photo" => $file_id,
                "caption" => "یک پیام جدید از طرف: ". "@". $chat_username. " در بخش ".$title . "\n" .$caption
            ]);
            break;
        case 'video':
            bot("sendVideo", [
                "chat_id" => -4103430097,
                "video" => $file_id,
                "caption" => "یک پیام جدید از طرف: ". "@". $chat_username. " در بخش ".$title . "\n" .$caption
            ]);
            break;
        default:
            bot("sendMessage", [
                "chat_id" => -4103430097,
                "text" => "یک پیام جدید از طرف: ". "@". $chat_username. " در بخش ".$title . "\n" .$text
            ]);
            break;
    }
}

// تعیین بخش
function titleConvert($title){
    switch ($title) {
        case 'sendMessage_form_shoraha_khaharan':
            return "خوابگاه خواهران";
        case 'sendMessage_form_shoraha_baradaran':
            return "خوابگاه برداران";
        case 'sendMessage_form_shoraha_ensani':
            return "علوم انسانی";
        case 'sendMessage_form_shoraha_paye':
            return "علوم پایه";
        case 'sendMessage_form_shoraha_honar':
            return "هنر و معماری";
        case 'sendMessage_form_shoraha_fani':
            return "فنی مهندسی";   
        case 'sendMessage_form_komision_amoozesh':
            return "آموزش";   
        case 'sendMessage_form_komision_hoghoghi':
            return "حقوقی";   
        case 'sendMessage_form_komision_resaneh':
            return "رسانه";   
        case 'sendMessage_form_komision_taghzie':
            return "تغذیه";   
        case 'sendMessage_form_komision_tarabari':
            return "ترابری";   
        case 'sendMessage_form_komision_khadamat':
            return "خدمات";   

        default:
            return "";   

    }
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