## Table of contents
- [Rudra\Model\Drivers\MySQL](#rudra_model_drivers_mysql)
- [Rudra\Model\Drivers\PgSQL](#rudra_model_drivers_pgsql)
- [Rudra\Model\Drivers\SQLite](#rudra_model_drivers_sqlite)
- [Rudra\Model\Entity](#rudra_model_entity)
- [Rudra\Model\Interfaces\SqlDialectInterface](#rudra_model_interfaces_sqldialectinterface)
- [Rudra\Model\Model](#rudra_model_model)
- [Rudra\Model\QB](#rudra_model_qb)
- [Rudra\Model\QBFacade](#rudra_model_qbfacade)
- [Rudra\Model\Repository](#rudra_model_repository)
- [Rudra\Model\Schema](#rudra_model_schema)
- [Rudra\Model\Traits\CacheTrait](#rudra_model_traits_cachetrait)
- [Rudra\Model\Traits\CrudTrait](#rudra_model_traits_crudtrait)
- [Rudra\Model\Traits\SchemaTrait](#rudra_model_traits_schematrait)


---



<a id="rudra_model_drivers_mysql"></a>

### Class: Rudra\Model\Drivers\MySQL
| Visibility | Function |
|:-----------|:---------|
| public | `groupConcat(string $fieldName, string $alias, ?string $orderBy, bool $distinct): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $autoincrement, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `createdAt(): string`<br> |
| public | `updatedAt(): string`<br> |
| public | `primaryKey(string $field): string`<br> |


<a id="rudra_model_drivers_pgsql"></a>

### Class: Rudra\Model\Drivers\PgSQL
| Visibility | Function |
|:-----------|:---------|
| public | `groupConcat(string $fieldName, string $alias, ?string $orderBy, bool $distinct): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $autoincrement, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `createdAt(): string`<br> |
| public | `updatedAt(): string`<br> |
| public | `primaryKey(string $field): string`<br> |


<a id="rudra_model_drivers_sqlite"></a>

### Class: Rudra\Model\Drivers\SQLite
| Visibility | Function |
|:-----------|:---------|
| public | `groupConcat(string $fieldName, string $alias, ?string $orderBy, bool $distinct): string`<br> |
| public | `close(): string`<br> |
| public | `integer(string $field, string $default, bool $autoincrement, string $null): string`<br> |
| public | `string(string $field, string $default, string $null): string`<br> |
| public | `text(string $field, string $null): string`<br> |
| public | `createdAt(): string`<br> |
| public | `updatedAt(): string`<br> |
| public | `primaryKey(string $field): string`<br> |


<a id="rudra_model_entity"></a>

### Class: Rudra\Model\Entity
| Visibility | Function |
|:-----------|:---------|
| public static | `__callStatic(string $method, array $parameters): mixed`<br> |
| public | `__call(string $method, array $parameters): mixed`<br> |
| protected static | `callMethod(string $method, array $parameters): mixed`<br>Dynamically calls a method on the corresponding Model, Repository, or parent Repository class.<br>The method first attempts to call the method on the Model class associated with the Entity.<br>If the Model does not exist, it falls back to the Repository class.<br>If the Repository does not exist, it defaults to the parent Repository class. |


<a id="rudra_model_interfaces_sqldialectinterface"></a>

### Class: Rudra\Model\Interfaces\SqlDialectInterface
| Visibility | Function |
|:-----------|:---------|
| abstract public | `groupConcat(string $fieldName, string $alias, ?string $orderBy, bool $distinct): string`<br> |
| abstract public | `close(): string`<br> |
| abstract public | `integer(string $field, string $default, bool $autoincrement, string $null): string`<br> |
| abstract public | `string(string $field, string $default, string $null): string`<br> |
| abstract public | `text(string $field, string $null): string`<br> |
| abstract public | `createdAt(): string`<br> |
| abstract public | `updatedAt(): string`<br> |
| abstract public | `primaryKey(string $field): string`<br> |


<a id="rudra_model_model"></a>

### Class: Rudra\Model\Model
| Visibility | Function |
|:-----------|:---------|
| public | `__construct(?string $table)`<br> |
| public | `__call(string $method, array $parameters): mixed`<br>Handles calls to undefined methods by delegating them to the corresponding Repository class.<br>The method dynamically resolves the Repository class associated with the Model.<br>If the Repository does not exist, it falls back to the parent Repository class.<br>If the method exists in the resolved Repository, it is invoked with the provided parameters.<br>Otherwise, an exception is thrown. |


<a id="rudra_model_qb"></a>

### Class: Rudra\Model\QB
| Visibility | Function |
|:-----------|:---------|
| public | `__construct($connection)`<br>Initializes the database driver based on the provided connection or a default connection from the container.<br>If no connection is provided and none is available in the container, a LogicException is thrown.<br>The driver is selected based on the database type specified in the connection's driver attribute. |
| public | `select(string $fields): self`<br> |
| public | `delete(string $table): self`<br> |
| public | `insert(string $table, string $columns): self`<br> |
| public | `values(string $placeholders): self`<br> |
| public | `update(string $table, string $set): self`<br> |
| public | `groupConcat(string $fieldName, string $alias, ?string $orderBy): self`<br> |
| public | `from(string $table): self`<br> |
| public | `where(string $param): self`<br> |
| public | `and(string $param): self`<br> |
| public | `or(string $param): self`<br> |
| public | `limit(string\|int $param): self`<br> |
| public | `offset(string\|int $param): self`<br> |
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
| public | `createdAt(): self`<br> |
| public | `updatedAt(): self`<br> |
| public | `primaryKey(?string $field): self`<br> |


<a id="rudra_model_qbfacade"></a>

### Class: Rudra\Model\QBFacade
| Visibility | Function |
|:-----------|:---------|
| public static | `__callStatic(string $method, array $parameters): mixed`<br>Handles static method calls for the Facade class<br>It dynamically resolves the underlying class name by removing "Facade" from the class name<br>If the resolved class does not exist, it attempts to clean up the class name by removing spaces<br>If the resolved class is not already registered in the container, it registers it<br>Finally, it delegates the static method call to the resolved class instance |


<a id="rudra_model_repository"></a>

### Class: Rudra\Model\Repository
| Visibility | Function |
|:-----------|:---------|
| public | `__construct(?string $table, ?PDO $connection)`<br>Initializes the class with a table name, connection, and sets up dependencies.<br>The connection is either provided directly or retrieved from the Rudra container.<br>If the connection is not an instance of PDO, a LogicException is thrown. |
| public | `__call(string $method, array $parameters): never`<br>Handles calls to undefined methods by throwing a LogicException.<br>This method is invoked when an attempt is made to call a non-existent method on the object. |
| public | `qb(): Rudra\Model\QB`<br>Returns an instance of the Query Builder (QB).<br>If the QB instance is not yet initialized, it creates a new instance using the connection.<br>This method implements lazy initialization to ensure the QB instance is created only when needed. |
| public | `connection(): PDO`<br>Returns the current PDO instance used by the repository. |
| public | `onConnection(PDO $connection): self`<br>Sets the connection for the database connection and resets the Query Builder instance.<br>This method allows changing the connection dynamically and ensures that the Query Builder is re-initialized. |
| public | `withConnection(PDO $connection): self`<br>Creates and returns a new instance of the class with the specified connection.<br>This method allows changing the connection while preserving the current table name.<br>It is useful for creating new instances with different database connections without modifying the original object. |
| public | `fetchAll(string $queryString, array $queryParams): array`<br>Executes the query and returns all result rows (for SELECT). |
| public | `fetch(string $queryString, array $queryParams): array\|false`<br>Executes the query and returns a single row. |
| public | `execute(string $queryString, array $queryParams): bool`<br>Executes the query (INSERT, UPDATE, DELETE) and returns the execution status. |
| public | `qBuilder(string $queryString, array $queryParams): array`<br> |
| public | `getAllPerPage(Rudra\Pagination $pagination, ?string $fields): array`<br> |
| public | `getAll(string $sort, ?string $fields): array`<br> |
| public | `numRows(): int`<br> |
| public | `findBy(string $field, mixed $value): array\|false`<br>Finds a single record by a specified field and value.<br>WARNING: The \$field parameter is inserted directly into the SQL query.<br>It is the developer's responsibility to ensure the field name is valid<br>and sanitized to prevent SQL injection. Do not pass raw user input here. |
| public | `lastInsertId(): string`<br> |
| public | `search(string $search, string $column, ?string $fields): array`<br>Searches for records in the database based on a search term and column.<br>WARNING: The \$column parameter is inserted directly into the SQL query.<br>Ensure it is sanitized to prevent SQL injection.<br>Results are ordered by ID in descending order and limited to 10 records. |
| public | `find(string\|int $id): array\|false`<br> |
| public | `update(array $fields): void`<br> |
| public | `create(array $fields): void`<br> |
| public | `delete(string\|int $id): void`<br> |
| protected static | `updateStmtString(array $fields): string`<br>Generates a string of fields and placeholders for an SQL UPDATE statement.<br>The method takes an array of fields and constructs a comma-separated list of "key=:key" pairs.<br>This string can be directly used in the SET clause of an SQL UPDATE query. |
| protected static | `createStmtString(array $fields): array`<br>Generates two strings for an SQL INSERT statement: one for column names and one for placeholders.<br>The method takes an array of fields and constructs two comma-separated lists:<br>- A list of column names.<br>- A list of placeholders (prefixed with colons) for parameter binding.<br>These strings can be directly used in the SQL INSERT query. |
| public | `cache(array $params, ?string $cacheTime): mixed`<br>Caches the result of a method call to a JSON file for a specified duration.<br>If the cached file exists and is still valid (based on cache time), the cached data is returned.<br>Otherwise, the method executes the specified method, caches its result, and returns the data. |
| public | `clearCache(string $type, ?string $key): void`<br>Clears cached files of a specified type or all types.<br>If a cache key is provided, only that specific cache file is deleted. |
| public | `getColumns(): array`<br>Retrieves the column information for the current table based on the database driver.<br>The method executes a query specific to the database type (MySQL, PostgreSQL, or SQLite)<br>to fetch the column details of the table. |
| public | `getFields(?string $fields): array`<br>Retrieves the list of fields (columns) for the current table.<br>If no specific fields are provided, the method fetches all column names based on the database driver.<br>Otherwise, it splits the provided comma-separated string of fields into an array. |


<a id="rudra_model_schema"></a>

### Class: Rudra\Model\Schema
| Visibility | Function |
|:-----------|:---------|
| public static | `create(string $table, callable $callback): self`<br>Creates a new Schema instance and defines the table structure using a callback function. |
| public static | `hasTable(string $table): bool`<br>Checks if a table exists in the database. |
| public | `execute(): bool`<br>Executes the schema creation.<br>Throws an exception if the table already exists to prevent silent masking of DB state. |


<a id="rudra_model_traits_cachetrait"></a>

### Class: Rudra\Model\Traits\CacheTrait
| Visibility | Function |
|:-----------|:---------|
| public | `cache(array $params, ?string $cacheTime): mixed`<br>Caches the result of a method call to a JSON file for a specified duration.<br>If the cached file exists and is still valid (based on cache time), the cached data is returned.<br>Otherwise, the method executes the specified method, caches its result, and returns the data. |
| public | `clearCache(string $type, ?string $key): void`<br>Clears cached files of a specified type or all types.<br>If a cache key is provided, only that specific cache file is deleted. |


<a id="rudra_model_traits_crudtrait"></a>

### Class: Rudra\Model\Traits\CrudTrait
| Visibility | Function |
|:-----------|:---------|
| public | `getAllPerPage(Rudra\Pagination $pagination, ?string $fields): array`<br> |
| public | `getAll(string $sort, ?string $fields): array`<br> |
| public | `numRows(): int`<br> |
| public | `findBy(string $field, mixed $value): array\|false`<br>Finds a single record by a specified field and value.<br>WARNING: The \$field parameter is inserted directly into the SQL query.<br>It is the developer's responsibility to ensure the field name is valid<br>and sanitized to prevent SQL injection. Do not pass raw user input here. |
| public | `lastInsertId(): string`<br> |
| public | `search(string $search, string $column, ?string $fields): array`<br>Searches for records in the database based on a search term and column.<br>WARNING: The \$column parameter is inserted directly into the SQL query.<br>Ensure it is sanitized to prevent SQL injection.<br>Results are ordered by ID in descending order and limited to 10 records. |
| public | `find(string\|int $id): array\|false`<br> |
| public | `update(array $fields): void`<br> |
| public | `create(array $fields): void`<br> |
| public | `delete(string\|int $id): void`<br> |
| protected static | `updateStmtString(array $fields): string`<br>Generates a string of fields and placeholders for an SQL UPDATE statement.<br>The method takes an array of fields and constructs a comma-separated list of "key=:key" pairs.<br>This string can be directly used in the SET clause of an SQL UPDATE query. |
| protected static | `createStmtString(array $fields): array`<br>Generates two strings for an SQL INSERT statement: one for column names and one for placeholders.<br>The method takes an array of fields and constructs two comma-separated lists:<br>- A list of column names.<br>- A list of placeholders (prefixed with colons) for parameter binding.<br>These strings can be directly used in the SQL INSERT query. |


<a id="rudra_model_traits_schematrait"></a>

### Class: Rudra\Model\Traits\SchemaTrait
| Visibility | Function |
|:-----------|:---------|
| public | `getColumns(): array`<br>Retrieves the column information for the current table based on the database driver.<br>The method executes a query specific to the database type (MySQL, PostgreSQL, or SQLite)<br>to fetch the column details of the table. |
| public | `getFields(?string $fields): array`<br>Retrieves the list of fields (columns) for the current table.<br>If no specific fields are provided, the method fetches all column names based on the database driver.<br>Otherwise, it splits the provided comma-separated string of fields into an array. |


---

###### created with [Rudra-Documentation-Collector](https://github.com/Jagepard/Rudra-Documentation-Collector)
