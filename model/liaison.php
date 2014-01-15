<?php
class Liaison
{
        public $user1;
		public $user2;

        public function toDB()
        {
                $object = get_object_vars($this);
                return $object;
        }
}