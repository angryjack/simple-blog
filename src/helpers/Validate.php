<?php
/**
 * Created by angryjack
 * Date: 2018-12-23 20:23
 */

namespace Angryjack\helpers;

use Angryjack\exceptions\BaseException;

trait Validate
{
    /**
     * Проводим валидацию данных
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function makeValidation(array $data) : bool
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                throw new BaseException('Проверяемое значение не имеет параметров.');
            }

            $rules = explode('|', $value);

            if ($rules[0] === 'str') {
                $this->checkString($key, array_slice($rules, 1));
            } elseif ($rules[0] === 'int') {
                $this->checkInteger($key, array_slice($rules, 1));
            } else {
                throw new BaseException('Данный формат не поддерживается.');
            }
        }

        return true;
    }

    /**
     * Валидация строки
     * @param $string
     * @param $rules
     * @return bool
     */
    private function checkString($string, $rules)
    {
        if (! is_string($string)) {
            return false;
        }

        foreach ($rules as $rule) {
            $sign = explode(':', $rule);

            if ($sign[0] === 'max' && strlen($string) > $sign[1]) {
                return false;
            } elseif ($sign[0] === 'min' && strlen($string) < $sign[1]) {
                return false;
            }
        }
        return true;
    }

    /**
     * Валидация числа
     * @param $integer
     * @param $rules
     * @return bool
     */
    private function checkInteger($integer, $rules)
    {
        if (! is_int($integer)) {
            return false;
        }

        foreach ($rules as $rule) {
            $sign = explode(':', $rule);

            if ($sign[0] === 'max' && $integer > $sign[1]) {
                return false;
            } elseif ($sign[0] === 'min' && $integer < $sign[1]) {
                return false;
            }
        }
        return true;
    }
}
