<?php
namespace Siciarek\AdRotatorBundle\Doctrine\DBAL\Types;

class GenderType extends EnumType
{
    const UNKNOWN = 'unknown';
    const MALE = 'male';
    const FEMALE = 'female';
    const BOTH = 'both';

    protected $name = 'gender';
    protected $default = self::UNKNOWN;
    protected $values = array(
        self::UNKNOWN,
        self::MALE,
        self::FEMALE,
        self::BOTH,
    );

    public static function getValues() {
        return self::getType('gender')->values;
    }

    public static function getDefault() {
        return self::getType('gender')->default;
    }
}