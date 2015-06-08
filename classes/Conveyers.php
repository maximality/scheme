<?php
 /**
  * Conveyer system
  * It allows to invoke a few threads
  * @author Tim
  */


require_once(strtr(dirname(__FILE__).'/Errors.php', "\\", "/"));   
 
if(!defined("CONVEYER_FREQUENCY"))
	define("CONVEYER_FREQUENCY", 1);
define("CONVEYER_PATH_TO_CONFIG_FILE", strtr(ROOT_DIR_SERVICE."conveyer.ini", "\\", "/"));
	

class Conveyers {
	
	public function __construct($name, $command = false){
		global $errorHandlerObject;
                $this->errorHandlerObject = $errorHandlerObject;
		
        $this->isSecond = (isset($_GET['isSecond']) and $_GET['isSecond'])?0:1;
		$executable = isset($_GET['ConveyerKey'])?$_GET['ConveyerKey']:0;
		$this->executable = $executable;
		$newKey = mt_rand(1, 1000000);
		$executableKey = $executable;
		
		if(!$name)
		{
			$fileLine = $this->FileLineCalc();
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 5, "Недопустимое имя конвейера", $fileLine[0], $fileLine[1]);
		}
		
		fclose(fopen(CONVEYER_PATH_TO_CONFIG_FILE, "a+"));
		$fp = fopen(CONVEYER_PATH_TO_CONFIG_FILE, "r+");
		
		flock($fp, LOCK_EX); //lock
		$config = "";
		while(!feof($fp))
			$config .= fread($fp, 100000);
		@$config = parse_ini_string($config, true);
		
