<?php
/**
 * Autonomy system of error handling.
 * To use it, include simply this code in yours.
 * @author Tim
 */
error_reporting(E_ALL);

define("ERROR_HANDLER_ENABLE", true);
if(!defined("ERROR_HANDLER_DEVELOPMENT"))
    define("ERROR_HANDLER_DEVELOPMENT", false);
define("ERROR_HANDLER_SUPPORTED_ERROR_MODE", E_ALL);
define("ERROR_HANDLER_ERROR_LOG", strtr(ROOT_DIR_SERVICE.'error_log.err', "\\", "/"));
define("ERROR_HANDLER_USER", "USER");
define("ERROR_HANDLER_SYSTEM", "SYSTEM");
define("ERROR_HANDLER_WARNING", "WARNING");
define("ERROR_HANDLER_ERROR", "ERROR");
define("ERROR_HANDLER_NOTICE", "NOTICE");
define("ERROR_HANDLER_SENDING_INTERVAL", 1800);
define("ERROR_HANDLER_CLEANING_LOG_INTERVAL", 2580000); //one month;

class Errors{
    
    public function GetNumErrors(){
        return count($this->errorsQueue);
    }
    
    public function Push($degree, $code = 0, $message = "", $file = "", $line = ""){
        if(!$file and !$line){
            $trace = debug_backtrace();
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];
        }
        $this->AddError(ERROR_HANDLER_USER, $degree, $code, $message, $file, $line);
        if($degree == ERROR_HANDLER_ERROR)
            exit;
    }
    
    public function GetError(){
        return array_shift($this->errorsQueue); 
    }
    
    //handlers
    //ShutDownHandler catches errors of E_ERROR and E_PARSE modes. Next it writes errorsQueue in log file
    //and sends that to developers if it is necessary.
   public function ShutDownHandler(){
        $error = error_get_last();
        if ($error and ($error['type'] == E_ERROR or $error['type'] == E_PARSE or $error['type'] == E_COMPILE_ERROR)) {
           $this->AddError(ERROR_HANDLER_SYSTEM, ERROR_HANDLER_ERROR, $error['type'], $error['message'], $error['file'], $error['line']);
        }
        //next operations
        $info = $this->ErrorLogPreparer();

        if($this->GetNumErrors()){
            $this->Log($info);
            echo $this->FormMessage(false); 
            $this->errorsQueue = array();
        }
        $this->SendLastErrors($info);
        if($this->GetNumErrors()){
             echo $this->FormMessage(false); 
             $this->Log($info); 
        }    
        fclose($info['fp']); 
    } 
    
    public function ErrorHandler($errno, $errstr, $errfile, $errline){
        if(error_reporting() == 0) //if @ was used
            return false;
        $degree = ERROR_HANDLER_WARNING;
        switch($errno){
            case E_ERROR:
                $degree = ERROR_HANDLER_ERROR; break;
            case E_NOTICE:
                $degree = ERROR_HANDLER_NOTICE; break;
        }
        $this->AddError(ERROR_HANDLER_SYSTEM, $degree, $errno, $errstr, $errfile, $errline);
        return true;
    }
    
    public function ExceptionHandler($ex){
        $this->AddError(ERROR_HANDLER_USER, ERROR_HANDLER_WARNING, $ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
    }
    //end handlers 
    
    //errorsQueue operations
    private function AddError($type, $degree, $code, $message = "", $file = "", $line = "", $time = 0){
        array_push($this->errorsQueue, array("type" => $type,
                         "degree" => $degree,
                         "code" => intval($code),
                         "location" => array("file" => $file, "line" => $line),
                         "message" => $message,
                         "time" => ($time)?$time:time()
                  ));
    }
    //end errorsQueue operations
    
    //This methods prepares log file
    private function ErrorLogPreparer(){
        fclose(fopen(ERROR_HANDLER_ERROR_LOG, "a+"));
        $info = array('fp' => false);
        $info['fp']= fopen(ERROR_HANDLER_ERROR_LOG, "r+b"); 
		if(!$info['fp']){
			$this->AddError(ERROR_HANDLER_USER, ERROR_HANDLER_ERROR, 1, "Не удалось открыть файл для записи ошибок");
			echo $this->FormMessage(false);
			exit;
		}
        $this->GetMetaData($info);
        return $info;
    }

    private function GetMetaData(&$info){
        if(!$info['fp'] or !$this->OwnFlock($info['fp'], LOCK_SH))
        {
            $this->InternalError();
        }
        $metaStr = fgets($info['fp']);
        $this->OwnFlock($info['fp'], LOCK_UN);
        @$metaData = unserialize(substr($metaStr, 3));
        if(!$metaData){
            $info['last_sending_time'] = time() - ERROR_HANDLER_SENDING_INTERVAL - 1;
            $info['last_writing_pos'] = 1;
			$info['last_clear_log_time'] = time();
            $this->WriteMetaData($info, true);
        }
        else
        {
            $info['meta_data_length'] = strlen($metaStr) - 3;
            $info['last_sending_time'] = $metaData['last_sending_time'];
            $info['last_writing_pos'] = $metaData['last_writing_pos'];
			$info['last_clear_log_time'] = $metaData['last_clear_log_time'];
        }
    }
    
    private function WriteMetaData(&$info, $clearFile = false){
         $metaStr = serialize(array("last_sending_time" => $info['last_sending_time'], "last_writing_pos" => $info['last_writing_pos'], "last_clear_log_time" => $info['last_clear_log_time']))."\n";
         $meta_data_length = sprintf("%03d", strlen($metaStr));
         $old_data_length = 0;
         if(isset($info['meta_data_length']))
               $old_data_length = $info['meta_data_length'];

         if((intval($old_data_length) != intval($meta_data_length)) and !$clearFile)
         {
            if(!$info['fp'] or !$this->OwnFlock($info['fp'], LOCK_EX))
            {
                $this->InternalError();
            }
            fseek($info['fp'], strlen($old_data_length) + intval($old_data_length));
            $buffer = fread($info['fp'], filesize(ERROR_HANDLER_ERROR_LOG));
            ftruncate($info['fp'] ,0);
			fseek($info['fp'], 0);
			fwrite($info['fp'], $meta_data_length.$metaStr);
            fwrite($info['fp'], $buffer);
			fflush($info['fp']);
            $this->OwnFlock($info['fp'], LOCK_UN);
         }
         else
         {
            if(!$info['fp'] or !$this->OwnFlock($info['fp'], LOCK_EX))
            {
                $this->InternalError();
            }
            if($clearFile)
                ftruncate($info['fp'], 0);
            fseek($info['fp'], 0);
            fwrite($info['fp'], $meta_data_length.$metaStr);
			fflush($info['fp']);
            $this->OwnFlock($info['fp'], LOCK_UN); 
         }
         
         $info['meta_data_length'] = $meta_data_length;        
    }
    
    private function OwnFlock($fp, $mode){
        $i = 0;
        $waitingLimit = 5;
        while($i < $waitingLimit and !flock($fp, $mode))
        {
            usleep(1);
            $i++;
        }
        if($waitingLimit == $i)
            return false;
        return true;
    }
    //end preparing of log file
    
    private function InternalError(){
        $this->AddError(ERROR_HANDLER_SYSTEM, ERROR_HANDLER_ERROR, 0, "Внутренний сбой обработки ошибок");
        echo $this->FormMessage(false);
        exit;
    }
    
    private function FormMessage($forLogFile = true){
        $result = "";
        if($count = count($this->errorsQueue)){
            $i = 1;
            foreach($this->errorsQueue as $val){
                if($forLogFile or ERROR_HANDLER_DEVELOPMENT or ((defined("ADMINS_HAT") and ADMINS_HAT) or defined("IS_ADMIN")))
                    $errorStr = "[".date("d-m-Y H:i:s", $val['time'])."] ".$val['type']." FAILURE - ".$val['degree']." ".$val['code'].": ".
                                strtr($val['message'], "\r\n", " ")." in ".$val['location']['file']." on line ".$val['location']['line']."\n";
                else {
                    $errorStr = ($i++).") ".$val['type']." FAILURE - ".$val['degree']." ".$val['code'].": Обратитесь к администратору или разработчику\n";
                }
                
                $result .= $errorStr;
            }
            if(!$forLogFile){
                $result = "<div style = 'position: absolute; z-index: 100000; top: 0px; left:0px; border: 2px; border-color:#F08C8C; background-color:#F0D7D7; width: 350px; font-size: 13px; padding: 20px;'>".
                          "<span style = 'color:#A23E3E; font-size: 20px;'>".(($count > 1)?"Произошли следующие ошибки:":"Ошибка!")."</span><br/><br/>".
                          nl2br(wordwrap($result, 50, "\n", true)).
                          "</div>";
            }
        }
        return $result;
    }
    
    private function Log($info){
         $msg = $this->FormMessage();
         if(!$info['fp'] or !$this->OwnFlock($info['fp'], LOCK_EX))
         {
                $this->InternalError();
         }
         fseek($info['fp'], filesize(ERROR_HANDLER_ERROR_LOG));
         fwrite($info['fp'], $msg);
		 fflush($info['fp']);
         $this->OwnFlock($info['fp'], LOCK_UN);
    }       
    
    private function SendLastErrors(&$info){
        if(((time() - $info['last_sending_time']) > ERROR_HANDLER_SENDING_INTERVAL)){
            if(!$info['fp'] or !$this->OwnFlock($info['fp'], LOCK_SH))
            {
                $this->InternalError();
            }
            $errorArrayAll = file(ERROR_HANDLER_ERROR_LOG, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $this->OwnFlock($info['fp'], LOCK_UN);
            if(isset($errorArrayAll[$info['last_writing_pos']]))
            {
              
                $count = 0;
                $sendingArr = array();
                while(@$error = $errorArrayAll[$info['last_writing_pos'] + $count]){
                    $sendingArr[] = $error;
                    $count++;
                }
                if($count)
                {
                    $errors = implode("**del**", $sendingArr);
					$sendInfo = SendInfoToDev("errors", array("lastErrors" => $errors));
					if(!$this->GetNumErrors() and ($sendInfo['code'] != 500)){	
						$clearFile = false;
						if((time() - $info['last_clear_log_time']) > (ERROR_HANDLER_CLEANING_LOG_INTERVAL + ERROR_HANDLER_SENDING_INTERVAL))
						{
							$clearFile = true;
							$info['last_clear_log_time'] = time();
						}
						$info['last_writing_pos'] += $count;
						$info['last_sending_time'] = time();
						$this->WriteMetaData($info, $clearFile);
					}
                }
            }
        }
    }
    
    private $errorsQueue = array();
}

$errorHandlerObject = new Errors(); 

//Function for sending information to developers
function SendInfoToDev($type, $info, $files = array()){
	global $errorHandlerObject;
	$trace = debug_backtrace();
	$file= $trace[0]['file'];
	$line = $trace[0]['line'];

	$result = array();
	
	if(!$type or !$info or !is_array($info))
		$errorHandlerObject->Push(ERROR_HANDLER_WARNING, 1, "Аргументы не заданы.", $file, $line);
	
	if($files and is_array($files))
		array_walk($files, function(&$val, $key){
			$val = ("@".strval($val));
		});
	
	array_walk($info, function(&$val, $key){
		$val = strval($val);
	});
	
	$ch = curl_init();
    $options = array(
        CURLOPT_URL => DEVELOPMENT_SERVER_ADDRESS,  
        CURLOPT_AUTOREFERER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array_merge(array("type" => $type, "licenseKey" => ((defined("LICENSE_KEY"))?LICENSE_KEY:0), "hostName" => isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"", 
										 "language" => isset($SiteLanguageGlobal)?$SiteLanguageGlobal:0,
										 "userAgent" => isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"",
										 "remoteAddr" => isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:""),
										 array_merge($info, $files))
    );
	
    curl_setopt_array($ch, $options);
    $acceptData = curl_exec($ch);

	if(curl_errno($ch))
    {	
	   curl_close($ch);
	   
       //$errorHandlerObject->Push(ERROR_HANDLER_WARNING, 2, "Не удалось связаться с сервером разработчиков для отправки служебной информации.", $file, $line);
	   $result['code'] = 500;
	   $result['answer'] = "Bad gateway.";
	   $result['details'] = "";
	   $result['error'] = 0;
    }
	else{
		curl_close($ch);
		@$xml = simplexml_load_string($acceptData);
		if($xml)
		{
			$result['code'] = $xml->code;
			$result['answer'] = $xml->answer;
			$result['details'] = $xml->details;
			$result['error'] = $xml->error;
			
			if(strval($result['error']))
			{
			    $errorHandlerObject->Push(ERROR_HANDLER_WARNING, 4, "Ответ от сервера разработчиков: ".$xml->error, $file, $line);
			}
		}
		else{
			$errorHandlerObject->Push(ERROR_HANDLER_WARNING, 3, "Получен некорректный ответ.", $file, $line);
		}
	}
	
	return $result;
}




//Settings of modes and handlers
//IMPORTANT!!! This code must be located after Errors class definition!
if(ERROR_HANDLER_ENABLE):
error_reporting(ERROR_HANDLER_SUPPORTED_ERROR_MODE);
ini_set('display_errors', 0);

register_shutdown_function(array($errorHandlerObject, "ShutDownHandler"));
set_error_handler(array($errorHandlerObject,"ErrorHandler"), ERROR_HANDLER_SUPPORTED_ERROR_MODE);
set_exception_handler(array($errorHandlerObject,"ExceptionHandler"));
endif;
?>