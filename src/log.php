<?php
namespace RusaDrako\log;

class log {
	/** @var string Разделение файла логов по неделям */
	const SEPARATION_WEEK='WEEK';
	/** @var string Разделение файла логов по дням */
	const SEPARATION_DAY='DAY';
	/** @var string Разделение файла логов по часам */
	const SEPARATION_HOUR='HOUR';
	/** @var string Без разделение файла логов */
	const SEPARATION_NO='NO';

	/** @var string Папка файла логирования */
	protected $folder='';
	/** @var string Имя файла логирования */
	protected $file='';

	/**
	 * log constructor.
	 * @param $file
	 * @param string $separation
	 */
	public function __construct($file, $separation=self::SEPARATION_NO){
		$path_parts=pathinfo($file);
		$this->folder=$path_parts['dirname'] . '/';
		# Формируем имя файла лога с учётом сепарации и запоминаем
		$this->file=$this->createFileName($path_parts['filename'], $separation).'.'.$path_parts['extension'];
	}

	/**
	 * Возвращает реальное имя файла логирования
	 * @return string
	 */
	public function getFile(){
		return $this->folder . $this->file;
	}

	/**
	 * Возвращает последние n строк теку3щего файла логирования
	 * @param int $count
	 * @return array|false
	 * @throws ExceptionLog
	 */
	public function getLastRows($count=10){
		try{
			$lines = file($this->getFile());
			$last_10 = array_slice($lines , -$count);
			return $last_10;
		} catch(\Exception $e){
			throw new ExceptionLog("Невозможно считать данные из файла {$this->getFile()}: {$e->getMessage()}");
		}
	}

	/**
	 * Формирует имя файла
	 * @param $file_name
	 * @param $separation
	 * @return string
	 */
	private static function createFileName($file_name, $separation){
		switch($separation){
			case static::SEPARATION_WEEK:
				$day = date('w');
				$add_file_name=date('_Y.m.d', strtotime('-'.$day.' days'));
				break;
			case static::SEPARATION_DAY:
				$add_file_name=date('_Y.m.d');
				break;
			case static::SEPARATION_HOUR:
				$add_file_name=date('_Y.m.d_H-00-00');
				break;
			case static::SEPARATION_NO:
			default:
				$add_file_name='';
				break;
		}
		return $file_name.$add_file_name;
	}

	/**
	 * Добавляет запись в лог
	 * @param $file
	 * @param $message
	 * @param int $addDate
	 * @throws ExceptionLog
	 */
	public function addLog($message, $addDate=1){
		$message=$this->addDateToMessage($message, $addDate);
		$message=$this->addLineBreakToMessage($message);
		$this->controlExistsDir($this->folder);
		$this->writeData($this->getFile(), $message);
	}

	/**
	 * Добавляет дату к сообщению
	 * @param $message
	 * @param int $addDate
	 * @return string
	 */
	private static function addDateToMessage($message, $addDate=1){
		if($addDate){
			$message=date('Y-m-d H:i:s: ') . $message;
		}
		return $message;
	}

	/**
	 * Добавляет перенос строки
	 * @param $message
	 * @param int $addDate
	 * @return string
	 */
	private static function addLineBreakToMessage($message){
		return $message . PHP_EOL;
	}

	/**
	 * Проверяет существорвание папки логирования
	 * @param $file
	 * @param string $mode
	 * @throws ExceptionLog
	 */
	private static function controlExistsDir($folder, $mode='0777'){
		if(!file_exists($folder)){
			if(!mkdir($folder, $mode, 1)){
				throw new ExceptionLog("Невозможно создать папку {$folder}");
			}
		}
	}

	/**
	 * Записывает данные в файл
	 * @param $file
	 * @param $message
	 */
	private static function writeData($file, $message){
		try{
			file_put_contents($file, $message, FILE_APPEND);
		} catch(\Exception $e){
			throw new ExceptionLog("Невозможно записать данные в файл {$file}: {$e->getMessage()}");
		}
	}

}

class ExceptionLog extends \Exception{}