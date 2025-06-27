## Table of contents
- [Rudra\Model\Driver\MySQL](#rudra_model_driver_mysql)
- [Rudra\Model\Driver\PgSQL](#rudra_model_driver_pgsql)
- [Rudra\Model\Driver\SQLite](#rudra_model_driver_sqlite)
- [Rudra\Model\Entity](#rudra_model_entity)
- [Rudra\Model\Model](#rudra_model_model)
- [Rudra\Model\QB](#rudra_model_qb)
- [Rudra\Model\QBFacade](#rudra_model_qbfacade)
- [Rudra\Model\Repository](#rudra_model_repository)
- [Rudra\Model\Schema](#rudra_model_schema)
<hr>

<a id="rudra_model_driver_mysql"></a>

### Class: Rudra\Model\Driver\MySQL
| Visibility | Function |
|:-----------|:---------|
| public | `concat(string $fieldName, string $alias, string $orderBy): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $autoincrement, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `created_at(): string`<br> |
| public | `updated_at(): string`<br> |
| public | `pk( $field): string`<br> |


<a id="rudra_model_driver_pgsql"></a>

### Class: Rudra\Model\Driver\PgSQL
| Visibility | Function |
|:-----------|:---------|
| public | `concat(string $fieldName, string $alias, string $orderBy): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $pk, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `created_at(): string`<br> |
| public | `updated_at(): string`<br> |
| public | `pk(string $field): string`<br> |


<a id="rudra_model_driver_sqlite"></a>

### Class: Rudra\Model\Driver\SQLite
| Visibility | Function |
|:-----------|:---------|
| public | `concat(string $fieldName, string $alias, string $orderBy): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $pk, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `created_at(): string`<br> |
| public | `updated_at(): string`<br> |
| public | `pk(string $field): string`<br> |


<a id="rudra_model_entity"></a>

### Class: Rudra\Model\Entity
| Visibility | Function |
|:-----------|:---------|
| public static | `__callStatic( $method, array $parameters)`<br> |
| public | `__call( $method, array $parameters)`<br> |
| protected static | `callMethod( $method, array $parameters)`<br>Dynamically calls a method on the corresponding Model, Repository, or parent Repository class.<br>The method first attempts to call the method on the Model class associated with the Entity.<br>If the Model does not exist, it falls back to the Repository class.<br>If the Repository does not exist, it defaults to the parent Repository class.<br>-------------------------<br>Динамически вызывает метод в соответствующем классе Model, Repository или родительском Repository.<br>Метод сначала пытается вызвать метод в классе Model, связанном с Entity.<br>Если Model не существует, используется класс Repository.<br>Если Repository не существует, используется родительский класс Repository. |


<a id="rudra_model_model"></a>

### Class: Rudra\Model\Model
| Visibility | Function |
|:-----------|:---------|
| public | `__construct(?string $table)`<br> |
| public | `__call( $method, array $parameters)`<br>Handles calls to undefined methods by delegating them to the corresponding Repository class.<br>The method dynamically resolves the Repository class associated with the Model.<br>If the Repository does not exist, it falls back to the parent Repository class.<br>If the method exists in the resolved Repository, it is invoked with the provided parameters.<br>Otherwise, an exception is thrown.<br>-------------------------<br>Обрабатывает вызовы неопределённых методов, делегируя их соответствующему классу Repository.<br>Метод динамически определяет класс Repository, связанный с Model.<br>Если Repository не существует, используется родительский класс Repository.<br>Если метод существует в разрешённом Repository, он вызывается с предоставленными параметрами.<br>В противном случае выбрасывается исключение. |


<a id="rudra_model_qb"></a>

### Class: Rudra\Model\QB
| Visibility | Function |
|:-----------|:---------|
| public | `__construct( $dsn)`<br>Initializes the database driver based on the provided DSN or a default DSN from the container.<br>If no DSN is provided and none is available in the container, a LogicException is thrown.<br>The driver is selected based on the database type specified in the DSN's driver attribute.<br>-------------------------<br>Инициализирует драйвер базы данных на основе предоставленного DSN или DSN по умолчанию из контейнера.<br>Если DSN не предоставлен и отсутствует в контейнере, выбрасывается исключение LogicException.<br>Драйвер выбирается на основе типа базы данных, указанного в атрибуте драйвера DSN. |
| public | `select(string $fields): self`<br> |
| public | `concat(string $fieldName, string $alias, ?string $orderBy): self`<br> |
| public | `from(string $table): self`<br> |
| public | `where(string $param): self`<br> |
| public | `and(string $param): self`<br> |
| public | `or(string $param): self`<br> |
| public | `limit( $param): self`<br> |
| public | `offset( $param): self`<br> |
| public | `orderBy(string $param): self`<br> |
| public | `groupBy(string $param): self`<br> |
| public | `join(string $param, string $type): self`<br> |
| public | `on(string $param): self`<br> |
| public | `get(): string`<br> |
| public | `create(string $table): self`<br> |
| public | `close(): self`<br> |
| public | `integer(string $field, string $default, bool $autoincrement, string $null): self`<br> |
| public | `string(string $field, string $default, string $null): self`<br> |
| public | `text(string $field, string $null): self`<br> |
| public | `created_at(): self`<br> |
| public | `updated_at(): self`<br> |
| public | `pk(?string $field): self`<br> |


<a id="rudra_model_qbfacade"></a>

### Class: Rudra\Model\QBFacade
| Visibility | Function |
|:-----------|:---------|
| public static | `__callStatic(string $method, array $parameters): ?mixed`<br>Handles static method calls for the Facade class.<br>It dynamically resolves the underlying class name by removing "Facade" from the class name.<br>If the resolved class does not exist, it attempts to clean up the class name by removing spaces.<br>If the resolved class is not already registered in the container, it registers it.<br>Finally, it delegates the static method call to the resolved class instance.<br>-------------------------<br>Обрабатывает статические вызовы методов для класса Facade.<br>Динамически разрешает имя базового класса, удаляя "Facade" из имени класса.<br>Если разрешённый класс не существует, пытается очистить имя класса, удаляя пробелы.<br>Если разрешённый класс ещё не зарегистрирован в контейнере, он регистрируется.<br>В конце делегирует статический вызов метода экземпляру разрешённого класса. |


<a id="rudra_model_repository"></a>

### Class: Rudra\Model\Repository
| Visibility | Function |
|:-----------|:---------|
| public | `__construct(?string $table, ?PDO $dsn)`<br>Initializes the class with a table name, DSN (Data Source Name), and sets up dependencies.<br>The DSN is either provided directly or retrieved from the Rudra container.<br>If the DSN is not an instance of PDO, a LogicException is thrown.<br>-------------------------<br>Инициализирует класс с именем таблицы, DSN (Data Source Name) и настраивает зависимости.<br>DSN может быть предоставлен напрямую или извлечен из контейнера Rudra.<br>Если DSN не является экземпляром PDO, выбрасывается исключение LogicException. |
| public | `__call( $method, array $parameters)`<br>Handles calls to undefined methods by throwing a LogicException.<br>This method is invoked when an attempt is made to call a non-existent method on the object.<br>-------------------------<br>Обрабатывает вызовы неопределённых методов, выбрасывая исключение LogicException.<br>Этот метод вызывается, когда происходит попытка вызвать несуществующий метод у объекта. |
| public | `qb(): Rudra\Model\QB`<br>Returns an instance of the Query Builder (QB).<br>If the QB instance is not yet initialized, it creates a new instance using the DSN.<br>This method implements lazy initialization to ensure the QB instance is created only when needed.<br>-------------------------<br>Возвращает экземпляр Query Builder (QB).<br>Если экземпляр QB ещё не инициализирован, создаётся новый экземпляр с использованием DSN.<br>Этот метод реализует ленивую инициализацию, чтобы гарантировать создание экземпляра QB только при необходимости. |
| public | `onDsn(PDO $dsn): self`<br>Sets the DSN (Data Source Name) for the database connection and resets the Query Builder instance.<br>This method allows changing the DSN dynamically and ensures that the Query Builder is re-initialized.<br>-------------------------<br>Устанавливает DSN (Data Source Name) для подключения к базе данных и сбрасывает экземпляр Query Builder.<br>Этот метод позволяет динамически изменять DSN и гарантирует повторную инициализацию Query Builder. |
| public | `withDsn(PDO $dsn): self`<br>Creates and returns a new instance of the class with the specified DSN.<br>This method allows changing the DSN while preserving the current table name.<br>It is useful for creating new instances with different database connections without modifying the original object.<br>-------------------------<br>Создает и возвращает новый экземпляр класса с указанным DSN.<br>Этот метод позволяет изменить DSN, сохраняя текущее имя таблицы.<br>Он полезен для создания новых экземпляров с разными подключениями к базе данных без изменения исходного объекта. |
| public | `qBuilder( $queryString, array $queryParams): array`<br>Executes a custom SQL query and returns the result as an associative array.<br>The method prepares the query, executes it with optional parameters, and fetches all results.<br>-------------------------<br>Выполняет пользовательский SQL-запрос и возвращает результат в виде ассоциативного массива.<br>Метод подготавливает запрос, выполняет его с необязательными параметрами и извлекает все результаты. |
| public | `getAllPerPage(Rudra\Pagination $pagination, ?string $fields)`<br> |
| public | `find( $id): array\|false`<br> |
| public | `getAll(string $sort, ?string $fields)`<br> |
| public | `numRows()`<br> |
| public | `findBy( $field,  $value)`<br> |
| public | `lastInsertId()`<br> |
| public | `update(array $fields)`<br> |
| public | `create(array $fields)`<br> |
| public | `delete( $id)`<br> |
| protected static | `updateStmtString(array $fields)`<br>Generates a string of fields and placeholders for an SQL UPDATE statement.<br>The method takes an array of fields and constructs a comma-separated list of "key=:key" pairs.<br>This string can be directly used in the SET clause of an SQL UPDATE query.<br>-------------------------<br>Генерирует строку полей и плейсхолдеров для SQL-запроса UPDATE.<br>Метод принимает массив полей и формирует список пар "ключ=:ключ", разделённый запятыми.<br>Эта строка может быть напрямую использована в SET-части SQL-запроса UPDATE. |
| protected static | `createStmtString(array $fields)`<br>Generates two strings for an SQL INSERT statement: one for column names and one for placeholders.<br>The method takes an array of fields and constructs two comma-separated lists:<br>- A list of column names.<br>- A list of placeholders (prefixed with colons) for parameter binding.<br>These strings can be directly used in the SQL INSERT query.<br>-------------------------<br>Генерирует две строки для SQL-запроса INSERT:<br>- Список имен столбцов.<br>- Список плейсхолдеров (с префиксом двоеточия) для связывания параметров.<br>Эти строки могут быть напрямую использованы в SQL-запросе INSERT. |
| public | `getColumns()`<br>Retrieves the column information for the current table based on the database driver.<br>The method executes a query specific to the database type (MySQL, PostgreSQL, or SQLite)<br>to fetch the column details of the table.<br>-------------------------<br>Получает информацию о столбцах текущей таблицы в зависимости от типа базы данных.<br>Метод выполняет запрос, специфичный для используемой СУБД (MySQL, PostgreSQL или SQLite),<br>чтобы получить сведения о столбцах таблицы. |
| public | `getFields(?string $fields)`<br>Retrieves the list of fields (columns) for the current table.<br>If no specific fields are provided, the method fetches all column names based on the database driver.<br>Otherwise, it splits the provided comma-separated string of fields into an array.<br>-------------------------<br>Получает список полей (столбцов) для текущей таблицы.<br>Если конкретные поля не указаны, метод извлекает все имена столбцов в зависимости от типа базы данных.<br>В противном случае он разделяет предоставленную строку полей, разделённых запятыми, на массив. |
| public | `search(string $search, string $column, ?string $fields)`<br>Searches for records in the database based on a search term and column.<br>The method prepares and executes a query to retrieve records where the specified column matches the search term.<br>Results are ordered by ID in descending order and limited to 10 records.<br>-------------------------<br>Выполняет поиск записей в базе данных на основе поискового запроса и указанного столбца.<br>Метод подготавливает и выполняет запрос для получения записей, где указанный столбец соответствует поисковому запросу.<br>Результаты сортируются по ID в порядке убывания и ограничиваются 10 записями. |
| public | `toggle()`<br> |
| public | `cache(array $params,  $cacheTime)`<br>Caches the result of a method call to a JSON file for a specified duration.<br>If the cached file exists and is still valid (based on cache time), the cached data is returned.<br>Otherwise, the method executes the specified method, caches its result, and returns the data.<br>-------------------------<br>Кэширует результат вызова метода в JSON-файл на определённый период времени.<br>Если кэшированный файл существует и всё ещё действителен (на основе времени кэширования), возвращаются кэшированные данные.<br>В противном случае метод выполняет указанный метод, кэширует его результат и возвращает данные. |
| public | `clearCache(string $type)`<br>Clears cached files of a specified type or all types.<br>The method removes JSON cache files from the 'database' or 'view' directories,<br>or clears both directories if 'all' is specified.<br>-------------------------<br>Очищает кэшированные файлы указанного типа или всех типов.<br>Метод удаляет JSON-файлы кэша из директорий 'database' или 'view',<br>или очищает обе директории, если указано значение 'all'. |


<a id="rudra_model_schema"></a>

### Class: Rudra\Model\Schema
| Visibility | Function |
|:-----------|:---------|
| public static | `create(string $table, callable $callback): self`<br>Creates a new Schema instance and defines the table structure using a callback function.<br>The callback function is used to configure the table schema via the Query Builder.<br>-------------------------<br>Создает новый экземпляр Schema и определяет структуру таблицы с помощью callback-функции.<br>Callback-функция используется для настройки схемы таблицы через Query Builder. |
| public | `execute(): bool`<br>Executes the schema creation by preparing and running the SQL query.<br>The SQL query is generated using the Query Builder and executed on the database connection.<br>-------------------------<br>Выполняет создание схемы путем подготовки и выполнения SQL-запроса.<br>SQL-запрос генерируется с использованием Query Builder и выполняется на подключении к базе данных. |
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
