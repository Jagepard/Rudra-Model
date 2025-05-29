## Table of contents
- [Rudra\Model\Driver\MySQL](#rudra_model_driver_mysql)
- [Rudra\Model\Driver\PgSQL](#rudra_model_driver_pgsql)
- [Rudra\Model\Driver\SQLite](#rudra_model_driver_sqlite)
- [Rudra\Model\Entity](#rudra_model_entity)
- [Rudra\Model\Model](#rudra_model_model)
- [Rudra\Model\QB](#rudra_model_qb)
- [Rudra\Model\QBFacade](#rudra_model_qbfacade)
- [Rudra\Model\Repository](#rudra_model_repository)
<hr>

<a id="rudra_model_driver_mysql"></a>

### Class: Rudra\Model\Driver\MySQL
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>concat</strong>( string $fieldName  string $alias  string $orderBy ): string</em><br>|
|public|<em><strong>close</strong>(): string</em><br>|
|public|<em><strong>integer</strong>( string $field  string $default  bool $autoincrement  string $null ): string</em><br>|
|public|<em><strong>string</strong>( string $field  string $default  string $null ): string</em><br>|
|public|<em><strong>text</strong>( string $field  string $null ): string</em><br>|
|public|<em><strong>created_at</strong>(): string</em><br>|
|public|<em><strong>updated_at</strong>(): string</em><br>|
|public|<em><strong>pk</strong>(  $field ): string</em><br>|


<a id="rudra_model_driver_pgsql"></a>

### Class: Rudra\Model\Driver\PgSQL
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>concat</strong>( string $fieldName  string $alias  string $orderBy ): string</em><br>|
|public|<em><strong>close</strong>(): string</em><br>|
|public|<em><strong>integer</strong>( string $field  string $default  bool $pk  string $null ): string</em><br>|
|public|<em><strong>string</strong>( string $field  string $default  string $null ): string</em><br>|
|public|<em><strong>text</strong>( string $field  string $null ): string</em><br>|
|public|<em><strong>created_at</strong>(): string</em><br>|
|public|<em><strong>updated_at</strong>(): string</em><br>|
|public|<em><strong>pk</strong>( string $field ): string</em><br>|


<a id="rudra_model_driver_sqlite"></a>

### Class: Rudra\Model\Driver\SQLite
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>concat</strong>( string $fieldName  string $alias  string $orderBy ): string</em><br>|
|public|<em><strong>close</strong>(): string</em><br>|
|public|<em><strong>integer</strong>( string $field  string $default  bool $pk  string $null ): string</em><br>|
|public|<em><strong>string</strong>( string $field  string $default  string $null ): string</em><br>|
|public|<em><strong>text</strong>( string $field  string $null ): string</em><br>|
|public|<em><strong>created_at</strong>(): string</em><br>|
|public|<em><strong>updated_at</strong>(): string</em><br>|
|public|<em><strong>pk</strong>( string $field ): string</em><br>|


<a id="rudra_model_entity"></a>

### Class: Rudra\Model\Entity
| Visibility | Function |
|:-----------|:---------|
|public static|<em><strong>__callStatic</strong>(  $method  array $parameters )</em><br>|
|public|<em><strong>__call</strong>(  $method  array $parameters )</em><br>|
|protected static|<em><strong>callMethod</strong>(  $method  array $parameters )</em><br>|


<a id="rudra_model_model"></a>

### Class: Rudra\Model\Model
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>__construct</strong>( string $table )</em><br>|
|public|<em><strong>__call</strong>(  $method  array $parameters )</em><br>|


<a id="rudra_model_qb"></a>

### Class: Rudra\Model\QB
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>__construct</strong>()</em><br>|
|public|<em><strong>select</strong>( string $fields ): self</em><br>|
|public|<em><strong>concat</strong>( string $fieldName  string $alias  ?string $orderBy ): self</em><br>|
|public|<em><strong>from</strong>( string $table ): self</em><br>|
|public|<em><strong>where</strong>( string $param ): self</em><br>|
|public|<em><strong>and</strong>( string $param ): self</em><br>|
|public|<em><strong>or</strong>( string $param ): self</em><br>|
|public|<em><strong>limit</strong>(  $param ): self</em><br>|
|public|<em><strong>offset</strong>(  $param ): self</em><br>|
|public|<em><strong>orderBy</strong>( string $param ): self</em><br>|
|public|<em><strong>groupBy</strong>( string $param ): self</em><br>|
|public|<em><strong>join</strong>( string $param  string $type ): self</em><br>|
|public|<em><strong>on</strong>( string $param ): self</em><br>|
|public|<em><strong>get</strong>(): string</em><br>|
|public|<em><strong>create</strong>( string $table ): self</em><br>|
|public|<em><strong>close</strong>(): self</em><br>|
|public|<em><strong>integer</strong>( string $field  string $default  bool $autoincrement  string $null ): self</em><br>|
|public|<em><strong>string</strong>( string $field  string $default  string $null ): self</em><br>|
|public|<em><strong>text</strong>( string $field  string $null ): self</em><br>|
|public|<em><strong>created_at</strong>(): self</em><br>|
|public|<em><strong>updated_at</strong>(): self</em><br>|
|public|<em><strong>pk</strong>( ?string $field ): self</em><br>|


<a id="rudra_model_qbfacade"></a>

### Class: Rudra\Model\QBFacade
| Visibility | Function |
|:-----------|:---------|
|public static|<em><strong>__callStatic</strong>( string $method  array $parameters ): mixed</em><br>|


<a id="rudra_model_repository"></a>

### Class: Rudra\Model\Repository
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>__construct</strong>( string $table )</em><br>|
|public|<em><strong>__call</strong>(  $method  array $parameters )</em><br>|
|public|<em><strong>qBuilder</strong>(  $queryString  array $queryParams ): array</em><br>Represents a prepared database query and, when the query is executed, the corresponding result set.<br>Представляет подготовленный запрос к базе данных, а после выполнения запроса соответствующий результирующий набор. |
|public|<em><strong>getAllPerPage</strong>( Rudra\Pagination $pagination  ?string $fields )</em><br>|
|public|<em><strong>find</strong>(  $id ): array|false</em><br>|
|public|<em><strong>getAll</strong>( string $sort  ?string $fields )</em><br>|
|public|<em><strong>numRows</strong>()</em><br>|
|public|<em><strong>findBy</strong>(  $field   $value )</em><br>|
|public|<em><strong>lastInsertId</strong>()</em><br>|
|public|<em><strong>update</strong>( array $fields )</em><br>|
|public|<em><strong>create</strong>( array $fields )</em><br>|
|public|<em><strong>delete</strong>(  $id )</em><br>|
|protected static|<em><strong>updateStmtString</strong>( array $fields )</em><br>Prepares a row to update the database|
|protected static|<em><strong>createStmtString</strong>( array $fields )</em><br>Prepares a row to be added to the database|
|public|<em><strong>getColumns</strong>()</em><br>|
|public|<em><strong>getFields</strong>( ?string $fields )</em><br>|
|public|<em><strong>search</strong>( string $search  string $column  ?string $fields )</em><br>|
|public|<em><strong>toggle</strong>()</em><br>Helper method for writing a toggle|
|public|<em><strong>qCache</strong>( array $params   $cacheTime )</em><br>|
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
