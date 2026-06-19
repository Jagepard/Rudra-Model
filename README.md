[![Build Status](https://travis-ci.org/Jagepard/Rudra-Model.svg?branch=master)](https://travis-ci.org/Jagepard/Rudra-Model)
[![Maintainability](https://qlty.sh/badges/ca8bb591-ff66-41c3-8f18-4d4a93b3ee41/maintainability.svg)](https://qlty.sh/gh/Jagepard/projects/Rudra-Model)
[![CodeFactor](https://www.codefactor.io/repository/github/jagepard/rudra-model/badge)](https://www.codefactor.io/repository/github/jagepard/rudra-model)
[![Coverage Status](https://coveralls.io/repos/github/Jagepard/Rudra-Model/badge.svg?branch=master)](https://coveralls.io/github/Jagepard/Rudra-Model?branch=master)
-----

# Rudra-Model | [API](https://github.com/Jagepard/Rudra-Model/blob/master/docs.md "Documentation API")

Rudra-Model is a lightweight, transparent, and ORM-free data access layer for the Rudra Framework. Built on the KISS principle, it avoids hidden dependencies and "magic" by providing direct access to PDO and a fluent Query Builder. 

Instead of a heavy ORM, it uses a simple and predictable delegation chain: **Entity → Model → Repository**. If a specific Model or Repository is not defined, it seamlessly falls back to the base `Repository` class, giving you out-of-the-box CRUD operations without writing boilerplate code.

## Architecture & Delegation
The component relies on a predictable fallback mechanism to minimize boilerplate:
1. **Entity**: The entry point for your domain objects. You only need to define the table name.
2. **Model**: Business logic layer. Calls are forwarded to the `Repository`.
3. **Repository**: Data access layer. Handles the actual database interaction. 

If you don't create a `Model` or `Repository` for your entity, the base `Repository` class automatically handles standard CRUD operations for the specified table.

## Usage Examples

### 1. Basic Entity Setup (Zero Boilerplate)
Define your entity and specify the table name. You don't need to create Model or Repository classes unless you need custom logic.

```php
namespace App\Containers\SomeContainer\Entity;

use Rudra\Model\Entity;

class User extends Entity
{
    public static ?string $table = 'users';
}

// Usage:
$users = User::getAll(); // Calls base Repository::getAll()
$user  = User::find(1);  // Calls base Repository::find()
User::create(['name' => 'John', 'email' => 'john@example.com']);
```
### 2. Custom Repository Logic
If you need custom queries, simply create a Repository class. The Entity will automatically route calls to it.
```php
namespace App\Containers\SomeContainer\Repository;

use Rudra\Model\Repository;

class UserRepository extends Repository
{
    public function findActiveUsers(): array
    {
        return $this->qBuilder("SELECT * FROM {$this->table} WHERE active = 1");
    }
}

// Usage:
$activeUsers = User::findActiveUsers(); // Automatically routed to UserRepository
```
### 3. Using the Query Builder (QB)
Build queries fluently. The QB simply builds the SQL string, which is then executed by the Repository.
```php
use Rudra\Model\QBFacade as QB;

$query = QB::select('id, name, email')
    ->from('users')
    ->where('status = :status')
    ->and('role = :role')
    ->orderBy('created_at DESC')
    ->limit(10)
    ->get();

// Resulting SQL: 
// SELECT id, name, email FROM users WHERE status = :status AND role = :role ORDER BY created_at DESC LIMIT 10;

// Execute via Repository:
$results = User::qBuilder($query, ['status' => 'active', 'role' => 'admin']);
```
### 4. Creating Tables (Schema)
Define your database schema using the Query Builder.
```php
use Rudra\Model\Schema;

Schema::create('users', function ($table) {
    $table->integer('id', '', true) // auto-increment
          ->string('name')
          ->string('email')
          ->text('bio', 'NULL')
          ->created_at()
          ->updated_at()
          ->pk('id');
})->execute();
```
### 5. Simple File Caching
Cache query results to JSON files to reduce database load. Simple, reliable, and easy to clear.
```php
// Cache the result of getAll()
$users = User::cache(['getAll']);

// Cache a custom method with parameters (e.g., '+1 hour' or '+1 day')
$posts = Post::cache(['findBy', ['category', 'news']], '+1 hour');

// Clear cache after data modification (automatically called in create/update/delete)
User::clearCache('database'); 
```
## License

This project is licensed under the **Mozilla Public License 2.0 (MPL-2.0)** — a free, open-source license that:

- Requires preservation of copyright and license notices,
- Allows commercial and non-commercial use,
- Requires that any modifications to the original files remain open under MPL-2.0,
- Permits combining with proprietary code in larger works.

📄 Full license text: [LICENSE](./LICENSE)  
🌐 Official MPL-2.0 page: https://mozilla.org/MPL/2.0/