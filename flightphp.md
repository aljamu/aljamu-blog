# Flight PHP
Flight is a fast, easy and scalable Framework. Main Focus is to be easy to understand and use.

## Routing
Map URL patterns to callback / class-functions (connect HTTP request with application logic)
- fast and simple request handling
- Show routes with methods with: php runway routes
### Routes
```
Flight::route('/', function(){
    echo 'hello world!';
});
OR
Flight::route('/', 'hello');
function hello() {
    echo 'hello world!';
}
OR
class GreetingController {
    public function hello() {
        echo 'hello world!';
    }
}
Flight::route('/', [ GreetingController::class, 'hello' ]); // preferred method
```
### Named Parameters
```
Flight::route('/@name/@id', function (string $name, string $id) {
  echo "hello, $name ($id)!";
});

Flight::route('/@name/@id:[0-9]{3}', function (string $name, string $id) {
  // This will match /bob/123
  // But will not match /bob/12345
});

Flight::route(
  '/blog(/@year(/@month))',
  function(?string $year, ?string $month, ?string $day) {
    // This will match the following URLS:
    // /blog/2012/12, /blog/2012, /blog
  }
);

Flight::route('/blog/*', function () {
  // This will match /blog/2000/02/01
});
```
### Route Grouping
```
Flight::group('/api/v1', function () {
  Flight::route('/users', function () {
    // Matches /api/v1/users
  });

  Flight::route('/posts', function () {
    // Matches /api/v1/posts
  });
});
```
### Ressource Routing (one to rule them all)
```
Flight::resource('/users', UsersController::class);
//will generate
[
      'index' => 'GET /users',
      'create' => 'GET /users/create',
      'store' => 'POST /users',
      'show' => 'GET /users/@id',
      'edit' => 'GET /users/@id/edit',
      'update' => 'PUT /users/@id',
      'destroy' => 'DELETE /users/@id'
]
```
Can be adjusted and customized. See [docs](https://docs.flightphp.com/en/v3/learn/routing)
### HTTP Methods
```
Flight::route('GET /', function () {
  echo 'I received a GET request.';
});

Flight::route('POST /', function () {
  echo 'I received a POST request.';
});

// You cannot use Flight::get() for routes as that is a method 
//    to get variables, not create a route.
Flight::post('/', function() { /* code */ });
Flight::patch('/', function() { /* code */ });
Flight::put('/', function() { /* code */ });
Flight::delete('/', function() { /* code */ });
OR
Flight::route('GET|POST /', function () {
  echo 'I received either a GET or a POST request.';
});
```
## Middleware
Middleware is executed before or after the route callback
- middleware for routes and group routes
- good for authentication or permission
- allows you to control your routes by assigning your custom app logic against them.
```
User request at URL /api ----> 
    Middleware->before() executed ----->
        Callable/method attached to /api executed and response generated ------>
    Middleware->after() executed ----->
User receives response from server
```
### Execution order
Ex: Middleware1->before(), Middleware2->before(), Middleware2->after(), Middleware1->after().

### Basic usage (example Logging In Validation)
Middleware can (and should) be registered as a class. If you need the "after" functionality, you must use a class.
```
use flight\Engine;

class LoggedInMiddleware {

    protected Engine $app;

    public function __construct(Engine $app) {
        $this->app = $app;
    }

    public function before(array $params) {
        $session = $this->app->session();
        if($session->get('logged_in') !== true) {
            $this->app->redirect('/login');
            exit;
        }
    }
}

// routes.php
$router->group('/admin', function(Router $router) {
    $router->get('/dashboard', [ DashboardController::class, 'index' ]);
    $router->get('/clients', [ ClientController::class, 'index' ]);
    // more routes...
}, [ LoggedInMiddleware::class ]);
```
other useful middleware examples (route parameters validation and api key validation) [here](https://docs.flightphp.com/en/v3/learn/middleware)

### Handling Middleware Execution
1. return status liek 403 Forbidden
2. redirect using ```Flight::redirect()```
3. create custom error

code examples [here](https://docs.flightphp.com/en/v3/learn/middleware#handling-middleware-execution)

## Request
PHP has several super globals including $_GET, $_POST, $_REQUEST, $_SERVER, $_FILES, and $_COOKIE. It's better to not use these Superglobals
- $_GET, acces via query property
```
// GET /search?keyword=something
Flight::route('/search', function(){
    $keyword = Flight::request()->query->keyword;
    echo "You are searching for: $keyword";
});
```
- $_POST, acces via data property
```
Flight::route('POST /submit', function(){
    $name = Flight::request()->data->name;
    $email = Flight::request()->data->email;
    echo "You submitted: $name, $email";
});
```
- $_COOKIE_, acces via data property
```
Flight::route('GET /login', function(){
    $savedLogin = Flight::request()->cookies->myLoginCookie;
    // check if it's really saved or not and if it is auto log them in
    if($savedLogin) {
        Flight::redirect('/dashboard');
        return;
    }
});
```
there are also properties for $_SERVER and $_FILES [here](https://docs.flightphp.com/en/v3/learn/requests#server)