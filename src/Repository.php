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
    private \PDO $connection;
    private QB $qb;

    /**
     * Initializes the class with a table name, connection, and sets up dependencies.
     * The connection is either provided directly or retrieved from the Rudra container.
     * If the connection is not an instance of PDO, a LogicException is thrown.
     * -------------------------
     * Инициализирует класс с именем таблицы, connection и настраивает зависимости.
     * connection может быть предоставлен напрямую или извлечен из контейнера Rudra.
     * Если connection не является экземпляром PDO, выбрасывается исключение LogicException.
     *
     * @param  string|null $table
     * @param  \PDO|null $connection
     * @return void
     * @throws LogicException
     */
    public function __construct(?string $table, ?\PDO $connection = null)
    {
        $this->table = $table;
        $this->rudra = Rudra::run();
        $this->connection = $connection ?? $this->rudra->get('connection');
        $this->qb = new QB($this->connection);

        if (!$this->connection instanceof \PDO) {
            throw new LogicException('connection must be an instance of PDO');
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
     * If the QB instance is not yet initialized, it creates a new instance using the connection.
     * This method implements lazy initialization to ensure the QB instance is created only when needed.
     * -------------------------
     * Возвращает экземпляр Query Builder (QB).
     * Если экземпляр QB ещё не инициализирован, создаётся новый экземпляр с использованием connection.
     * Этот метод реализует ленивую инициализацию, чтобы гарантировать создание экземпляра QB только при необходимости.
     *
     * @return QB
     */
    public function qb(): QB
    {
        if ($this->qb === null) {
            $this->qb = new QB($this->connection);
        }

        return $this->qb;
    }

    /**
     * Returns the current PDO instance used by the repository.
     * -------------------------
     * Возвращает текущий экземпляр PDO, используемый репозиторием.
     *
     * @return \PDO
     */
    public function connection(): \PDO
    {
        return $this->connection;
    }

    /**
     * Sets the connection for the database connection and resets the Query Builder instance.
     * This method allows changing the connection dynamically and ensures that the Query Builder is re-initialized.
     * -------------------------
     * Устанавливает connection для подключения к базе данных и сбрасывает экземпляр Query Builder.
     * Этот метод позволяет динамически изменять connection и гарантирует повторную инициализацию Query Builder.
     *
     * @param  \PDO $connection
     * @return self
     */
    public function onConnection(\PDO $connection): self
    {
        $this->connection = $connection;
        $this->qb  = null;

        return $this;
    }

    /**
     * Creates and returns a new instance of the class with the specified connection.
     * This method allows changing the connection while preserving the current table name.
     * It is useful for creating new instances with different database connections without modifying the original object.
     * -------------------------
     * Создает и возвращает новый экземпляр класса с указанным connection.
     * Этот метод позволяет изменить connection, сохраняя текущее имя таблицы.
     * Он полезен для создания новых экземпляров с разными подключениями к базе данных без изменения исходного объекта.
     *
     * @param  \PDO $connection
     * @return self
     */
    public function withConnection(\PDO $connection): self
    {
        return new static($this->table, $connection);
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
        $stmt = $this->connection->prepare($queryString);
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
        $stmt = $this->connection->prepare("
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
     * @return array
     */
    public function getAll(string $sort = 'id ASC', string $fields = null): array
    {
        $fields  = !isset($fields) ? implode(',', $this->getFields($fields)) : $fields;
        $table   = $this->table;
        $qString = $this->qb()->select($fields)
            ->from($table)
            ->orderBy($sort)
            ->get();

        return $this->qBuilder($qString);
    }


    public function numRows()
    {
        $table = $this->table;
        $count = $this->connection->query("SELECT COUNT(*) FROM {$table}");

        return $count->fetchColumn();
    }

    /**
     * Finds a single record by a specified field and value.
     * The field name is validated against the actual table columns to prevent SQL injection.
     * -------------------------
     * Находит одну запись по указанному полю и значению.
     * Имя поля проверяется по списку реальных колонок таблицы для предотвращения SQL-инъекций.
     *
     * @param string $field
     * @param mixed  $value
     * @return array|false
     * @throws LogicException if the field is not a valid column name
     */
    public function findBy(string $field, $value): array|false
    {
        $allowedFields = $this->getFields();
        if (!in_array($field, $allowedFields, true)) {
            throw new LogicException("Invalid field: {$field}");
        }

        $table = $this->table;
        $stmt  = $this->connection->prepare("
            SELECT * FROM {$table}
            WHERE {$field} = :val
        ");

        $stmt->execute([':val' => $value]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
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

        $query = $this->connection->prepare("
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

        $query = $this->connection->prepare("
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
        $query = $this->connection->prepare("DELETE FROM {$table} WHERE id = :id");
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

        if ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $query = $this->connection->query("SHOW COLUMNS FROM {$table}");
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $query = $this->connection->query("SELECT column_name, data_type
                FROM information_schema.columns 
                WHERE table_name = '{$table}'"
            );
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $query = $this->connection->query("PRAGMA table_info('{$table}')"
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
     * @param string|null $fields
     * @return array
     */
    public function getFields(?string $fields = null): array
    {
        if ($fields !== null) {
            // Разделяем по запятой и убираем пробелы вокруг каждого поля
            return array_map('trim', explode(',', $fields));
        }

        // Инициализируем как пустой массив — защита от null
        $fieldList = [];

        if ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['Field'];
            }
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['column_name'];
            }
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['name'];
            }
        }

        return $fieldList;
    }

    /**
     * Searches for records in the database based on a search term and column.
     * The column name is validated against the actual table columns to prevent SQL injection.
     * Results are ordered by ID in descending order and limited to 10 records.
     * -------------------------
     * Выполняет поиск записей в базе данных на основе поискового запроса и указанного столбца.
     * Имя столбца проверяется по списку реальных колонок таблицы для предотвращения SQL-инъекций.
     * Результаты сортируются по ID в порядке убывания и ограничиваются 10 записями.
     *
     * @param string      $search
     * @param string      $column
     * @param string|null $fields
     * @return array
     * @throws LogicException if the column is not a valid column name
     */
    public function search(string $search, string $column, ?string $fields = null): array
    {
        $allowedColumns = $this->getFields();
        if (!in_array($column, $allowedColumns, true)) {
            throw new LogicException("Invalid search column: {$column}");
        }

        $table  = $this->table;
        $fields = $fields ? implode(',', array_map('trim', explode(',', $fields))) : implode(',', $this->getFields());
        $driver = $this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME);

        // Формируем выражение для приведения к строке
        $searchExpr = match ($driver) {
            'pgsql'  => "{$column}::TEXT",          // PostgreSQL
            'mysql'  => "CAST({$column} AS CHAR)",  // MySQL
            'sqlite' => "CAST({$column} AS TEXT)",  // SQLite
            default  => $column,                    // fallback
        };

        $query = $this->connection->prepare("
            SELECT {$fields} FROM {$table}
            WHERE {$searchExpr} LIKE :search
            ORDER BY id DESC
            LIMIT 10
        ");

        $query->execute([':search' => "%{$search}%"]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
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
        $directory = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'database';     
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
     * If a cache key is provided, only that specific cache file is deleted.
     * -------------------------
     * Очищает кэшированные файлы указанного типа или всех типов.
     * Если указан ключ кэша, удаляется только этот конкретный файл.
     *
     * @param string $type  Тип кэша: 'database', 'templates', 'twig', 'routes' или 'all'
     * @param string|null $key  Имя кэш-файла (без .json). Если null — очистить всю папку.
     * @return void
     */
    public function clearCache(string $type = 'database', ?string $key = null): void
    {
        $baseDir = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;

        if ($type === 'all') {
            $this->clearCache('database', $key);
            $this->clearCache('templates', $key);
            $this->clearCache('twig', $key);
            $this->clearCache('routes', $key);
            return;
        }

        if (!in_array($type, ['database', 'templates', 'twig', 'routes'], true)) {
            return;
        }

        $directory = $baseDir . $type;

        if ($key !== null) {
            // Удаляем один файл
            $file = $directory . DIRECTORY_SEPARATOR . $key . '.json';
            if (is_file($file)) {
                unlink($file);
            }
            return;
        }

        // Удаляем все файлы в директории
        if (is_dir($directory)) {
            foreach (glob("$directory/*.json") as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
