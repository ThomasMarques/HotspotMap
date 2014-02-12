<?php

namespace HotspotMap\service;


class DataAccessLayer extends \PDO
{
    /**
     * @param string $dsn
     * @param string $user
     * @param string $password
     */
    public function __construct($dsn, $user, $password)
    {
        parent::__construct($dsn, $user, $password);
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return bool Returns `true`on success, `false`otherwise
     */
    public function executeQuery($query, array $parameters = [])
    {
        $stmt = $this->prepare($query);

        foreach ($parameters as $name => $value)
        {
            if(is_numeric($value))
            {
                $paramType = \PDO::PARAM_INT;
            }
            elseif(is_null($value))
            {
                $paramType = \PDO::PARAM_NULL;
            }
            elseif(is_string($value))
            {
                $paramType = \PDO::PARAM_STR;
            }

            $stmt->bindValue(':' . $name, $value,$paramType);
            print ':' . $name . " -> " . $value . " \\ " .$paramType . "\n";
        }

        return $stmt->execute();
    }
} 