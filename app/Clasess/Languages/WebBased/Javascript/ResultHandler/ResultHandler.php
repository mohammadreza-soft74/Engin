<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:29 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\ResultHandler;

use App\Clasess\Base\BaseResultHandler\BaseResultHandler;

class ResultHandler extends BaseResultHandler
{
	public function create($result)
	{

		if ($result['result'])
		{
			$res=[
				'error' => false,
				'result' => ['running'=>true]
			];
		}
		return $res;
	}

	public function pageLoad($result)
	{
		return[
			'error' => false,
			'result' => $result['files']
		];
	}

	public function run($result)
	{
		return[
			'error' => false,
			'result' => $result['path']
		];
	}

	public function codes($result)
	{
		return[
			'error' => false,
			'result'=> $result['result']
		];
	}
}