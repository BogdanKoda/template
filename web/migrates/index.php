<?php
function pd($arr){
    echo "<pre>" . print_r($arr, 1) . "</pre>";
}
function dd($arr){
    pd($arr);
    die();
}

$d = "\r\n";

$foreignText = [];
$indicesText = [];

$priorityMigrate = [];
$priorityIndexes = [];
$priorityMultipleMigrate = [];

$needWithoutDraft = false;
if(isset($_GET['noDraft']))
    $needWithoutDraft = true;

$filename = $_SERVER["DOCUMENT_ROOT"] . "/migrates/index.sql";

$templateAttributes = [
    "id" => "ID",
    "createdAt" => "Дата создания",
    "updatedAt" => "Дата обновления",
    "userId" => "ID пользователя",
    "authorId" => "ID автора",
    "photoId" => "ID фотографии",
    "token" => "Токен",
    "tokenExpiredAt" => "Время истечение токена",
    "dateOfBirth" => "Дата рождения",
    "city" => "Город",
    "email" => "E-mail",
    "login" => "Логин",
    "phone" => "Номер телефона",
    "lastLoginAt" => "Дата последнего входа",
    "firstName" => "Имя",
    "lastName" => "Фамилия",
    "fullName" => "Полное имя",
];

if(file_exists($filename)){

    $sql = file_get_contents($filename);
    $sql = strtr($sql, ["\r" => "", "\n" => ""]);

    $sql = explode(";", $sql);
    sort($sql);
    foreach($sql as $action){
        preg_match_all('/\((((?>[^()]+)|(?R))*)\)/', $action, $createParams);
        $action = preg_replace('/\((((?>[^()]+)|(?R))*)\)/', "", $action);
        $createParams = $createParams[1] ?? [];

        $action = explode(" ", $action);

        $actionName = $action[0] ?? "";
        $actionOver = $action[1] ?? "";
        $overName = $action[2] ?? "";

        preg_match_all('/`(.*?)`/', $overName, $overName);
        $overName = $overName[1][0] ?? "";

        if($actionName != "" && $actionOver != "" && $overName != ""){
            if($actionName == "CREATE" && $actionOver == "TABLE") {

                $migrateName = getMigrationsFilename($overName);
                $migrateFilename = getMigratePath() . $migrateName;
                $migrateTable = "{{%$overName}}";
                $migrate = "<?php\r\n\r\nuse yii\db\Migration;\r\n\r\nclass $migrateName extends Migration\r\n{\r\n\r\n\tpublic function safeUp()\r\n\t{\r\n\t\t\$this->createTable(\"$migrateTable\", [";

                $modelName = ucfirst($overName);
                $modelRules = [];
                $modelAttributeLabels = "";
                $fieldsList = "";
	
	            $propertyList = "";
				$requiredBefore = "";
	
	            $createParams = preg_replace('/,(?=[^()]+\))/', '#', $createParams[0]);
	            $createParams = strtr($createParams, [',' => '#', '#' => ',']);
	            $createParams = explode("#", $createParams);

                if(!$needWithoutDraft) {
                    $createParams[] = "`isUpdate` boolean";
                }
				
                foreach($createParams as $itemParam){
					$oldItemParam = $itemParam;
                    $itemParam = explode(" ", $itemParam);
                    $tempItemParam = [];
                    foreach($itemParam as $a => $item){
                        if($item != "") $tempItemParam[] = $item;
                    }
                    $itemParam = $tempItemParam;

                    $fieldName = $itemParam[0] ?? "";
                    preg_match('/`(.*?)`/', $fieldName, $fieldName);
                    $fieldName = $fieldName[1] ?? '';

                    if($fieldName != "") {
                        $type = $itemParam[1] ?? "";
                        preg_match('/\((((?>[^()]+)|(?R))*)\)/', $type, $typeReg);
                        $v = $typeReg[1] ?? "";
                        if(isset($typeReg[1])){
                            $type = explode('(', $type)[0];
                        }
	
	                    $propertyList .= " * @property null \$$fieldName\r\n";
                        $modelAttributeLabels .= "\r\n\t\t\t\"$fieldName\" => \"".$templateAttributes[$fieldName]."\",";
                        $fieldsList .= "\t\t\t\"$fieldName\",\r\n";

                        if(i($type, "int") && $itemParam[2] == "PRIMARY" && $itemParam[3] == "KEY" && $itemParam[4] == "AUTO_INCREMENT")
                            $addMigrate = "\$this->primaryKey()";
                        else if(i($type, "bigint") && $itemParam[2] == "PRIMARY" && $itemParam[3] == "KEY" && $itemParam[4] == "AUTO_INCREMENT")
                            $addMigrate = "\$this->bigPrimaryKey()";
                        else if(i($type, "bool") || i($type, "boolean")) {
                            $addMigrate = "\$this->boolean()";
                            $modelRules["boolean"][] = $fieldName;
                        }
                        else if(i($type, "float")) {
                            $addMigrate = "\$this->float()";
                            $modelRules["float"][] = $fieldName;
                        }
                        else if(i($type, "double")) {
	                        $addMigrate = "\$this->double()";
	                        $modelRules["double"][] = $fieldName;
                        }
                        else if(i($type, "json")) {
	                        $addMigrate = "\$this->json()";
	                        $modelRules["string"][] = $fieldName;
                        }
                        else if(i($type, "text")) {
                            $addMigrate = "\$this->text()";
                            $modelRules["string"][] = $fieldName;
                        }
                        else if(i($type, "bigint")) {
                            $addMigrate = "\$this->bigInteger($v)";
                            $modelRules["integer"][] = $fieldName;
                        }
                        else if(i($type, "int") || i($type, "integer")) {
                            $addMigrate = "\$this->integer($v)";
                            $modelRules["integer"][] = $fieldName;
                        }
                        else if(i($type, "date")) {
                            $addMigrate = "\$this->date()";
                            $modelRules["safe"][] = $fieldName;
                        }
                        else if(i($type, "datetime")) {
                            $addMigrate = "\$this->dateTime()";
                            $modelRules["safe"][] = $fieldName;
                        }
                        else if(i($type, "time")) {
                            $addMigrate = "\$this->time()";
                            $modelRules["safe"][] = $fieldName;
                        }
                        else if(i($type, "string") || i($type, "varchar") || i($itemParam[1] ?? "", "email")) {
                            $addMigrate = "\$this->string($v)";
                            $modelRules["string"][] = $fieldName;
                        }
                        else if(i($type, "ENUM")) {
							preg_match('/ENUM[ ]*\(([,\' A-Za-z0-9]+)\)/', $oldItemParam, $enumV);
							$enumV = $enumV[1];
	                        $addMigrate = "\"ENUM($enumV)\"";
	                        $modelRules["in"][] = $fieldName;
                        }
                        else{
                            dd("Нет значения для значения $type");
                        }

                        if(i($itemParam[1] ?? "", "email")){
                            $modelRules["email"][] = $fieldName;
                        }

                        if($itemParam[2] == "UNIQUE")
                            $addMigrate .= "->unique()";

                        $migrate .= "\r\n\t\t\t'$fieldName' => $addMigrate,";
						
						if($fieldName != "id"){
							$requiredBefore .= "\t\t\$this->$fieldName = null;\r\n";
						}
                    }
					

                }

                $migrate .= "\r\n\t\t]);\r\n\r\n" . ($indicesText[$overName]['mig']['add'] ?? "") . "\r\n\r\n" . ($foreignText[$overName]['mig']['add'] ?? "") . "\r\n\r\n\t}\r\n\r\n\tpublic function safeDown()\r\n\t{\r\n".($foreignText[$overName]['mig']['rem'] ?? "")."\r\n\r\n".($indicesText[$overName]['mig']['rem'] ?? "")."\r\n\r\n\t\t\$this->dropTable(\"$migrateTable\");\r\n\t}\r\n\r\n}";

                $migrateFilename .= ".php";
                if(!file_exists($migrateFilename)) {
	                file_put_contents($migrateFilename, $migrate);
                }
                else
                    pd("$migrateFilename уже существует");


                $modelRulesText = "";
                foreach($modelRules as $name => $modelList){
                    foreach($modelList as $j => $it){
                        $modelList[$j] = "'$it'";
                    }
                    $modelList = implode(", ", $modelList);
                    $modelRulesText .= "\r\n\t\t\t[[$modelList], \"$name\"],";

                }

                $modelName = explode("_", $modelName);
                foreach ($modelName as $index => $item) {
                    $modelName[$index] = ucfirst($item);
                }
                $modelName = implode("", $modelName);

                if(!$needWithoutDraft) {
                    $model = "<?php
namespace app\\models;

use app\\components\\ModelHelperFunctions;
use yii\\base\\Exception;

/**
$propertyList
 */
class $modelName extends $modelName" . "Required
{
	public function rules(): array
	{
		\$rules = parent::rules();
		\$rules[] = [[], \"required\"];
		return \$rules;
	}

	public function beforeValidate(): bool
	{
		if (\$this->isNewRecord) {			
			//\$this->createdAt = ModelHelperFunctions::getDate();
		}
		\$this->isUpdate = 1;
		
		//\$this->updatedAt = ModelHelperFunctions::getDate();
		
		return parent::beforeValidate();
	}

	public function fields(): array
	{
	    return parent::fields();
	}
}
";
                }
                else{
                    $model = "<?php
namespace app\\models;

use app\\models\\extend\\BaseModel;

/**
$propertyList
*/
class $modelName extends BaseModel
{
	public static function tableName(): string
	{
		return '$overName';
	}

	public function rules(): array
	{
		return [$modelRulesText" . $foreignText[$overName]['model']['add'] . "
		];
	}
	
	public function beforeValidate(): bool
	{
		if (\$this->isNewRecord) {			
			//\$this->createdAt = ModelHelperFunctions::getDate();
		}
		
		//\$this->updatedAt = ModelHelperFunctions::getDate();
		
		return parent::beforeValidate();
	}
	
	public function attributeLabels(): array
	{
		return [$modelAttributeLabels
		];
	}
	
	public function fields(): array
	{
	    return [
$fieldsList	    
	    ];
	}
}";
                }

                    $modelFilename = getModelPath() . $modelName . ".php";

                    if (!file_exists($modelFilename)) {
	                    file_put_contents($modelFilename, $model);
                    }

                if(!$needWithoutDraft) {
                    $model = "<?php
namespace app\\models;

use app\\models\\extend\\BaseModel;

class $modelName" . "Required extends BaseModel
{
	public static function tableName(): string
	{
		return '$overName';
	}

	public function rules(): array
	{
		return [$modelRulesText" . $foreignText[$overName]['model']['add'] . "
		];
	}
	
	public function beforeValidate(): bool
	{
	
		return parent::beforeValidate();
	}
	
	public function attributeLabels(): array
	{
		return [$modelAttributeLabels
		];
	}
	
	public function fields(): array
	{
	    return parent::fields();
	}
}
";

                    $modelFilename = getModelPath() . $modelName . "Required.php";

                    if (!file_exists($modelFilename)) {
	                    file_put_contents($modelFilename, $model);
                    }
                }

            }
            else if($actionName == "ALTER" && $actionOver == "TABLE"){
                $actionTemp = [];
                foreach($action as $aItem){
                    if($aItem != "") $actionTemp[] = $aItem;
                }
                $action = $actionTemp;
                foreach($createParams as $cpi => $par){
                    preg_match('/`(.*?)`/', $par, $par);
                    $createParams[$cpi] = $par[1];
                }

                preg_match('/`(.*?)`/', $action[7], $action[7]);
                $action[7] = $action[7][1];

                $priorityMigrate[] = [$action[7], $overName];

                if(!isset($indicesText[$overName]['mig']['add']))
                    $indicesText[$overName]['mig']['add'] = "";
                if(!isset($indicesText[$overName]['mig']['rem']))
                    $indicesText[$overName]['mig']['rem'] = "";

                if(!isset($foreignText[$overName]['mig']['add']))
                    $foreignText[$overName]['mig']['add'] = "";
                if(!isset($foreignText[$overName]['mig']['rem']))
                    $foreignText[$overName]['mig']['rem'] = "";

                if(!isset($foreignText[$overName]['model']['add']))
                    $foreignText[$overName]['model']['add'] = "";

                $foreignText[$overName]['model']['add'] .= "\r\n\t\t\t[['".$createParams[0]."'],\r\n\t\t\t\t'exist',\r\n\t\t\t\t'skipOnError'     => true,\r\n\t\t\t\t'targetClass'     => ".ucfirst($action[7])."::class,\r\n\t\t\t\t'targetAttribute' => ['".$createParams[0]."' => '".$createParams[1]."']\r\n\t\t\t],";

                $foreignText[$overName]['mig']['add'] .= "\t\t\$this->addForeignKey(\r\n\t\t\t'{{%fk-$overName-".$createParams[0]."}}',\r\n\t\t\t'{{%$overName}}',\r\n\t\t\t'".$createParams[0]."',\r\n\t\t\t'{{%".$action[7]."}}',\r\n\t\t\t'".$createParams[1]."',\r\n\t\t\t'CASCADE'\r\n\t\t);\r\n";
                $foreignText[$overName]['mig']['rem'] .= "\t\t\$this->dropForeignKey(\r\n\t\t\t'{{%fk-$overName-".$createParams[0]."}}',\r\n\t\t\t'{{%$overName}}');\r\n";

                $indicesText[$overName]['mig']['add'] .= "\t\t\$this->createIndex(\r\n\t\t\t'{{%idx-$overName-".$createParams[0]."}}',\r\n\t\t\t'{{%$overName}}',\r\n\t\t\t'".$createParams[0]."'\r\n\t\t);\r\n";
                $indicesText[$overName]['mig']['rem'] .= "\t\t\$this->dropIndex(\r\n\t\t\t'{{%idx-$overName-".$createParams[0]."}}',\r\n\t\t\t'{{%$overName}}'\r\n\t\t);\r\n";
            }

        }

    }

}


function getMigratePath(): string
{
    $root = $_SERVER["DOCUMENT_ROOT"];
    $root = explode("/", $root);
    unset($root[count($root) - 1]);

    $root = implode("/", $root);
    $root = $root . "/migrations/";

    if(!is_dir($root))
        mkdir($root);

    return $root;
}

function getModelPath(): string
{
    $root = $_SERVER["DOCUMENT_ROOT"];
    $root = explode("/", $root);
    unset($root[count($root) - 1]);

    $root = implode("/", $root);
    $root = $root . "/models/";

    if(!is_dir($root))
        mkdir($root);

    return $root;
}

function getMigrationsFilename($name): string
{
    global $priorityMigrate;

    foreach ($priorityMigrate as $mig){
        if($mig[0] == $name && !isset($mig[2])) {
            getIndexPriority();
        }
    }

    $index = 99;
    foreach ($priorityMigrate as $mig){
        if($mig[0] == $name && $index > $mig[2]) {
            $index = $mig[2];
        }
    }


    return "m".date("ymd")."_".$index.date("is")."_create_$name"."_table";
}

function i($value, string $string): bool
{
    return $value == $string;
}

function getIndexPriority()
{
    global $priorityMultipleMigrate, $priorityMigrate;
    foreach ($priorityMigrate as $mig){
        $priorityMultipleMigrate[$mig[0]][] = $mig[1];
    }

    foreach($priorityMultipleMigrate as $ref => $table){
        $index = setPriority($ref);

        foreach($priorityMigrate as $priorityName => $p){
            if($p[0] == $ref){
                $priorityMigrate[$priorityName][2] = $index+1;
            }
        }
    }

    foreach($priorityMultipleMigrate as $table){
        foreach($table as $t) {
            $index = setPriority($t);
            foreach ($priorityMigrate as $priorityName => $p) {
                if ($p[0] == $t) {
                    $priorityMigrate[$priorityName][2] = $index + 3;
                }
            }
        }
    }

}

function setPriority($ref){
    global $priorityMigrate, $priorityMultipleMigrate;

    $maxRefPrior = 10;
    foreach($priorityMultipleMigrate as $mig){
        foreach($mig as $m){
            if($m == $ref){
                foreach($priorityMigrate as $pri){
                    if($pri[0] == $ref){
                        if(isset($pri[2])){
                            if($maxRefPrior < $pri[2]) {
                                $maxRefPrior = $pri[2];
                            }
                        }
                    }
                }
            }
        }
    }
    return $maxRefPrior;

}