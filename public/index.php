<?php
/* @created by bambang priyatna 2017 */
/* @work At PT Imani Prima */
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    echo ($file);
    if (is_file($file)) {
        return false;
    }
}
// use \Psr\Http\Message\StreamInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// customers routes
//require __DIR__ . '/../src/routes/customer.php';

// Run app
//$app->run();
$app = new \Slim\App;



$app->post('/login', function (Request $request, Response $response) {
    $username = $request->getParsedBody()['username'];
    $password = $request->getParsedBody()['password'];
    $db = DBConnection (); 
    $result = $db->query("select * from user where username = '$username' and password = '$password' ")->fetchAll(PDO::FETCH_OBJ); 
        if (empty($result)){
         return $response->withJson([
             'success'=>false,'message'=>'username atau password false']);
          } else {
            $key = "example_key";
            $token = array(
                "iss" => new DateTime(),
                "aud" => new DateTime("now +2 hours"),
                "iat" => 1356999524,
                "nbf" => 1357000000
            );

            /**
            * IMPORTANT:
            * You must specify supported algorithms for your application. See
            * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
            * for a list of spec-compliant algorithms.
            */
         $jwt = JWT::encode($token, $key);
         $data["status"] = "success";
         $data["message"] = "berhasil login!!";
           return $response->withStatus(201)
             ->withHeader("Content-Type", "application/json")
             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
             ->withHeader('Authorization',$jwt)
             ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

         }       
});



$app->get('/get', function (Request $request, Response $response) use($app){
    $key = 'example_key';
    $jwt = $request->getHeader('Authorization')[0];
    $db = DBConnection ();
    $result = $db->query("select * from user")->fetchAll(PDO::FETCH_OBJ);
    try {
        $decoded = JWT::decode($jwt, $key, array("HS256"));
        return $response->withStatus(200)
             ->withHeader("Content-Type", "application/json")
             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
             ->withHeader('Authorization',$jwt)
             ->write(json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    } catch (Exception $e) {
        echo "must Authorization";
    }
    
});



//show all
$app->post('/test', function (Request $request, Response $response) use($app) {
     
     $username = $request->getParsedBody()['username'];
     $db = DBConnection ();
      
     $result = $db->query("select * from user where username = '$username'  ")->fetchAll(PDO::FETCH_OBJ);
     
     return $response->withJson ($result);
    // echo json_encode($result,$data);
    // return $response
    //         ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
    //         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    //         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


//show all
$app->get('/show', function (Request $request,Response $response) use($app) {
    // $this->token;
     $db = DBConnection ();
     $result = $db->query("select * from user")->fetchAll(PDO::FETCH_OBJ);
     return $response->withJson ($result);
     //echo json_encode($result);
});

// showby id
$app->get('/show/{id}', function (Request $request,Response $response) use($app) {
     $db = DBConnection ();
     $id = $request->getAttribute('id');
     $result = $db->query("select * from user where id = $id")->fetchAll(PDO::FETCH_OBJ);
     //var_dump($jwt);
     return $response->withJson ($result);
});





$app->get('/token', function (Request $request,Response $response) use($app) {
 // echo ('test'); 
 //echo ($username);
     $now = new DateTime(); 
     $future = new DateTime("now +2 hours");
     $server = $request->getServerParams(); 
     $payload = [
         "iat" => Time(),
         "exp" => Time(),
         "jti" => $jti,
         "sub" => $server["HTTP_HOST"]
     ];
   
     $token = JWT::encode($payload, $secret, "HS256");
     //var_dump ($token);
     $data["status"] = "success";
     //$data["token"] = $token;
    
     return $response->withStatus(201)
         ->withHeader("Content-Type", "application/json")
         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
         ->withHeader('Authorization',$token)
         ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
//$decoded = JWT::decode($jwt, $key, array('HS256'));

});





function DBConnection () {
    return new PDO ('mysql:dbhost=localhost;dbname=customer','root','123456');
};


$app->run();