		//Protection
		if($executableKey and ($config[$name]['executable_key'] != $executableKey)){
			$fileLine = $this->FileLineCalc();
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 8, "Нарушение защиты", $fileLine[0], $fileLine[1]);
		}
        
		if($command == "force_start" and isset($config[$name]) and !$executable)
            unset($config[$name]);
     
		if(isset($config[$name]) and !$command and !$executable)
		{	
			$fileLine = $this->FileLineCalc();
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 5, "Недопустимое имя конвейера.", $fileLine[0], $fileLine[1]);
		}
		
		if(!isset($config[$name]) and $command != "console")
			$config[$name] = array(
				"iteration" => 0,
				"start_time" => date("m-d-Y H:i:s", time()),
                "frequency_of_first" => CONVEYER_FREQUENCY, //frq of first
                "frequency_of_second" => "",
                "last_iteration_time" => time(),
				"current_command" => "",
				"executable_key" => $newKey
			);
			
		if(!$command and $executable)
		{
			$command = $config[$name]['current_command'];
		}	
		
		$this->name = $name;
		$this->currentCommand = $command;	
		
		$this->ExecCommand($command, $config);
		
		if(isset($config[$name]) and $executable){
			$config[$name]['iteration'] += 1;
			$config[$name]['executable_key'] = $newKey;
			
            if(($config[$name]['frequency_of_first'] != CONVEYER_FREQUENCY) and !$this->isSecond)
                $config[$name]['frequency_of_first'] = CONVEYER_FREQUENCY;
            if(($config[$name]['frequency_of_second'] != CONVEYER_FREQUENCY) and $this->isSecond)
                $config[$name]['frequency_of_second'] = CONVEYER_FREQUENCY;
            $config[$name]['last_iteration_time'] = time();
        }
				
			
		$config = $this->GenerateIni($config);
		ftruncate($fp, 0);
		fseek($fp, 0);
		fwrite($fp, $config);
		fflush($fp);
		flock($fp, LOCK_UN); //end lock
		fclose($fp);
		
		$this->executableKey = $newKey;
		$this->currentConfig = $config;	
	}
	
	public function Run($ownFriend, $ownCallback = false){
		if($this->currentCommand == "end" or $this->currentCommand == "console")
			return;
		if($this->executable){
            ignore_user_abort(true);
            echo "OK";
            flush();
        }
		set_time_limit(0);
		
		$ownFriend = parse_url($ownFriend);
		$ownFriend['scheme'] = "http";
		
		if(!$ownFriend['host'])
		{
			$fileLine = $this->FileLineCalc();
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 1, "Адрес друга задан неправильно", $fileLine[0], $fileLine[1]);
		}
		$this->ownFriend = $ownFriend;
		$this->ownCallback = $ownCallback;
		if(is_array($ownCallback))
		{
			if(!method_exists($ownCallback[0], $ownCallback[1]))
			{
				$fileLine = $this->FileLineCalc();
				$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 2, "Передан недействительный функтор", $fileLine[0], $fileLine[1]);
			}
		}
		else if($ownCallback and !function_exists($ownCallback))
		{
			$fileLine = $this->FileLineCalc();
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 2, "Передан недействительный функтор", $fileLine[0], $fileLine[1]);
		}
		
		if($ownCallback)
			$this->execute();
		$this->callOwnFriend();
	}
	
	public function ExecCommand($command, &$config = false){
		$name = $this->name;
                $executable = $this->executable;
		if($command)
		{
			$command = strtolower($command);
			switch($command)
			{
				case "end": 
					if(!$executable){
                                                if(!$config){
                                                    fclose(fopen(CONVEYER_PATH_TO_CONFIG_FILE, "a+"));
                                                    $fp = fopen(CONVEYER_PATH_TO_CONFIG_FILE, "r+");
                                                    flock($fp, LOCK_EX);
                                                    $config = "";
                                                    while(!feof($fp))
                                                         $config .= fread($fp, 100000); 
                                            
                                                    @$config = parse_ini_string($config, true);
                                                    $config[$name]['current_command'] = "end";
                                                    ftruncate($fp, 0);
                                                    fseek($fp, 0);
                                                    fwrite($fp, $this->GenerateIni($config));
													fflush($fp);
													flock($fp, LOCK_UN);
                                                    fclose($fp);
                                                }
                                                else
                                                    $config[$name]['current_command'] = "end";
					}
					else
						unset($config[$name]);
					break;
                                case "check":
                                        if(!$config){
                                            fclose(fopen(CONVEYER_PATH_TO_CONFIG_FILE, "a+"));
                                            $fp = fopen(CONVEYER_PATH_TO_CONFIG_FILE, "r+");
                                            flock($fp, LOCK_SH);
                                            $config = "";
                                            while(!feof($fp))
                                                $config .= fread($fp, 100000); 
                                            flock($fp, LOCK_UN);
                                            @$config = parse_ini_string($config, true);
                                            if(!$config)
												return false;
                                            fclose($fp);
                                            if($config and isset($config[$name]))
                                                if(abs(intval(time() - $config[$name]['last_iteration_time'])) <= ($config[$name]['frequency_of_first'] + $config[$name]['frequency_of_second'] + 1))
                                                    return true;
                                            return false;
                                        }
                                        else{
                                            $this->errorHandlerObject->Push(ERROR_HANDLER_NOTICE, 7, "Данная команда конвейера выполняется только в режиме консоли", $fileLine[0], $fileLine[1]);
                                        }
                                        break;
				default: if(!in_array($command, $this->allCommands)) $this->errorHandlerObject->Push(ERROR_HANDLER_NOTICE, 6, "Неизвестная команда конвейера", $fileLine[0], $fileLine[1]);
			}
		}
	}
	
	private function execute(){
		call_user_func($this->ownCallback);
		$this->callOwnFriend();
	}
	
	private function callOwnFriend(){
		if($this->isCalledOwnFriend)
			return;
				
		if(CONVEYER_FREQUENCY)
			sleep(CONVEYER_FREQUENCY);
			
		set_time_limit(10);
		$sh = fsockopen($this->ownFriend['host'], 80, $errno, $errstr);
		if($errno)
		{
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 4, "Конвейер остановлен: ".$errstr);
		}
		
		fputs($sh, "GET ".($this->ownFriend['scheme'])."://".($this->ownFriend['host']).($this->ownFriend['path'])."?isSecond=".$this->isSecond."&ConveyerKey=".$this->executableKey." HTTP/1.1\n");
		fputs($sh, "Host: ".$this->ownFriend['host']."\n");
		fputs($sh, "User-Agent: Conveyer\n\n");
		
		$str = fgets($sh, 50);
		if(strpos($str, "200") === false)
		{
			$this->errorHandlerObject->Push(ERROR_HANDLER_ERROR, 4, "Конвейер остановлен");
		}
		
		stream_set_blocking($sh, 0);
		stream_set_timeout($sh, 3600);
		set_time_limit(0);

		$this->isCalledOwnFriend = true;
	}

	private function GenerateIni($arrValues, $withSection = true)
	{
		$result = "";
		
		foreach($arrValues as $key => $val)
		{
			if($withSection)
			{
				$sectionValues = $val;
				$result .= "\n\n[".$key."]\n\n";
				foreach($sectionValues as $key2 => $val2){
					$val2 = (is_string($val2))?'"'.$val2.'"':$val2;
					$result .= ($key2." = ".$val2."\n");
				}
			}
			else{
				
				$result .= ($key." = ".$val."\n\n");
			}
		}
		
		return $result;
	}
	
	private function FileLineCalc(){
		$trace = debug_backtrace();
		return array($trace[1]['file'], $trace[1]['line']);
	}
	
	private $isCalledOwnFriend = false;
	
	private $errorHandlerObject;
	
	private $name;
	private $currentCommand = "";
	private $executable;
	private $executableKey;
	private $isSecond;
	private $ownCallback;
	private $ownFriend = "";
	private $currentConfig;
	
	private $allCommands = array("end", "console", "allow_running", "force_start", "check");
}
?>