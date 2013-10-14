<?php
namespace Siciarek\AdRotatorBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    protected $default;
    protected $name;
    protected $values = array();

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // TODO: rozkminić domyślne wartości w enumie, na razie niewspierane
        $this->default = null;

        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, $this->values);

        $vals = implode(', ', $values);

        $decl = ($this->default !== null)
            ? sprintf("ENUM(%s) DEFAULT '%s'",
                $vals,
                $this->default
            )
            : sprintf("ENUM(%s)",
                $vals
            );

        return $decl;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value !== null and !in_array($value, $this->values)) {
            $msg = sprintf('Value "%s" is not a proper %s value.', $value, $this->name);
            throw new \InvalidArgumentException($msg);
        }
        return $value;
    }

    public function getName()
    {
        return $this->name;
    }
}
