<?php

#    CastingWords API python client
#
#    Copyright (c) 2013 CastingWords LLC
#
# See the License file
# distributed with this work for additional information
# regarding copyright ownership.  The CastingWords licenses this file
# to you under the Apache License, Version 2.0 (the
# "License"); you may not use this file except in compliance
# with the License.  You may obtain a copy of the License at

#   http://www.apache.org/licenses/LICENSE-2.0

# Unless required by applicable law or agreed to in writing,
# software distributed under the License is distributed on an
# "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
# KIND, either express or implied.  See the License for the
# specific language governing permissions and limitations
# under the License.


class CastingWordsAPI
{     
  protected $ch; 
  protected $response;
  public $test;

  function __construct($api_key)
  {
    $this->api_key = $api_key	;
    $this->base = 'https://castingwords.com/store/API4';
    $this->ch   = curl_init();

  }

  function __destruct() {
    curl_close($this->ch);     
   }

  protected function path() {
    $path = func_get_args();
    array_unshift($path, $this->base);
    return implode('/', $path)  ;
  }

  protected function request($method, $path, $params){
    if(!isset($this->ch)) $this->ch = curl_init();
    if ($this->test){
      if (!$params){
        $params = [];
      }
      $params["test"] = 1;
    }

    if ($params){
      $query = http_build_query($params, '', '&') ;
      // This API uses multivalued querys but not arrayed queries
      // so we transform url[0]=XXX&url[1]=YYY
      // into url=XXX&url=YYY
      $query = preg_replace('/%5B[0-9]+%5D/simU', '', $query);      
      $path = $path . "?" . $query;
      
    }

    $options = [
                CURLOPT_URL => $path,
                CURLOPT_CUSTOMREQUEST => $method, 
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HEADER => 0,
                CURLINFO_HEADER_OUT => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_VERBOSE => 0,
                ]; 
#    echo $path . "\n";

    curl_setopt_array($this->ch,$options);   	   
    $response =  curl_exec($this->ch);
    $information = curl_getinfo($this->ch);
#    echo $information["http_code"] . "\n";
    if ($information["http_code"] > 500){
      $j = json_decode($response, true);
      if ($j && $j["message"]){
        throw new Exception($j["message"]);         
      }else{           
        throw new Exception("Internal Server Error - please contact support@castingwords.com");
      }
    }elseif ($information["http_code"] > 400){
      $j = json_decode($response, true);
      var_dump($j);
      if ($j && $j["message"]){
        throw new Exception($j["message"]);
      }else{           
        throw new Exception($response);
      }
    }
    return(json_decode($response, true));	   			  
    	    
  }

  /**
     Gets a dictionary of attribute for this audiofile. 
     The current transcription status is in 'statename'.
  **/

  public function audiofile_details($afid){
    $this->response = $this->request("GET", 
                                     $this->path("audiofile",$afid), 
                                     ["api_key" => $this->api_key]
                                     );
    return  $this->response;
  }


    /*
        Returns the transcript for a given audiofile_id - valid extensions are 
        txt
        doc
        rtf
        html
    */

  public function audiofile_transcript($afid, $extension){
      $this->response = $this->request("GET",$this->path("audiofile" , $afid, "transcript.$ext" ), ["api_key" => $this->api_key]);
    return  $this->response;
  }

        
    /*
      Orders a transcripts. Returns the order and audiofile ids.
      url -  Url(s) of audio/video to transcribe.  Preferably points to an mp3. ;
      sku -  one or more skus to order every url with. Only one TRANS%  sku is allowed.
      TRANS14   =  Budget Transcription with a target of 14 days. 
      TRANS2    =  1 Day Transcription
      TRANS6    =  6 Day Transcription
      DIFFQ2    =  Difficult Audio
      TSTMP1    =  Timestamps
      VERBATIM1 =  Verbatim Transcription
          
      Return Example: {'message': "Order '0a0' Created", 'audiofiles': [0000001], 'order':'0a0'}
    */

  public function order_url( $url, $sku){   
    $this->response = $this->request("POST", 
                                     $this->path("order_url"), 
                                     ["api_key"=> $this->api_key, "url"=>$url, "sku" =>$sku]);
    return  $this->response;
  }

  /*
    Returns the remaining prepay balance for your account
  */

  public function prepay_balance(){   
    $this->response = $this->request("GET", 
                                     $this->path("prepay_balance"), 
                                     ["api_key" => $this->api_key]);
    return  $this->response["balance"];
  }

  public function set_webhook( $callback_url){   
    $this->response = $this->request("POST", 
                                     $this->path("webhook"), 
                                     ["api_key"=> $this->api_key,  "webhook" =>$callback_url]);
    return  $this->response;
  }

  public function webhook(){   
    $this->response = $this->request("GET", 
                                     $this->path("webhook"), 
                                     ["api_key" => $this->api_key]);
    return  $this->response["webhook"];
  }



}


