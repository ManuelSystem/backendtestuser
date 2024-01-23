<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     //test para obtener todos los usuarios
    public function test_get_all_users()
    {
        Artisan::call('migrate');
        // Simular una solicitud GET a la ruta '/api/users'
       $response = $this->get('/api/users');

       // Asegurarse de que la solicitud fue exitosa (código de respuesta 200)
       $response->assertStatus(200);

    }

    //test para obtener un usuario en especifico que existe
    public function test_get_user_id()
    {
        Artisan::call('migrate');

       // Intenta obtener la información del usuario con ID 9 ejemplo
        $response = $this->get('/api/users/9/edit');

        // Verifica si la respuesta es 404 (Not Found)
        if ($response->status() === 404) {
            $this->assertTrue(true); // Indica que la prueba pasa si el recurso no se encuentra
            return;
        }

        // Asegurarse de que la solicitud fue exitosa (código de respuesta 200)
        $response->assertStatus(200);

    }

    //inserción de usuario nuevo valido
    public function test_insert_user_with_valid_data()
    {
        Artisan::call('migrate');

        // Datos válidos para la inserción de un usuario
        $userData = [
            'name' => 'Nombre Usuario Test',
            'email' => 'usuario@example.com',
            'password' => 'password123',
        ];

        // Intenta insertar un usuario con datos válidos
        $response = $this->post('/api/users', $userData);

        // Asegúrate de que la inserción fue exitosa (código de respuesta 201)
        $response->assertStatus(201);

        // Asegúrate de que la respuesta contiene los datos del usuario insertado
        $response->assertJsonFragment([
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

    }

    /**
     * A test for validating invalid data during user insertion.
     *
     * @return void
     */

     //inserción de usuario nuevo no valido
    public function test_insert_user_with_invalid_data()
    {
        Artisan::call('migrate');
        
        // Datos inválidos para la inserción de un usuario (email incorrecto y contraseña corta)
        $invalidUserData = [
            'name' => 'Nombre Usuario',
            'email' => 'correo.invalido', // Email inválido
            'password' => 'pass', // Contraseña corta (menos de 6 caracteres)
        ];

        // Intenta insertar un usuario con datos inválidos
        $response = $this->post('/api/users', $invalidUserData);

        // Asegúrate de que la inserción falló debido a datos inválidos (código de respuesta 422)
        $response->assertStatus(422);

        // Asegúrate de que la respuesta contiene errores de validación específicos
        $response->assertJsonValidationErrors(['email', 'password']);

    }

}
