<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db = new mysqli('localhost', 'root', 'josegs98', 'controlangular');

// Configuración de cabeceras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}


$app->get("/prb", function() use($app){
	echo "prueba realizada correctamente";
});

/////////////////////////////////////Usuario//////////////////////////////////////////
$app->get('/usuarios', function() use($db, $app){
	$sql = 'SELECT * FROM usuario ORDER BY idusuario DESC;';
	$query = $db->query($sql);

	$usuarios = array();
	while ($usuario = $query->fetch_assoc()) {
		$usuarios[] = $usuario;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $usuarios
		);

	echo json_encode($result);
});

$app->post('/usuario', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['dni'])){
		$data['dni']=null;
	}

	if(!isset($data['nombre'])){
		$data['nombre']=null;
	}

	if(!isset($data['apellidos'])){
		$data['apellidos']=null;
	}

	if(!isset($data['email'])){
		$data['email']=null;
	}
	
		if(!isset($data['password'])){
		$data['password']=null;
	}
	
		if(!isset($data['rol'])){
		$data['rol']='ROLE_USER';
	}
	
		if(!isset($data['image'])){
		$data['image']=null;
	}

	$query = "INSERT INTO usuario VALUES(NULL,".
			 "'{$data['dni']}',".
			 "'{$data['nombre']}',".
			 "'{$data['apellidos']}',".
			 "'{$data['email']}',".
			 "'{$data['password']}',".
			 "'{$data['rol']}',".
			 "'{$data['image']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'Usuario NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'Usuario creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->get('/usuario/:idusuario', function($idusuario) use($db, $app){
	$sql = 'SELECT * FROM usuario WHERE idusuario = '.$idusuario;
	$query = $db->query($sql);

	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'Usuario no disponible'
	);

	if($query->num_rows == 1){
		$usuario = $query->fetch_assoc();

		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'data' 	=> $usuario
		);
	}

	echo json_encode($result);
});

$app->get('/delete-usuario/:idusuario', function($idusuario) use($db, $app){
	$sql = 'DELETE FROM usuario WHERE idusuario = '.$idusuario;
	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El usuario se ha eliminado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El usuario no se ha eliminado!!'
		);
	}

	echo json_encode($result);
});
$app->post('/update-usuario/:idusuario', function($idusuario) use($db, $app){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$sql = "UPDATE usuario SET ".
		   "dni = '{$data["dni"]}', ".
		   "nombre = '{$data["nombre"]}', ".
		   "apellidos = '{$data["apellidos"]}', ".
		   "email = '{$data["email"]}', ".
		   "password = '{$data["password"]}', ";

	if(isset($data['image'])){
 		$sql .= "image = '{$data["image"]}', ";
	}

	$sql .=	"rol = '{$data["rol"]}' WHERE idusuario = {$idusuario}";


	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El usuario se ha actualizado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El usuario no se ha actualizado!!'
		);
	}

	echo json_encode($result);

});

/////////////////////////////////////Articulos/////////////////////////////////////////

$app->post('/articulos', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['nombre'])){
		$data['nombre']=null;
	}

	if(!isset($data['descripcion'])){
		$data['descripcion']=null;
	}

	if(!isset($data['precio'])){
		$data['precio']=null;
	}

	if(!isset($data['imagen'])){
		$data['imagen']=null;
	}

	$query = "INSERT INTO articulos VALUES(NULL,".
			 "'{$data['nombre']}',".
			 "'{$data['descripcion']}',".
			 "'{$data['precio']}',".
			 "'{$data['imagen']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'Articulo NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'Articulo creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->get('/articulos', function() use($db, $app){
	$sql = 'SELECT * FROM articulos ORDER BY idarticulo DESC;';
	$query = $db->query($sql);

	$articulos = array();
	while ($articulo = $query->fetch_assoc()) {
		$articulos[] = $articulo;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $articulos
		);

	echo json_encode($result);
});

$app->get('/articulos/:idarticulo', function($idarticulo) use($db, $app){
	$sql = 'SELECT * FROM articulos WHERE idarticulo = '.$idarticulo;
	$query = $db->query($sql);

	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'Articulo no disponible'
	);

	if($query->num_rows == 1){
		$articulo = $query->fetch_assoc();

		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'data' 	=> $articulo
		);
	}

	echo json_encode($result);
});

$app->get('/delete-articulo/:idarticulo', function($idarticulo) use($db, $app){
	$sql = 'DELETE FROM articulos WHERE idarticulo = '.$idarticulo;
	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El producto se ha eliminado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El producto no se ha eliminado!!'
		);
	}

	echo json_encode($result);
});

$app->post('/update-articulo/:idarticulo', function($idarticulo) use($db, $app){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$sql = "UPDATE articulos SET ".
		   "nombre = '{$data["nombre"]}', ".
		   "descripcion = '{$data["descripcion"]}', ";

	if(isset($data['imagen'])){
 		$sql .= "imagen = '{$data["imagen"]}', ";
	}

	$sql .=	"precio = '{$data["precio"]}' WHERE idarticulo = {$idarticulo}";


	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El articulo se ha actualizado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El articulo no se ha actualizado!!'
		);
	}

	echo json_encode($result);

});

