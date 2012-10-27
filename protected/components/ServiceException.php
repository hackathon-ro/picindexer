<?php 

/**
 * Generic exception class for API errors
 * @author Tudor
 *
 */
class ServiceException extends CException {
	/**
	 * Raises an exception depending on the service it is used for. Passes on all arguments
	 * except the first as constructor arguments for the new exception instance
	 * 
	 * @param string $type defines the service for which the exception is thrown
	 * @see Exceptions below for a list of valid values for $type
	 */
	public function __construct($type) {
		$classname = ucfirst(strtolower(trim($type))).'Exception';
		$args = array_splice(func_get_args(), 1);
		
		$class = new ReflectionClass($classname);
		
		Yii::trace(CVarDumper::dumpAsString($args));
		
		$e = $class->newInstanceArgs($args);
		
		throw $e;
	}
}

class FacebookException extends CException {
	protected $error;
	protected $error_reason;
	protected $error_description;
	
	public function __construct($error, $error_reason, $error_description) {
		$this->error = $error;
		$this->error_reason = $error_reason;
		$this->error_description = $error_description;
		
		$this->message = sprintf(
			'Facebook error: %s. Reason: %s. Details: %s',
			$this->error,
			$this->error_reason,
			$this->error_description
		);
	}
}