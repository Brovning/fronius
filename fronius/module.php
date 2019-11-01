<?php
	class Fronius extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			//Properties
			$this->RegisterPropertyString('IP', '');
			$this->RegisterPropertyString('Port', '');

		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
		}

	}