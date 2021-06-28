<?php

namespace App\Controllers;

use PDO;
use Slim\Http\Request;

class ProductController extends Controller
{

    protected $fillable = ['name', 'description'];

    /**
     *  get a specific  product
     */
    // public function get($id)
    // {
    //     $product = $this->container->get('db')->prepare("
    //     SELECT * 
    //     FROM product
    //     WHERE id = :id
    //     ");
       
    //     $product->execute(['id' => $id]);
 
    //     $product = $product->fetch(PDO::FETCH_OBJ);
 
    //     if(!$product)
    //     {
    //         return $this->response->withStatus(404);
    //     }
       
    //     $result = $this->response->getBody();

    //     $result->write(json_encode($product));

    //     #var_dump($result);

    //     return $this->response->withStatus(200)->withBody($result);
       
    // }

    /**
     * better way to solve 
     *  get a specific  product
     */
    public function get($id)
    {
        $product = $this->container->get('db')->prepare("
            SELECT * 
            FROM product
            WHERE id = :id
        ");
       
        $product->execute(['id' => $id]);
 
        $product = $product->fetch(PDO::FETCH_OBJ);
 
        if(!$product)
        {
            return $this->response->withStatus(404);
        }
       
        return $this->response(json_encode($product), 200);
    }
   
    public function put($id)
    {
        $parsedBody = $this->request->getParsedBody();

        $fill =  array_filter (array_map ( function ($column) {
            #return $column;

            if(in_array($column, $this->fillable))
            {
                return "{$column} = :{$column}";
            }
        }, array_keys($parsedBody)));

       if(empty($fill))
       {
           return $this->response('null', 404);
       }

       $update = $this->container->get('db')->prepare("
            UPDATE product
            SET " . implode(',', $fill) . "
            WHERE id = :id
       ");

       $update->execute(array_merge(['id' => $id], $parsedBody));

       if(!$update)
       {
           return $this->response('null', 404);
       }
    }
    /**
     * 
     * create a new record
     */
    public function post()
    {
        $body = $this->request->getParsedBody();
      
        if(!isset($body['name'], $body['description']))
        {
            return $this->response('null', 404);
        }
        
        if(empty($body['name']) || empty($body['description']))
        {
            return $this->response('Please fill the fields correctly!!!', 404);
        }
       
        $create =  $this->container->get('db')->prepare("
            INSERT INTO product (name, description)
            VALUES(:name, :description)
        ");

        $create->execute([
            'name' => $body['name'],
            'description' => $body['description'],
        ]);

        return $this->response('Successfully added a new product', 200);
    }


    /**
     * Delete record from the database
     */
    public function delete($id)
    {
        
        $delete =  $this->container->get('db')->prepare("
            DELETE FROM product WHERE id = :id;
        ");

        $delete->execute(['id' => $id]);
        return $this->response('Record with the id: $id is Successfully deleted from your DB', 200);
    }
}
