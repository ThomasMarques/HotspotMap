<?php

namespace HotspotMap\dal\MySqlImplementation;


class Connexion extends \PDO
{
    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=hotspotmap";
        $user = "root";
        $password = "";
        parent::__construct($dsn, $user, $password);
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return \PDOStatement
     */
    private function prepareStatement($query, $parameters = [])
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
        }

        return $stmt;
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return bool Returns `true` on success, `false` otherwise
     */
    public function executeQuery($query, $parameters = [])
    {
        $stmt = $this->prepareStatement($query, $parameters);
        return $stmt->execute();
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return bool Returns results array on success, `false` otherwise
     */
    public function selectQuery($query, $parameters = [])
    {
        $stmt = $this->prepareStatement($query, $parameters);
        if($stmt->execute())
        {
            return $stmt->fetchAll();
        }
        return false;
    }
} 