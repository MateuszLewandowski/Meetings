<?php 

namespace App\Core\Factory;

use App\Entity\FactorableInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;

abstract class FactoryAbstract
{
    protected array $required = [];

    public abstract function create(array $arguments = []);

    protected function make(FactorableInterface $object, array $arguments = []) 
    {
        foreach ($this->required as $key) {
            if (!array_key_exists($key, $arguments)) {
                throw new InvalidArgumentException("Required argument is missing - {$key}.");
            }
        }
        foreach ($arguments as $key => $value) {
            $method = $this->camelCase(
                method: implode(separator: '', array: ['set', ucfirst($key)])
            );
            if (!method_exists($object, $method)) {
                throw new InvalidMethodNameException($method);
            }
            $object->{$method}($value);
        }
        return $object;
    }

    private function camelCase(string $method): string {
        $parts = explode('_', $method);
        $len = count($parts);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $parts[$i] = ucfirst($parts[$i]);
            }
        }
        return implode('', $parts);
    }
}