## Table of contents
- [Rudra\Model\Model](#rudra_model_model)
- [Rudra\Model\QB](#rudra_model_qb)
- [Rudra\Model\QBFacade](#rudra_model_qbfacade)
<hr>

<a id="rudra_model_model"></a>

### Class: Rudra\Model\Model
| Visibility | Function |
|:-----------|:---------|
|public static|<em><strong>__callStatic</strong>(  $method   $parameters )</em><br>Calls unavailable methods in a static context in the Repository namespace<br>Вызывает недоступные методы в статическом контексте в пространстве имен репозитория.|
|public static|<em><strong>qBuilder</strong>(  $queryString   $queryParams )</em><br>Represents a prepared database query and, when the query is executed, the corresponding result set.<br>Представляет подготовленный запрос к базе данных, а после выполнения запроса соответствующий результирующий набор. |
|public static|<em><strong>getAllPerPage</strong>( Rudra\Pagination $pagination  ?string $fields )</em><br>Retrieves all data, taking into account paging.<br>Получает все данные с учетом постраничного разбиения.|
|public static|<em><strong>find</strong>(  $id )</em><br>Finds an element in the database by id<br>Находит элемент в базе данных по идентификатору|
|public static|<em><strong>getAll</strong>( string $sort  ?string $fields )</em><br>Retrieves all items from the database according to the parameters<br>Получает все элементы из базы данныхв соответствии с параметрами|
|public static|<em><strong>numRows</strong>()</em><br>Gets the number of rows in a specific table<br>Получает количество строк в определенной таблице|
|public static|<em><strong>findBy</strong>(  $field   $value )</em><br>Searches for an element by value in a given field<br>Ищет элемент по значению в заданном поле|
|public static|<em><strong>lastInsertId</strong>()</em><br>Returns the ID of the last inserted row or sequence value <br>Возвращает ID последней вставленной строки или значение последовательности |
|public static|<em><strong>update</strong>( array $fields )</em><br>Updates a record in the database<br>Обновляет запись в базе данных|
|public static|<em><strong>create</strong>( array $fields )</em><br>Adds an entry to the database<br>Добавляет запись в базу данных|
|public static|<em><strong>delete</strong>(  $id )</em><br>Deletes an entry in the database<br>Удаляет запись в базе данных|
|protected static|<em><strong>updateStmtString</strong>( array $fields )</em><br>Prepares a row to update the database<br>Подготавливает строку для обновления базы данных|
|protected static|<em><strong>createStmtString</strong>( array $fields )</em><br>Prepares a row to be added to the database<br>Подготавливает строку для добавления в базу данных|
|public static|<em><strong>getColumns</strong>()</em><br>Gets the names of the columns in the table<br>Получает название столбцов в таблице|
|public static|<em><strong>getFields</strong>( ?string $fields )</em><br>Gets the values of fields in a table<br>Получает значение полей в таблице|
|public static|<em><strong>search</strong>( string $search  string $column  ?string $fields )</em><br>Searches the table for data that matches the parameters<br>Ищет в таблице данные соответствующие параметрам|
|public static|<em><strong>toggle</strong>()</em><br>Helper method for writing a toggle<br>Вспомогательный метод для написания переключателя|
|public static|<em><strong>qCache</strong>( array $params   $cacheTime )</em><br>Caches a database query<br>Кэширует запрос к базе данных|


<a id="rudra_model_qb"></a>

### Class: Rudra\Model\QB
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>select</strong>( ?string $fields )</em><br>Selects data from the database<br>Выбирает данные из базы данных|
|public|<em><strong>array_agg</strong>( string $fieldName  string $alias  string $orderBy )</em><br>Accepts a set of values<br>Принимает набор значений|
|public|<em><strong>from</strong>(  $table )</em><br>Specifies the table name<br>Указывает название таблицы|
|public|<em><strong>where</strong>(  $param )</em><br>WHERE clause to filter rows returned by a SELECT statement<br>Предложение WHERE для фильтрации строк, возвращаемых инструкцией SELECT.|
|public|<em><strong>and</strong>(  $param )</em><br>Logical operator AND<br>Логический оператор И|
|public|<em><strong>or</strong>(  $param )</em><br>Logical operator OR<br>Логический оператор ИЛИ|
|public|<em><strong>limit</strong>(  $param )</em><br>LIMIT is an optional clause of the SELECT statement <br>LIMIT — необязательное предложение оператора SELECT.|
|public|<em><strong>offset</strong>(  $param )</em><br>OFFSET clause<br>Предложение OFFSET|
|public|<em><strong>orderBy</strong>(  $param )</em><br>To sort the rows of the result set, use the ORDER BY<br>Чтобы отсортировать строки результирующего набора, используйте ORDER BY|
|public|<em><strong>groupBy</strong>(  $param )</em><br>The GROUP BY clause divides the rows returned from the SELECT statement into groups<br>Предложение GROUP BY делит строки, возвращаемые инструкцией SELECT, на группы|
|public|<em><strong>join</strong>(  $param   $type )</em><br>PostgreSQL join is used to combine columns from one (selfjoin) or more tables<br>Соединение PostgreSQL используется для объединения столбцов из одной (самообъединение) или нескольких таблиц|
|public|<em><strong>on</strong>(  $param )</em><br>Matching the values<br>Соответствие значений|
|public|<em><strong>get</strong>(): string</em><br>Gets query string<br>Получает строку запроса|


<a id="rudra_model_qbfacade"></a>

### Class: Rudra\Model\QBFacade
| Visibility | Function |
|:-----------|:---------|
|public static|<em><strong>__callStatic</strong>( string $method  array $parameters )</em><br>Calls class methods statically<br>Вызывает методы класса статически|
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
