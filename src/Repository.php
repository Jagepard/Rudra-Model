<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Pagination;
use Rudra\Container\Rudra;
use Rudra\Redirect\Redirect;
use Rudra\Validation\Validation;
use Rudra\Exceptions\LogicException;

class Repository
{
    public ?string $table;
    private Rudra $rudra;
    private \PDO $dsn;
    private QB $qb;

    /**
     * Initializes the class with a table name, DSN (Data Source Name), and sets up dependencies.
     * The DSN is either provided directly or retrieved from the Rudra container.
     * If the DSN is not an instance of PDO, a LogicException is thrown.
     * -------------------------
     * Инициализирует класс с именем таблицы, DSN (Data Source Name) и настраивает зависимости.
     * DSN может быть предоставлен напрямую или извлечен из контейнера Rudra.
     * Если DSN не является экземпляром PDO, выбрасывается исключение LogicException.
     *
     * @param  string|null $table
     * @param  \PDO|null $dsn
     * @return void
     * @throws LogicException
     */
    public function __construct(?string $table, ?\PDO $dsn = null)
    {
        $this->table = $table;
        $this->rudra = Rudra::run();
        $this->dsn   = $dsn ?? $this->rudra->get('DSN');
        $this->qb    = new QB($this->dsn);

        if (!$this->dsn instanceof \PDO) {
            throw new LogicException('DSN must be an instance of PDO');
        }
    }

    /**
     * Handles calls to undefined methods by throwing a LogicException.
     * This method is invoked when an attempt is made to call a non-existent method on the object.
     * -------------------------
     * Обрабатывает вызовы неопределённых методов, выбрасывая исключение LogicException.
     * Этот метод вызывается, когда происходит попытка вызвать несуществующий метод у объекта.
     *
     * @param  string $method
     * @param  array $parameters
     * @return void
     * @throws LogicException
     */
    public function __call($method, array $parameters = [])
    {
        throw new LogicException(sprintf('Method %s does not exists', $method));
    }

    /**
     * Returns an instance of the Query Builder (QB).
     * If the QB instance is not yet initialized, it creates a new instance using the DSN.
     * This method implements lazy initialization to ensure the QB instance is created only when needed.
     * -------------------------
     * Возвращает экземпляр Query Builder (QB).
     * Если экземпляр QB ещё не инициализирован, создаётся новый экземпляр с использованием DSN.
     * Этот метод реализует ленивую инициализацию, чтобы гарантировать создание экземпляра QB только при необходимости.
     *
     * @return QB
     */
    public function qb(): QB
    {
        if ($this->qb === null) {
            $this->qb = new QB($this->dsn);
        }

        return $this->qb;
    }

    /**
     * Sets the DSN (Data Source Name) for the database connection and resets the Query Builder instance.
     * This method allows changing the DSN dynamically and ensures that the Query Builder is re-initialized.
     * -------------------------
     * Устанавливает DSN (Data Source Name) для подключения к базе данных и сбрасывает экземпляр Query Builder.
     * Этот метод позволяет динамически изменять DSN и гарантирует повторную инициализацию Query Builder.
     *
     * @param  \PDO $dsn
     * @return self
     */
    public function onDsn(\PDO $dsn): self
    {
        $this->dsn = $dsn;
        $this->qb  = null;

        return $this;
    }

    /**
     * Creates and returns a new instance of the class with the specified DSN.
     * This method allows changing the DSN while preserving the current table name.
     * It is useful for creating new instances with different database connections without modifying the original object.
     * -------------------------
     * Создает и возвращает новый экземпляр класса с указанным DSN.
     * Этот метод позволяет изменить DSN, сохраняя текущее имя таблицы.
     * Он полезен для создания новых экземпляров с разными подключениями к базе данных без изменения исходного объекта.
     *
     * @param  \PDO $dsn
     * @return self
     */
    public function withDsn(\PDO $dsn): self
    {
        return new static($this->table, $dsn);
    }

