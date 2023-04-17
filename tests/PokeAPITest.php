<?php

use PHPUnit\Framework\TestCase;
use Understrap_Child\Data_Providers\PokeAPI;

class PokeAPITest extends TestCase {
    
    private $pokeAPI;
    
    protected function setUp(): void {
        $this->pokeAPI = new PokeAPI();
    }
    
    public function testGetPokemonInfo() {
        $info = $this->pokeAPI->get_pokemon_info('pikachu');
        
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('description', $info);
        $this->assertArrayHasKey('primary_type', $info);
        $this->assertArrayHasKey('attacks', $info);
        $this->assertNotEmpty($info['name']);
        $this->assertNotEmpty($info['description']);
        $this->assertNotEmpty($info['primary_type']);
        $this->assertNotEmpty($info['attacks']);
    }
    
    public function testGetTotalResults() {
        $totalResults = $this->pokeAPI->get_total_results();
        
        $this->assertIsArray($totalResults);
        $this->assertNotEmpty($totalResults);
    }
    
}
?>
