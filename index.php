<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$dsn = 'mysql:host=localhost;dbname=mi_base_de_datos;charset=utf8';
$username = 'upso_crud';
$password = 'upso';

// Conexión a la base de datos
try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["message" => "Error de conexión: " . $e->getMessage()]);
    exit();
}

// Función para obtener todos los productos
function obtenerProductos($db) {
    $query = "SELECT id, name, description, price FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        echo json_encode(["message" => "No se encontraron productos."]);
    }
}

// Función para crear un producto
function crearProducto($db) {
    $data = json_decode(file_get_contents("php://input"), true);
    //echo json_encode($data);
    if (!empty($data['name'])
     && !empty($data['description'])
     && !empty($data['price'])
     && !empty($data['id'])

     ) {
        $query = "INSERT INTO products (id,name, description, price) VALUES (:id,:name, :description, :price)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id',$data['id']);
        //echo json_encode(["message" => "Bandera."]);

        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);

        try{
            if ($stmt->execute()) {
                echo json_encode(["message" => "Producto creado exitosamente."]);
            } else {
                echo json_encode(["message" => "Error al crear el producto."]);
            }
        }
        catch(Exception $e)
        {
            echo json_encode(["message" => "Error al crear el producto."]);
        }
        
    } else {
        echo json_encode(["message" => "Datos incompletos."]);
    }
}

// Función para actualizar un producto
function actualizarProducto($db) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!empty($data['id']) 
    && !empty($data['name']) 
    && !empty($data['description']) 
    && !empty($data['price'])) {
        $query = "UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Producto actualizado exitosamente."]);
        } else {
            echo json_encode(["message" => "Error al actualizar el producto."]);
        }
    } else {
        echo json_encode(["message" => "Datos incompletos."]);
    }
}

// Función para eliminar un producto
function eliminarProducto($db) {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!empty($data['id'])) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data['id']);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Producto eliminado exitosamente."]);
        } else {
            echo json_encode(["message" => "Error al eliminar el producto."]);
        }
    } else {
        echo json_encode(["message" => "Datos incompletos."]);
    }
}

// Manejo de las solicitudes HTTP
switch ($method) {
    case 'GET':
        obtenerProductos($db);
        break;
    
    case 'POST':
        crearProducto($db);
        break;
    
    case 'PUT':
        actualizarProducto($db);
        break;
    
    case 'DELETE':
        eliminarProducto($db);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido."]);
        break;
}

?>