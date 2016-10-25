<?php

namespace Bigcommerce\Api\Resources;

use Bigcommerce\Api\Resource;
use Bigcommerce\Api\Client;

class CustomerGroup extends Resource
{

	protected $ignoreOnCreate = array(
		'id',
	);

	protected $ignoreOnUpdate = array(
		'id',
	);

	public function create()
	{
        // Not implemented
	}
	
	public function update()
	{
		return Client::updateCustomerGroup($this->id, $this->getUpdateFields());
	}
	
	public function delete()
	{
        // Not implemented
	}

}
