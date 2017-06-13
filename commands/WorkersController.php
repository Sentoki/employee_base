<?php

namespace app\commands;

use yii\console\Controller;

/**
 * Вспомогательные мелочи
 *
 * Class WorkersController
 *
 * @package app\commands
 */
class WorkersController extends Controller
{
    private function apiRequest(bool $is_male = true, int $number) : array
    {
        
    }


    /**
     * Генерирует список работников чтобы не набивать руками
     */
    public function actionGenerate()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $paramPost = [
            'fam' => 1,
            'imya' => 1,
            'otch' => 0,
            'pol' => 1,
            'count' => '250',
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, $paramPost);
        curl_setopt($curl, CURLOPT_URL, 'http://freegenerator.ru/fio');
        $out = trim(strip_tags(curl_exec($curl)));
        echo $out;
        $error = curl_error($curl);
        if (strlen($error) > 0) {
            echo 'error: ' . $error;
        }
        curl_close($curl);
    }
}
