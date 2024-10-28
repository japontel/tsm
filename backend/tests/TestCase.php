<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Crear conexión SQLite en memoria
        $database = ':memory:';
        config([
            'database.connections.sqlite.database' => $database,
            'database.default' => 'sqlite',
        ]);

        // Asegurarse de que estamos usando la conexión correcta
        DB::purge();
        DB::disconnect('sqlite');
        
        // Ejecutar migraciones
        $this->artisan('migrate:fresh');
    }

    protected function tearDown(): void
    {
        // Limpiar después de cada test
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}