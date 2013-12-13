<?php
set_include_path('./lib/');
require 'CastingWordsAPI.php';
$api_key = $argv[1];
$cw = new CastingWordsAPI($api_key);

// Everythings in test mode
$cw->test  = 1;
echo "-----------------------------------------\nAudiofile Details\n";
print_r($cw->audiofile_details(101));
echo "-----------------------------------------\nOrder URL\n";
print_r($cw->order_url(["http://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3",
                        "http://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3"
                        ], ["TRANS6", "TSTMP1"]));
echo "-----------------------------------------\nPrepay Balance\n";
print_r($cw->prepay_balance());
echo "\n-----------------------------------------\nWebhook Address\n";
print_r($cw->webhook());
echo "\n";
