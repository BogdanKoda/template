<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class KodaController extends Controller
{

    public function actionIndex($name, $oneId = ""): int
    {
        $modelName = ucfirst($name);
        $modelPhoto = $modelName . "Photos";

        $controllerName = $modelName . "Controller";

        $filePath = Yii::getAlias('@app') . '\\controllers\\'.$controllerName.".php";
        if(file_exists($filePath)){
            print "Такой контроллер уже существует";
            return ExitCode::OK;
        }
		
        $useOneId = "";
        $controllerData = '';
        if($oneId != '') {
            $controllerData = "
			\"modelPhotos\" => [
				new PhotoUploadData(
					new $modelPhoto(),
					\"photo\",
					\"$oneId\"
				)
			]
		";
            $useOneId = "use app\\models\\$modelPhoto;";
        }

        $controllerCode = "<?php

namespace app\\controllers;

use app\\components\\PhotoUploadData;
use app\\components\\service\\CrudActionsImpl;
use app\\components\\service\\CrudActionsService;
use app\\components\\Strategy\\ModelHelper;
use app\\components\\Strategy\\Strategy;
use app\\models\\extend\\BaseModel;
use app\\models\\$modelName;
$useOneId

class $controllerName extends CrudController
{
    public function getModel(): BaseModel
    {
        return new $modelName();
    }
    
    public function controllerData(): array
    {
		return [$controllerData];
    }

	public function getStrategy(): Strategy
	{
		return new ModelHelper(\$this->getModel(), \$this->controllerData());
	}
	
	public function crudService(): CrudActionsImpl
	{
		return new CrudActionsService(\$this->getStrategy());
	}
}";

        file_put_contents($filePath, $controllerCode);

        return ExitCode::OK;
    }


}