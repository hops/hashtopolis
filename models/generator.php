<?php

$CONF = array();

//config SQL here
$CONF['Bill'] = array(
		'billId',
		'userId',
		'isPaid',
		'userIsNotified',
		'notes'
);

foreach($CONF as $NAME => $COLUMNS){

	$class = "<?php\n/*\n * \$Id: \$\n */\n\nclass ";
	$class .= $NAME;
	$class .= " extends AbstractModel {\n	private \$modelName = \"";
	$class .= $NAME;
	$class .= "\";\n	\n	// Modelvariables\n";
	for($x=0;$x<sizeof($COLUMNS);$x++){
		$class .= "	private \$".$COLUMNS[$x].";\n";
	}
	$class .= "	\n	public function getName() {\n		return \$this->modelName;\n	}\n	\n	function __construct(";
	for($x=0;$x<sizeof($COLUMNS);$x++){
		$class .= "\$".$COLUMNS[$x];
		if($x < sizeof($COLUMNS) - 1){
			$class .= ", ";
		}
	}
	$class .= ") {\n";
	for($x=0;$x<sizeof($COLUMNS);$x++){
		$class .= "		\$this->".$COLUMNS[$x]." = \$".$COLUMNS[$x].";\n";
	}
	$class .= "\n	}\n	\n	function getKeyValueDict() {\n		\$dict = array ();\n";
	for($x=0;$x<sizeof($COLUMNS);$x++){
		$class .= "		\$dict['".$COLUMNS[$x]."'] = \$this->".$COLUMNS[$x].";\n";
	}
	$class .= "		\n		return \$dict;\n	}\n	\n	function getPrimaryKey() {\n		return \"";
	$class .= $COLUMNS[0];
	$class .= "\";\n	}\n	\n	function getPrimaryKeyValue() {\n		return \$this->";
	$class .= $COLUMNS[0];
	$class .= ";\n	}\n	\n	function getId() {\n		return \$this->";
	$class .= $COLUMNS[0];
	$class .= ";\n	}\n	\n	function setId(\$id) {\n		\$this->";
	$class .= $COLUMNS[0];
	$class .= " = \$id;\n	}\n";
	for($x=1;$x<sizeof($COLUMNS);$x++){
		$name = $COLUMNS[$x];
		$name[0] = strtoupper($name[0]);
		$class .= "\n	function get".$name."(){\n";
		$class .= "		return \$this->".$COLUMNS[$x].";\n";
		$class .= "	}\n";
		$class .= "\n	function set".$name."(\$".$COLUMNS[$x]."){\n";
		$class .= "		\$this->".$COLUMNS[$x]." = \$".$COLUMNS[$x].";\n";
		$class .= "	}\n";
	}
	$class .= "}\n";

	if(!file_exists(dirname(__FILE__)."/".$NAME.".class.php")){
		file_put_contents(dirname(__FILE__)."/".$NAME.".class.php", $class);
	}

	//create factory
	$factory = "<?php\n/*\n * \$Id: \$\n */\n\nclass ";
	$factory .= $NAME;
	$factory .= "Factory extends AbstractModelFactory {\n	function getModelName() {\n		return \"";
	$factory .= $NAME;
	$factory .= "\";\n	}\n	\n	function getModelTable() {\n		return \"";
	$factory .= $NAME;
	$factory .= "\";\n	}\n	\n	function isCachable() {\n		return false;\n	}\n	\n	function getCacheValidTime() {\n		return - 1;\n	}\n	\n	function getNullObject() {\n		\$o = new ";
	$factory .= $NAME;
	$factory .= "(-1";
	for($x=0;$x<sizeof($COLUMNS) - 1;$x++){
		$factory .= ", null";
	}
	$factory .= ");\n		return \$o;\n	}\n	\n	function createObjectFromDict(\$pk, \$dict) {\n		\$o = new ";
	$factory .= $NAME;
	$factory .= "(\$pk";
	for($x=1;$x<sizeof($COLUMNS);$x++){
		$factory .= ", \$dict['".$COLUMNS[$x]."']";
	}
	$factory .= ");\n		return \$o;\n	}\n}";
	
	if(!file_exists(dirname(__FILE__)."/".$NAME."Factory.class.php")){
		file_put_contents(dirname(__FILE__)."/".$NAME."Factory.class.php", $factory);
	}
}