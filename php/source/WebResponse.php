<?php

namespace com\prsolucoes;

class WebResponse
{

	const RESPONSE_TYPE_JSON = 'json';
	const RESPONSE_TYPE_XML = 'xml';

	public $responseType;
	public $response;

	function __construct($responseType = self::RESPONSE_TYPE_JSON)
	{
		$this->responseType = $responseType;
		$this->response = $this->createResponseAsArray();

		if(!in_array($responseType, array(self::RESPONSE_TYPE_JSON, self::RESPONSE_TYPE_XML)))
		{
			throw new Exception('Response type (' . $this->responseType . ') not implemented.');
		}
	}

	public function mergeModelErrors($model, $message = 'validate')
	{
		if($model)
		{
			if($model->getErrors())
			{
				$errorList = array();

				foreach ($model->getErrors() as $key => $value)
				{
					$errorList[] = array($key, $value);
				}

				if(!isset($this->response['errors']))
				{
					$this->response['data']['errors'] = array();
				}

				$this->response['data']['errors'] = array_merge($this->response['data']['errors'], $errorList);
				$this->response['message'] = $message;
			}
		}
	}

	public function setSuccess($value)
	{
		$this->response['success'] = $value;
	}

	public function getSuccess()
	{
		return $this->response['success'];
	}

	public function getMessage()
	{
		return $this->response['message'];
	}

	public function setMessage($value)
	{
		$this->response['message'] = $value;
	}

	public function setData($value)
	{
		$this->response['data'] = $value;
	}

	public function getData()
	{
		return $this->response['data'];
	}

	public function getDataValue($key, $default = null)
	{
		if(is_array($this->response) && isset($this->response['data']) && isset($this->response['data'][$key]))
		{
			return $this->response['data'][$key];
		}

		return $default;
	}

	public function setDataErrors($value)
	{
		$this->response['data']['errors'] = $value;
	}

	public function clearData()
	{
		$this->response['data'] = array();
	}

	public function addData($key, $value)
	{
		$this->response['data'][$key] = $value;
	}

	public function addDataError($field, $message)
	{
		if(!isset($this->response['data']['errors']))
		{
			$this->response['data']['errors'] = array();
		}

		$this->response['data']['errors'][] = array($field, $message);
	}

	public function __toString()
	{
		if($this->responseType == self::RESPONSE_TYPE_JSON)
		{
			return json_encode($this->response);
		}

		throw new Exception('Response type (' . $this->responseType . ') not implemented.');
	}

	private function createResponseAsArray()
	{
		return array(
			'success' => false,
			'message' => null,
			'data'    => array(
				'errors' => array(),
			),
		);
	}

}