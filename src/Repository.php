<?php

namespace Rudra\Model;

use Rudra\Pagination;
use Rudra\Container\Facades\Rudra;
use Rudra\Container\Facades\Request;
use Rudra\Container\Facades\Session;
use Rudra\Redirect\RedirectFacade as Redirect;
use Rudra\Validation\ValidationFacade as Validation;

class Repository
{
    public string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->DSN   = Rudra::get("DSN");
    }

    /**
     * Represents a prepared database query and, when the query is executed, the corresponding result set.
     * ---------------------------------------------------------------------------------------------------
     * Представляет подготовленный запрос к базе данных, а после выполнения запроса соответствующий результирующий набор. 
     *
     * @param  $queryString
     * @param  array  $queryParams
     * @return void
     */
    public function qBuilder($queryString, $queryParams = []): array
    {
        $stmt = Rudra::get("DSN")->prepare($queryString);
        $stmt->execute($queryParams);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all data, taking into account paging.
     * -----------------------------------------------
     * Получает все данные с учетом постраничного разбиения.
     *
     * @param  Pagination  $pagination
     * @param  string|null $fields
     * @return void
     */
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

    /**
     * Finds an element in the database by id
     * --------------------------------------
     * Находит элемент в базе данных по идентификатору
     *
     * @param  id
     * @return void
     */
    public function find($id): array
    {
        $stmt = Rudra::get("DSN")->prepare("
                SELECT * FROM {$this->table}
                WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all items from the database according to the parameters
     * -----------------------------------------------------------------
     * Получает все элементы из базы данных  в соответствии с параметрами
     *
     * @param  string      $sort
     * @param  string|null $fields
     * @return void
     */
    public function getAll(string $sort = 'id', string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $table   = $this->table;
        $qString = QBFacade::select($fields)
            ->from($table)
            ->orderBy("id ASC")
            ->get();

        return self::qBuilder($qString);
    }

    /**
     * Gets the number of rows in a specific table
     * -------------------------------------------
     * Получает количество строк в определенной таблице
     *
     * @return void
     */
    public function numRows()
    {
        $table = $this->table;
        $count = Rudra::get("DSN")->query("SELECT COUNT(*) FROM {$table}");

        return $count->fetchColumn();
    }

    /**
     * Searches for an element by value in a given field
     * -------------------------------------------------
     * Ищет элемент по значению в заданном поле
     *
     * @param  $field
     * @param  $value
     * @return void
     */
    public function findBy($field, $value)
    {
        $table = $this->table;
        $stmt  = Rudra::get("DSN")->prepare("
                SELECT * FROM {$table}
                WHERE {$field} = :val
        ");

        $stmt->execute([
            ":val" => $value,
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns the ID of the last inserted row or sequence value 
     * ---------------------------------------------------------
     * Возвращает ID последней вставленной строки или значение последовательности 
     *
     * @return void
     */
    public function lastInsertId()
    {
        return Rudra::get("DSN")->lastInsertId();
    }

    /**
     * Updates a record in the database
     * --------------------------------
     * Обновляет запись в базе данных
     *
     * @param  string $id
     * @param  array  $fields
     * @return void
     */
    public function update(string $id, array $fields)
    {
        $table = $this->table;
        unset($fields['id']);
        $stmtString   = $this->updateStmtString($fields);
        $fields['id'] = $id;

        $query = Rudra::get("DSN")->prepare("
                UPDATE {$table} SET {$stmtString}
                WHERE id =:id");

        $query->execute($fields);
    }

    /**
     * Adds an entry to the database
     * -----------------------------
     * Добавляет запись в базу данных
     *
     * @param array $fields
     * @return void
     */
    public function create(array $fields)
    {
        $table      = $this->table;
        $stmtString = $this->createStmtString($fields);

        $query = Rudra::get("DSN")->prepare("
                INSERT INTO {$table} ({$stmtString[0]})
                VALUES ({$stmtString[1]})");

        $query->execute($fields);
    }

    /**
     * Deletes an entry in the database
     * --------------------------------
     * Удаляет запись в базе данных
     *
     * @param  $id
     * @return void
     */
    public function delete($id)
    {
        $table = $this->table;
        $query = Rudra::get("DSN")->prepare("DELETE FROM {$table} WHERE id = :id");
        $query->execute([':id' => $id]);
    }

    /**
     * Prepares a row to update the database
     * -------------------------------------
     * Подготавливает строку для обновления базы данных
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
     * ------------------------------------------
     * Подготавливает строку для добавления в базу данных
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

    /**
     * Gets the names of the columns in the table
     * ------------------------------------------
     * Получает название столбцов в таблице
     *
     * @return void
     */
    public function getColumns()
    {
        $table = $this->table;

        if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $query = Rudra::get("DSN")->query("SHOW COLUMNS FROM {$table}");
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $query = Rudra::get("DSN")->query("SELECT column_name, data_type
                FROM information_schema.columns 
                WHERE table_name = '{$table}'"
            );
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $query = Rudra::get("DSN")->query("PRAGMA table_info('{$table}')"
            );
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Gets the values of fields in a table
     * ------------------------------------
     * Получает значение полей в таблице
     *
     * @param  string|null $fields
     * @return void
     */
    public function getFields(string $fields = null)
    {
        if (!isset($fields)) {
            if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['Field'];
                }
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['column_name'];
                }
            } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['name'];
                }
            }
        } else {
            $fields = explode(', ', $fields);
        }

        return $fields;
    }

    /**
     * Searches the table for data that matches the parameters
     * -------------------------------------------------------
     * Ищет в таблице данные соответствующие параметрам
     *
     * @param  string      $search
     * @param  string      $column
     * @param  string|null $fields
     * @return void
     */
    public function search(string $search, string $column, string $fields = null)
    {
        $table  = $this->table;
        $fields = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;

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

    /**
     * Helper method for writing a toggle
     * ----------------------------------
     * Вспомогательный метод для написания переключателя
     *
     * @return void
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

            self::update($updateArr);
            $table = $this->table;

            Redirect::run(ltrim($validated['redirect'], '/'));
        } else {
            dd(Validation::getAlerts($processed));
        }
    }

    /**
     * Caches a database query
     * -----------------------
     * Кэширует запрос к базе данных
     *
     * @param  array  $params
     * @param  $cacheTime
     * @return void
     */
    public function qCache(array $params, $cacheTime = null)
    {
        $directory = $this->directory . DIRECTORY_SEPARATOR . 'cache';
        $file      = "$directory/$params[0].json";
        $cacheTime = $cacheTime ?? Rudra::config()->get('cache.time');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($file) && (strtotime($cacheTime, filemtime($file)) > time())) {
            return json_decode(file_get_contents($file), true);
        }

        $method = (strpos($params[0], '_') !== false) ? strstr($params[0], '_', true) : $params[0];
        $data   = (!array_key_exists(1, $params)) ? $this->method() : $this->method(...$params[1]);
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }
}
