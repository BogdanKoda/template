<?php

namespace app\controllers;

use app\components\dto\DTO;
use app\components\excel\Excel;
use app\components\excel\ExcelFontBuilder;
use app\components\excel\ExcelHelper;
use app\components\Exceptions\ModelException;
use app\components\Response;
use app\components\services\UsersService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Yii;
use yii\base\Exception as yiiException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

class UsersController extends CrudController
{

    /**
     * @route 'GET users/me'
     * @secure user, admin
     *
     * @throws NotFoundHttpException
     */
    public function me(): array
    {
        return Response::success($this->service()->show(Yii::$app->user->getId()))->return();
    }

    /**
     * @route 'POST users/logo'
     * @secure user
     *
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @throws ModelException
     */
    public function logo(): array
    {
        return $this->update(Yii::$app->user->identity->getId());
    }

    /**
     * @route 'POST /users/login'
     * @throws ForbiddenHttpException
     * @throws yiiException
     */
    public function login(): array
    {
        return Response::success($this->service()->login(DTO::handle($this->dto, $this->post)))->return();
    }

    public function service(): UsersService
    {
        return Yii::$container->get(UsersService::class);
    }


    /**
     * @route 'GET users/test'
     */
    public function test()
    {
        $excel = new Excel();

        try {
            $excel->cellBuilder(ExcelHelper::coords(2, 2), "Hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world, hello world")
                ->withMergeCells("B2:F6")
                ->setVerticalAlignment('center')
                ->setHorizontalAlignment('right')
                ->setFont(ExcelFontBuilder::init()->setSize(13)->useBoldStyle()->useStrikeStyle()->useUnderlineStyle()->useItalicStyle())
                ->setFontColor("#00FFFF")
                ->setBackgroundColor("#FFFFFF")
                ->setBgFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->setBorderType(\PHPExcel_Style_Border::BORDER_SLANTDASHDOT)
                ->setBorderColor("#FF00FF")
                ->useWrap();

            $excel->cellBuilder("G1", "Привет!")
                ->setFont(ExcelFontBuilder::init()->setSize(22));

            $excel->save(false, "test.xlsx");
        } catch (Exception | \PhpOffice\PhpSpreadsheet\Exception $e) {
            var_dump($e->getMessage());
        }
        die();
    }
}
