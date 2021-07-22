<?php
abstract class modelBase
{
	protected $db;

	public function __construct()
	{
		$this->db = SPDO::singleton();
	}
}
?>
