
<?php
    

	function getTitle()
	{
		global $pageTitle;
		if(isset($pageTitle))
			echo $pageTitle.' | Epoka Car Rental';
		else
			echo "Epoka Car Rental";
	}


    function countItems($item,$table)
	{
		global $con;
		$stat_ = $con->prepare("SELECT COUNT($item) FROM $table");
		$stat_->execute();
		
		return $stat_->fetchColumn();
	}

    /*

	function checkItem($select, $from, $value)
	{
		global $con;
		$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
		$statment->execute(array($value));
		$count = $statment->rowCount();
		
		return $count;
	}

	/*
		==============================================
		TEST INPUT FUNCTION, IS USED FOR SANITIZING USER INPUTS
		AND REMOVE SUSPICIOUS CHARS and Remove Extra Spaces
		==============================================

	*/

	function test_input($data) 
	{
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}




