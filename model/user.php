<?php
class User
{
        public $numero;
		public $password;
		public $in_love;

        public function toDB()
        {
                $object = get_object_vars($this);
                return $object;
        }
}