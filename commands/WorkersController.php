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
    private function apiRequest(bool $is_male = true, int $number)
    {
        if (!extension_loaded('intl')){
            echo "Требуется расщирение intl\nsudo apt-get install php7.0-intl";
            exit;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        $paramPost = [
            'fam' => 1,
            'imya' => 1,
            'otch' => 0,
            'pol' => $is_male,
            'count' => $number,
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, $paramPost);
        curl_setopt($curl, CURLOPT_URL, 'http://freegenerator.ru/fio');
        $out = trim(strip_tags(curl_exec($curl)));
        $error = curl_error($curl);
        if (strlen($error) > 0) {
            throw new \Exception($error);
        }
        curl_close($curl);
        $temp_names = explode('  ', $out);
        foreach ($temp_names as $name) {
            $lastname_and_firstname = explode(' ', trim($name));
            $year = rand(1950, 1999);
            $birth = $year . '-' . rand(1, 12) . '-' . rand(1, 28);
            $employment_date = rand(2010, 2017) . '-' . rand(1, 12) . '-' . rand(1, 28);

            $rand_month = rand(1, 10);
            $leaveDate = (new \DateTime($employment_date))->modify("+{$rand_month} month");
            $leaveDate = $leaveDate->format('Y-m-d');
            $leave_probability = [true, false, false, false, false];
            $leave_date = $leave_probability[array_rand($leave_probability)] ? $leaveDate : null;

            $email = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; Lower()',
                $lastname_and_firstname[0] . '_' . $lastname_and_firstname[1] . '_' . $year
            );
            $email = str_replace("'", '', $email . '@mail.ru');
            $position_id = rand(1, 5);
            $department_id = rand(1, 8);
            $phone = rand(71111111111, 79999999999);
            echo "['{$lastname_and_firstname[1]}', '{$lastname_and_firstname[0]}', '{$birth}', '{$email}',".
                "'+{$phone}', '{$employment_date}', '{$leave_date}', {$position_id}, $department_id],\n";
        }
    }


    /**
     * Генерирует список работников чтобы не набивать руками
     */
    public function actionGenerate()
    {
        echo "[\n";
        $this->apiRequest(true, 400);
        $this->apiRequest(false, 400);
        echo "]";
    }
}
