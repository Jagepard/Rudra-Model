<?php

declare (strict_types = 1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Session;
use Rudra\Pagination;
use Rudra\Redirect\RedirectFacade as Redirect;
use Rudra\Validation\ValidationFacade as Validation;

class Model
{
    public static string $table;
    public static string $directory;

    public static function __callStatic($method, $parameters = [])
    {
        $className = str_replace("Models", "Repository", get_called_class()) . "Repository";

        return $className::$method(...$parameters);
    }

    public static function qBuilder($queryString, $queryParams = [])
    {
        $stmt = Rudra::get("DSN")->prepare($queryString);
        $stmt->execute($queryParams);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAllPerPage(Pagination $pagination, string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', static::getFields($fields)) : $fields;
        $table   = static::$table;
        $qString = QBFacade::select($fields)
            ->from($table)
            ->orderBy("id DESC")
            ->limit($pagination->getPerPage())->offset($pagination->getOffset())
            ->get();

        return self::qBuilder($qString);
    }

    public static function find($id)
    {
        $table = static::$table;
        $stmt  = Rudra::get("DSN")->prepare("
                SELECT * FROM {$table}
                WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getAll(string $sort = 'id', string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', static::getFields($fields)) : $fields;
        $table   = static::$table;
        $qString = QBFacade::select($fields)
            ->from($table)
            ->orderBy("id ASC")
            ->get();

        return self::qBuilder($qString);
    }

    public static function numRows()
    {
        $table = static::$table;
        $count = Rudra::get("DSN")->query("SELECT COUNT(*) FROM {$table}");

        return $count->fetchColumn();
    }

    public static function findBy($field, $value)
    {
        $table = static::$table;
        $stmt  = Rudra::get("DSN")->prepare("
                SELECT * FROM {$table}
                WHERE {$field} = :val
        ");

        $stmt->execute([
            ":val" => $value,
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function lastInsertId()
    {
        return Rudra::get("DSN")->lastInsertId();
    }

    public static function update($fields)
    {
        $table = static::$table;
        $id    = $fields['id'];
        unset($fields['id']);
        $stmtString   = static::updateStmtString($fields);
        $fields['id'] = $id;

        $query = Rudra::get("DSN")->prepare("
                UPDATE {$table} SET {$stmtString}
                WHERE id =:id");

        $query->execute($fields);
    }

    public static function create($fields)
    {
        $table      = static::$table;
        $stmtString = static::createStmtString($fields);

        $query = Rudra::get("DSN")->prepare("
                INSERT INTO {$table} ({$stmtString[0]})
                VALUES ({$stmtString[1]})");

        $query->execute($fields);
    }

    public static function delete($id)
    {
        $table = static::$table;
        $query = Rudra::get("DSN")->prepare("DELETE FROM {$table} WHERE id = :id");
        $query->execute([':id' => $id]);
    }

    protected static function updateStmtString(array $fields)
    {
        $stmtFields = [];

        foreach ($fields as $key => $data) {
            $stmtFields[] = "{$key}=:{$key}";
        }

        return implode(",", $stmtFields);
    }

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

    public static function getColumns()
    {
        $table = static::$table;
        $query = Rudra::get("DSN")->query("SELECT column_name, data_type
            FROM information_schema.columns 
            WHERE table_name = '{$table}'"
        );

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getFields(string $fields = null)
    {
        if (!isset($fields)) {
            foreach (static::getColumns() as $column) {
                $fields[] = $column['column_name'];
            }
        } else {
            $fields = explode(', ', $fields);
        }

        return $fields;
    }

    public static function search(string $search, string $column, string $fields = null)
    {
        $table  = static::$table;
        $fields = !isset($fields) ? implode(',', static::getFields($fields)) : $fields;

        $query = Rudra::get("DSN")->prepare("
            SELECT {$fields} FROM {$table}
            WHERE {$column} LIKE :search
            ORDER BY id DESC
            LIMIT 10");

        $query->execute([
            ':search' => '%' . $search . '%',
        ]);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function toggle()
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

            self::update($updateArr);
            $table = static::$table;

            Redirect::run(ltrim($validated['redirect'], '/'));
        } else {
            dd(Validation::getAlerts($processed));
        }
    }

    public static function qCache(array $params, $cacheTime = null)
    {
        $directory = static::$directory . '/cache';
        $file      = "$directory/$params[0].json";
        $cacheTime = $cacheTime ?? Rudra::config()->get('cache.time');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($file) && (strtotime($cacheTime, filemtime($file)) > time())) {
            return json_decode(file_get_contents($file));
        }

        $method = (strpos($params[0], '_') !== false) ? strstr($params[0], '_', true) : $params[0];
        $data   = (!array_key_exists(1, $params)) ? static::$method() : static::$method(...$params[1]);
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }
}