////////////////cargar en el servidor una imagen//////////////////////
$app->post('/upload-file', function() use($db, $app){
	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'El archivo no ha podido subirse'
	);

	if(isset($_FILES['uploads'])){
		$piramideUploader = new PiramideUploader();

		$upload = $piramideUploader->upload('image', "uploads", "uploads", array('image/jpeg', 'image/png', 'image/gif'));
		$file = $piramideUploader->getInfoFile();
		$file_name = $file['complete_name'];

		if(isset($upload) && $upload["uploaded"] == false){
			$result = array(
				'status' 	=> 'error',
				'code'		=> 404,
				'message' 	=> 'El archivo no ha podido subirse'
			);
		}else{
			$result = array(
				'status' 	=> 'success',
				'code'		=> 200,
				'message' 	=> 'El archivo se ha subido',
				'filename'  => $file_name
			);
		}
	}

	echo json_encode($result);
});

/////////////////////////////////////Clientes/////////////////////////////////////////

$app->post('/clientes', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['dni'])){
		$data['dni']=null;
	}

	if(!isset($data['nombre'])){
		$data['nombre']=null;
	}

	if(!isset($data['apellidos'])){
		$data['apellidos']=null;
	}

	if(!isset($data['telefono'])){
		$data['telefono']=null;
	}

	$query = "INSERT INTO clientes VALUES(NULL,".
			 "'{$data['dni']}',".
			 "'{$data['nombre']}',".
			 "'{$data['apellidos']}',".
			 "'{$data['telefono']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'Cliente NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'Cliente creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->get('/clientes', function() use($db, $app){
	$sql = 'SELECT * FROM clientes ORDER BY idcliente DESC;';
	$query = $db->query($sql);

	$clientes = array();
	while ($cliente = $query->fetch_assoc()) {
		$clientes[] = $cliente;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $clientes
		);

	echo json_encode($result);
});

$app->post('/update-cliente/:idcliente', function($idcliente) use($db, $app){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$sql = "UPDATE clientes SET ".
		   "dni = '{$data["dni"]}', ".
		   "nombre = '{$data["nombre"]}', ".
		   "apellidos = '{$data["apellidos"]}', ";

	$sql .=	"telefono = '{$data["telefono"]}' WHERE idcliente = {$idcliente}";

	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El cliente se ha actualizado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El cliente no se ha actualizado!!'
		);
	}

	echo json_encode($result);

});

$app->get('/clientes/:idcliente', function($idcliente) use($db, $app){
	$sql = 'SELECT * FROM clientes WHERE idcliente = '.$idcliente;
	$query = $db->query($sql);

	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'Cliente no disponible'
	);

	if($query->num_rows == 1){
		$cliente = $query->fetch_assoc();

		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'data' 	=> $cliente
		);
	}

	echo json_encode($result);
});

$app->get('/delete-cliente/:idcliente', function($idcliente) use($db, $app){
	$sql = 'DELETE FROM clientes WHERE idcliente = '.$idcliente;
	$query = $db->query($sql);

	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El cliente se ha eliminado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El cliente no se ha eliminado!!'
		);
	}

	echo json_encode($result);
});

//////////////////////////////documentos////////////////////////////////////

$app->post('/documentos', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = "INSERT INTO documento VALUES(NULL,".
			 "'{$data['idcliente']}',".
			 "'{$data['total']}',".
			 "'{$data['fecha']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'El documento NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'Documento creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->get('/documentos', function() use($db, $app){
	$sql = 'SELECT * FROM documento ORDER BY iddocumento DESC;';
	$query = $db->query($sql);

	$documentos = array();
	while ($documento = $query->fetch_assoc()) {
		$documentos[] = $documento;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $documentos
		);

	echo json_encode($result);
});

$app->get('/documentos/:iddocumento', function($iddocumento) use($db, $app){
	$sql = 'SELECT * FROM documento WHERE iddocumento = '.$iddocumento;
	$query = $db->query($sql);

	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'Documento no disponible'
	);

	if($query->num_rows == 1){
		$documento = $query->fetch_assoc();

		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'data' 	=> $documento
		);
	}

	echo json_encode($result);
});

////////////////////////////////detalledocumento//////////////////////////////////////

$app->post('/detalledocumento', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = "INSERT INTO detalledocumento VALUES(NULL,".
			 "'{$data['iddocumento']}',".
			 "'{$data['idarticulo']}',".
			 "'{$data['cantidad']}',".
			 "'{$data['precio']}'".
			 ");";

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'El detalleDocumento NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'DetallleDocumento creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->get('/detalledocumento/:iddocumento', function($iddocumento) use($db, $app){
	$sql = 'SELECT * FROM detalledocumento WHERE iddocumento = '.$iddocumento;
	$query = $db->query($sql);

	$detalledocumentos = array();
	while ($detalledocumento = $query->fetch_assoc()) {
		$detalledocumentos[] = $detalledocumento;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $detalledocumentos
		);

	echo json_encode($result);
});

$app->run();