    /**
     * Executes a custom SQL query and returns the result as an associative array.
     * The method prepares the query, executes it with optional parameters, and fetches all results.
     * -------------------------
     * Выполняет пользовательский SQL-запрос и возвращает результат в виде ассоциативного массива.
     * Метод подготавливает запрос, выполняет его с необязательными параметрами и извлекает все результаты.
     *
     * @param  string $queryString
     * @param  array $queryParams
     * @return array
     */
    public function qBuilder($queryString, array $queryParams = []): array
    {
        $stmt = $this->dsn->prepare($queryString);
        $stmt->execute($queryParams);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param  Pagination $pagination
     * @param  string|null $fields
     * @return array
     */
    public function getAllPerPage(Pagination $pagination, string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $qString = $this->qb()->select($fields)
            ->from($this->table)
            ->orderBy("id DESC")
            ->limit($pagination->getPerPage())->offset($pagination->getOffset())
            ->get();

        return $this->qBuilder($qString);
    }

    /**
     * @param  int|string $id
     * @return array|false
     */
    public function find($id): array|false
    {
        $stmt = $this->dsn->prepare("
                SELECT * FROM {$this->table}
                WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param  string      $sort
     * @param  string|null $fields
     * @return void
     */
    public function getAll(string $sort = 'id ASC', string $fields = null)
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $table   = $this->table;
        $qString = $this->qb()->select($fields)
            ->from($table)
            ->orderBy($sort)
            ->get();

        return self::qBuilder($qString);
    }


    public function numRows()
    {
        $table = $this->table;
        $count = $this->dsn->query("SELECT COUNT(*) FROM {$table}");

        return $count->fetchColumn();
    }

    /**
     * @param  $field
     * @param  $value
     * @return void
     */
    public function findBy($field, $value)
    {
        $table = $this->table;
        $stmt  = $this->dsn->prepare("
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
        return $this->dsn->lastInsertId();
    }

    /**
     * @param  array $fields
     * @return void
     */
    public function update(array $fields)
    {
        $id = $fields['id'];
        unset($fields['id']);
        $stmtString   = $this->updateStmtString($fields);
        $fields['id'] = $id;

        $query = $this->dsn->prepare("
                UPDATE {$this->table} SET {$stmtString}
                WHERE id =:id");

        $query->execute($fields);
        $this->clearCache();
    }

    /**
     * @param  array $fields
     * @return void
     */
    public function create(array $fields)
    {
        $table      = $this->table;
        $stmtString = $this->createStmtString($fields);

        $query = $this->dsn->prepare("
                INSERT INTO {$table} ({$stmtString[0]})
                VALUES ({$stmtString[1]})");

        $query->execute($fields);
        $this->clearCache();
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $table = $this->table;
        $query = $this->dsn->prepare("DELETE FROM {$table} WHERE id = :id");
        $query->execute([':id' => $id]);
        $this->clearCache();
    }

    /**
     * Generates a string of fields and placeholders for an SQL UPDATE statement.
     * The method takes an array of fields and constructs a comma-separated list of "key=:key" pairs.
     * This string can be directly used in the SET clause of an SQL UPDATE query.
     * -------------------------
     * Генерирует строку полей и плейсхолдеров для SQL-запроса UPDATE.
     * Метод принимает массив полей и формирует список пар "ключ=:ключ", разделённый запятыми.
     * Эта строка может быть напрямую использована в SET-части SQL-запроса UPDATE.
     *
     * @param  array $fields
     * @return string
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
     * Generates two strings for an SQL INSERT statement: one for column names and one for placeholders.
     * The method takes an array of fields and constructs two comma-separated lists:
     * - A list of column names.
     * - A list of placeholders (prefixed with colons) for parameter binding.
     * These strings can be directly used in the SQL INSERT query.
     * -------------------------
     * Генерирует две строки для SQL-запроса INSERT:
     * - Список имен столбцов.
     * - Список плейсхолдеров (с префиксом двоеточия) для связывания параметров.
     * Эти строки могут быть напрямую использованы в SQL-запросе INSERT.
     *
     * @param  array $fields
     * @return array 
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
     * Retrieves the column information for the current table based on the database driver.
     * The method executes a query specific to the database type (MySQL, PostgreSQL, or SQLite) 
     * to fetch the column details of the table.
     * -------------------------
     * Получает информацию о столбцах текущей таблицы в зависимости от типа базы данных.
     * Метод выполняет запрос, специфичный для используемой СУБД (MySQL, PostgreSQL или SQLite), 
     * чтобы получить сведения о столбцах таблицы.
     *
     * @return array
     * @throws \PDOException
     */
    public function getColumns()
    {
        $table = $this->table;

        if ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $query = $this->dsn->query("SHOW COLUMNS FROM {$table}");
        } elseif ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $query = $this->dsn->query("SELECT column_name, data_type
                FROM information_schema.columns 
                WHERE table_name = '{$table}'"
            );
        } elseif ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $query = $this->dsn->query("PRAGMA table_info('{$table}')"
            );
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the list of fields (columns) for the current table.
     * If no specific fields are provided, the method fetches all column names based on the database driver.
     * Otherwise, it splits the provided comma-separated string of fields into an array.
     * -------------------------
     * Получает список полей (столбцов) для текущей таблицы.
     * Если конкретные поля не указаны, метод извлекает все имена столбцов в зависимости от типа базы данных.
     * В противном случае он разделяет предоставленную строку полей, разделённых запятыми, на массив.
     *
     * @param  string|null $fields
     * @return array
     */
    public function getFields(string $fields = null)
    {
        if (!isset($fields)) {
            if ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['Field'];
                }
            } elseif ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
                foreach ($this->getColumns() as $column) {
                    $fields[] = $column['column_name'];
                }
            } elseif ($this->dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
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
     * Searches for records in the database based on a search term and column.
     * The method prepares and executes a query to retrieve records where the specified column matches the search term.
     * Results are ordered by ID in descending order and limited to 10 records.
     * -------------------------
     * Выполняет поиск записей в базе данных на основе поискового запроса и указанного столбца.
     * Метод подготавливает и выполняет запрос для получения записей, где указанный столбец соответствует поисковому запросу.
     * Результаты сортируются по ID в порядке убывания и ограничиваются 10 записями.
     *
     * @param  string $search
     * @param  string $column
     * @param  string|null $fields
     * @return array 
     */
    public function search(string $search, string $column, string $fields = null)
    {
        $table  = $this->table;
        $fields = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;

        $query = $this->dsn->prepare("
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
     * @deprecated
     * Helper method for writing a toggle.
     * This method processes a toggle request, validates input data, updates the status of a record,
     * and redirects to the specified URL.
     * -------------------------
     * Вспомогательный метод для обработки переключения (toggle).
     * Этот метод обрабатывает запрос на переключение, проверяет входные данные, обновляет статус записи
     * и выполняет перенаправление на указанный URL.
     *
     * @return void
     */
    public function toggle()
    {
        $processed = [
            'csrf_field' => $this->rudra->get(Validation::class)->sanitize($this->rudra->request()->post()->get("csrf_field"))->csrf($this->rudra->session()->get("csrf_token"))->run(),
            'id'         => $this->rudra->get(Validation::class)->sanitize($this->rudra->request()->put()->get('id'))->run(),
            'redirect'   => $this->rudra->get(Validation::class)->sanitize($this->rudra->request()->put()->get('redirect'))->run(),
        ];

        $validated = $this->rudra->get(Validation::class)->getValidated($processed, ["csrf_field", "_method"]);

        if ($this->rudra->get(Validation::class)->approve($processed)) {
            $fields    = ["id", "status", "updated_at"];
            $updateArr = [];

            foreach ($fields as $field) {
                if (($field === "updated_at")) {
                    $updateArr[$field] = date('Y-m-d H:i:s');
                    continue;
                }

                $updateArr["status"] = ($this->rudra->request()->put()->has("checkbox")) ? "1" : "0";
                $updateArr["id"]     = $validated["id"];
            }

            $this->update($updateArr);
            $this->rudra->get(Redirect::class)->run(ltrim($validated['redirect'], '/'));
        } else {
            dd($this->rudra->get(Validation::class)->getAlerts($processed));
        }
    }

    /**
     * Caches the result of a method call to a JSON file for a specified duration.
     * If the cached file exists and is still valid (based on cache time), the cached data is returned.
     * Otherwise, the method executes the specified method, caches its result, and returns the data.
     * -------------------------
     * Кэширует результат вызова метода в JSON-файл на определённый период времени.
     * Если кэшированный файл существует и всё ещё действителен (на основе времени кэширования), возвращаются кэшированные данные.
     * В противном случае метод выполняет указанный метод, кэширует его результат и возвращает данные.
     *
     * @param  array $params
     * @param  string|null $cacheTime
     * @return mixed
     */
    public function cache(array $params, $cacheTime = null)
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

    /**
     * Clears cached files of a specified type or all types.
     * The method removes JSON cache files from the 'database' or 'view' directories, 
     * or clears both directories if 'all' is specified.
     * -------------------------
     * Очищает кэшированные файлы указанного типа или всех типов.
     * Метод удаляет JSON-файлы кэша из директорий 'database' или 'view',
     * или очищает обе директории, если указано значение 'all'.
     *
     * @param  string $type
     * @return void
     */
    public function clearCache(string $type = 'database')
    {
        $baseDir = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        
        if (!in_array($type, ['database', 'view', 'all'])) {
            return;
        }

        if ($type === 'all') {
            $this->clearCache('database');
            $this->clearCache('view');
            return;
        }

        $directory = $baseDir . $type;

        if (is_dir($directory)) {
            foreach (glob("$directory/*.json") as $file) {
                if (is_file($file)) unlink($file);
            }
        }
    }
}
