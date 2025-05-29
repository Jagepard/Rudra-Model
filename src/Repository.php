<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Pagination;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Session;
use Rudra\Exceptions\RudraException;
use Rudra\Redirect\RedirectFacade as Redirect;
use Rudra\Validation\ValidationFacade as Validation;

class Repository
{
    public string $table;
    private \PDO $DSN;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->DSN   = Rudra::get("DSN");
    }

    public function __call($method, array $parameters = [])
    {
        throw new RudraException(sprintf('Method %s does not exists', $method));
    }

    /**
     * Represents a prepared database query and, when the query is executed, the corresponding result set.
     * ---------------------------------------------------------------------------------------------------
     * Представляет подготовленный запрос к базе данных, а после выполнения запроса соответствующий результирующий набор. 
     *
     * @param  $queryString
     * @param array $queryParams
     * @return void
     */
    public function qBuilder($queryString, array $queryParams = []): array
    {
        $stmt = $this->DSN->prepare($queryString);
        $stmt->execute($queryParams);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllPerPage(Pagination $pagination, string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $qString = QBFacade::select($fields)
            ->from($this->table)
            ->orderBy("id DESC")
            ->limit($pagination->getPerPage())->offset($pagination->getOffset())
            ->get();

        return $this->qBuilder($qString);
    }

    public function find($id): array|false
    {
        $stmt = $this->DSN->prepare("
                SELECT * FROM {$this->table}
                WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll(string $sort = 'id ASC', string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $table   = $this->table;
        $qString = QBFacade::select($fields)
            ->from($table)
            ->orderBy($sort)
            ->get();

        return self::qBuilder($qString);
    }

    public function numRows()
    {
        $table = $this->table;
        $count = $this->DSN->query("SELECT COUNT(*) FROM {$table}");

        return $count->fetchColumn();
    }

    public function findBy($field, $value)
    {
        $table = $this->table;
        $stmt  = $this->DSN->prepare("
                SELECT * FROM {$table}
                WHERE {$field} = :val
        ");

        $stmt->execute([
            ":val" => $value,
        ]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->DSN->lastInsertId();
    }

    public function update(array $fields)
    {
        $id = $fields['id'];
        unset($fields['id']);
        $stmtString   = $this->updateStmtString($fields);
        $fields['id'] = $id;

        $query = $this->DSN->prepare("
                UPDATE {$this->table} SET {$stmtString}
                WHERE id =:id");

        $query->execute($fields);
    }

    public function create(array $fields)
    {
        $table      = $this->table;
        $stmtString = $this->createStmtString($fields);

        $query = $this->DSN->prepare("
                INSERT INTO {$table} ({$stmtString[0]})
                VALUES ({$stmtString[1]})");

        $query->execute($fields);
    }

    public function delete($id)
    {
        $table = $this->table;
        $query = $this->DSN->prepare("DELETE FROM {$table} WHERE id = :id");
        $query->execute([':id' => $id]);
    }

    /**
     * Prepares a row to update the database
     *
     * @param  array $fields
     * @return void
     */
    protected static function updateStmtString(array $fields)
    {
        $stmtFields = [];

        foreach ($fields as $key => $data) {
            $stmtFields[] = "{$key}=:{$key}";
        }

        return implode(",", $stmtFields);
    }

    /**
     * Prepares a row to be added to the database
     *
     * @param  array $fields
     * @return void
     */
    protected static function createStmtString(array $fields)
    {
        $insert  = [];
        $execute = [];

        foreach ($fields as $key => $data) {
            $insert[]  = "{$key}";
            $execute[] = ":{$key}";
        }

        return [implode(",", $insert), implode(",", $execute)];
    }

    public function getColumns()
    {
        $table = $this->table;

        if ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $query = $this->DSN->query("SHOW COLUMNS FROM {$table}");
        } elseif ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $query = $this->DSN->query("SELECT column_name, data_type
                FROM information_schema.columns 
                WHERE table_name = '{$table}'"
            );
        } elseif ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $query = $this->DSN->query("PRAGMA table_info('{$table}')"
            );
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFields(string $fields = null)
    {
        if (!isset($fields)) {
            if ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['Field'];
                }
            } elseif ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['column_name'];
                }
            } elseif ($this->DSN->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['name'];
                }
            }
        } else {
            $fields = explode(', ', $fields);
        }

        return $fields;
    }

    public function search(string $search, string $column, string $fields = null)
    {
        $table  = $this->table;
        $fields = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;

        $query = $this->DSN->prepare("
            SELECT {$fields} FROM {$table}
            WHERE {$column} LIKE :search
            ORDER BY id DESC
            LIMIT 10");

        $query->execute([
            ':search' => '%' . $search . '%',
        ]);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Helper method for writing a toggle
     */
    public function toggle()
    {
        $processed = [
            'csrf_field' => Validation::sanitize(Request::post()->get("csrf_field"))->csrf(Session::get("csrf_token"))->run(),
            'id'         => Validation::sanitize(Request::put()->get('id'))->run(),
            'redirect'   => Validation::sanitize(Request::put()->get('redirect'))->run(),
        ];

        $validated = Validation::getValidated($processed, ["csrf_field", "_method"]);

        if (Validation::approve($processed)) {
            $fields    = ["id", "status", "updated_at"];
            $updateArr = [];

            foreach ($fields as $field) {
                if (($field === "updated_at")) {
                    $updateArr[$field] = date('Y-m-d H:i:s');
                    continue;
                }

                $updateArr["status"] = (Request::put()->has("checkbox")) ? "1" : "0";
                $updateArr["id"]     = $validated["id"];
            }

            $this->update($updateArr);
            Redirect::run(ltrim($validated['redirect'], '/'));
        } else {
            dd(Validation::getAlerts($processed));
        }
    }

    public function qCache(array $params, $cacheTime = null)
    {
        $directory = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'database';       
        $file      = "$directory/$params[0].json";
        $cacheTime = $cacheTime ?? config('cache.time', 'database');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($file) && (strtotime($cacheTime, filemtime($file)) > time())) {
            return json_decode(file_get_contents($file), true);
        }

        $method = (strpos($params[0], '_') !== false) ? strstr($params[0], '_', true) : $params[0];
        $data   = (!array_key_exists(1, $params)) ? $this->$method() : $this->$method(...$params[1]);
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }
}
