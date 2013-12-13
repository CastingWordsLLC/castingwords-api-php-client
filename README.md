CastingWords Transcription API  
=================
PHP Client
-------------


Install:

    $ 

Example:

     $ php -a
     Interactive shell
     php > set_include_path('./lib/');
     php > require 'CastingWordsAPI.php';
     >>> $cw = new CastingWordsAPI("123456789asdfghjkl0987654321");
     >>> $cw->test  = 1;  // set test mode
     >>> echo $cw->prepay_balance()."\n";
     1001.00
     >>> print_r( $cw->order_url("http://example.com/media.mp3", "TRANS14"));
     Array
     (
         [audiofiles] => Array
            (
               [0] => 165131
            )

          [order] => 4nX
          [message] => Order '4nX' Created
    )

Run Example:
    $   php ex/example.php $MY_API_KEY;

See Also: 
* [CastingWords Transcription API Description](https://castingwords.com/support/transcription-api.html)
* [CastingWords API Docs](https://castingwords.com/docs/developer/SimpleAPI.html)







