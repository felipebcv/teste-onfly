<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.s
     */
    public function run(): void
    {
        $destinations = [
            'São Paulo',
            'Rio de Janeiro',
            'Belo Horizonte',
            'Porto Alegre',
            'Curitiba',
            'Salvador',
            'Fortaleza',
            'Recife',
            'Brasília',
            'Manaus',
        ];

        foreach ($destinations as $destination) {
            DB::table('destinations')->insert([
                'name'        => $destination,
                'description' => "Brazil: {$destination}",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
